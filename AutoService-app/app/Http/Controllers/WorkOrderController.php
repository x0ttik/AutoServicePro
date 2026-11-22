<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkOrderController extends Controller
{
    public function index(): View
    {
        $orders = WorkOrder::query()
            ->with('vehicle')
            ->latest()
            ->orderByDesc('id')
            ->paginate(15);

        return view('work-orders.index', ['orders' => $orders]);
    }

    public function create(Vehicle $vehicle): View
    {
        $vehicle->load('client');

        return view('work-orders.create', ['vehicle' => $vehicle]);
    }

    public function store(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $data = $request->validate([
            'description' => ['required', 'string'],
        ], [
            'description.required' => 'Describe el trabajo solicitado.',
        ]);

        $order = new WorkOrder();
        $order->vehicle()->associate($vehicle);
        $order->creator()->associate($request->user());
        $order->description = $data['description'];
        $order->status = 'open';
        $order->save();

        return redirect()->route('work-orders.show', $order)
            ->with('status', 'Orden abierta correctamente.');
    }

    public function show(WorkOrder $workOrder): View
    {
        $workOrder->load(['vehicle.client', 'creator', 'items']);

        $services = $workOrder->status === 'open'
            ? Service::query()
                ->where('is_active', true)
                ->whereNotIn('id', $workOrder->items->pluck('service_id'))
                ->orderBy('name')
                ->get()
            : collect();

        $totalCents = $workOrder->items->sum(
            fn ($item) => $item->subtotalCents()
        );

        return view('work-orders.show', [
            'order' => $workOrder,
            'services' => $services,
            'totalCents' => $totalCents,
        ]);
    }

    public function updateStatus(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $data = $request->validateWithBag('orderTransition', [
            'expected_status' => ['required', 'in:open,in_progress,ready,delivered'],
            'target_status' => ['required', 'in:open,in_progress,ready,delivered'],
        ]);

        DB::transaction(function () use ($workOrder, $data): void {
            $order = WorkOrder::query()
                ->lockForUpdate()
                ->findOrFail($workOrder->getKey());

            if ($order->status !== $data['expected_status']) {
                throw ValidationException::withMessages([
                    'status' => 'La orden cambió de estado. Recarga la ficha y vuelve a intentarlo.',
                ])->errorBag('orderTransition');
            }

            $nextStatus = $order->nextStatus();

            if ($nextStatus === null || $nextStatus !== $data['target_status']) {
                throw ValidationException::withMessages([
                    'status' => 'La orden no puede avanzar al estado solicitado.',
                ])->errorBag('orderTransition');
            }

            if ($order->status === 'open' && ! $order->items()->exists()) {
                throw ValidationException::withMessages([
                    'status' => 'Agrega al menos un servicio a la cotización antes de iniciar el trabajo.',
                ])->errorBag('orderTransition');
            }

            $order->status = $nextStatus;

            if ($nextStatus === 'delivered') {
                $order->delivered_at = now();
            }

            $order->save();
        });

        return redirect()->route('work-orders.show', $workOrder)
            ->with('status', 'Estado de la orden actualizado.');
    }
}
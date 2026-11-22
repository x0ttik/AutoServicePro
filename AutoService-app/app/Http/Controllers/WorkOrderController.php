<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
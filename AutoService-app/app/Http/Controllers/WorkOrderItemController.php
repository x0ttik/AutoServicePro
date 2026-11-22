<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkOrderItemController extends Controller
{
    public function store(Request $request, WorkOrder $workOrder): RedirectResponse
    {
        $data = $request->validate([
            'service_id' => ['required', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'between:1,32767'],
        ], [
            'service_id.required' => 'Selecciona un servicio.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.between' => 'La cantidad debe estar entre 1 y 32767.',
        ]);

        $this->changeQuote($workOrder, function (WorkOrder $order) use ($data): void {
            $service = Service::query()
                ->whereKey($data['service_id'])
                ->where('is_active', true)
                ->sharedLock()
                ->first();

            if (! $service) {
                throw ValidationException::withMessages([
                    'service_id' => 'Selecciona un servicio activo del catálogo.',
                ]);
            }

            if ($order->items()->where('service_id', $service->id)->exists()) {
                throw ValidationException::withMessages([
                    'service_id' => 'Este servicio ya está en la orden. Ajusta su cantidad.',
                ]);
            }

            $order->items()->create([
                'service_id' => $service->id,
                'service_name' => $service->name,
                'unit_price_cents' => $service->base_price_cents,
                'quantity' => $data['quantity'],
            ]);
        });

        return redirect()->route('work-orders.show', $workOrder)
            ->with('status', 'Servicio agregado a la cotización.');
    }

    public function update(
        Request $request,
        WorkOrder $workOrder,
        WorkOrderItem $item
    ): RedirectResponse {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'between:1,32767'],
        ], [
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.between' => 'La cantidad debe estar entre 1 y 32767.',
        ]);

        $this->changeQuote($workOrder, function (WorkOrder $order) use ($item, $data): void {
            $storedItem = $order->items()->findOrFail($item->getKey());
            $storedItem->quantity = $data['quantity'];
            $storedItem->save();
        });

        return redirect()->route('work-orders.show', $workOrder)
            ->with('status', 'Cantidad actualizada.');
    }

    public function destroy(WorkOrder $workOrder, WorkOrderItem $item): RedirectResponse
    {
        $this->changeQuote($workOrder, function (WorkOrder $order) use ($item): void {
            $order->items()->findOrFail($item->getKey())->delete();
        });

        return redirect()->route('work-orders.show', $workOrder)
            ->with('status', 'Servicio retirado de la cotización.');
    }

    private function changeQuote(WorkOrder $workOrder, Closure $change): void
    {
        DB::transaction(function () use ($workOrder, $change): void {
            $order = WorkOrder::query()
                ->lockForUpdate()
                ->findOrFail($workOrder->getKey());

            if ($order->status !== 'open') {
                throw ValidationException::withMessages([
                    'quote' => 'Solo puedes modificar la cotización de una orden abierta.',
                ]);
            }

            $change($order);
        });
    }
}
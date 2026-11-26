<?php

namespace Tests\Unit;

use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class WorkOrderTest extends TestCase
{
    public function test_calcula_los_subtotales_y_el_total_en_centavos(): void
    {
        $primerServicio = new WorkOrderItem([
            'unit_price_cents' => 30000,
            'quantity' => 2,
        ]);

        $segundoServicio = new WorkOrderItem([
            'unit_price_cents' => 45000,
            'quantity' => 1,
        ]);

        $orden = new WorkOrder();
        $orden->setRelation('items', new Collection([
            $primerServicio,
            $segundoServicio,
        ]));

        $this->assertSame(60000, $primerServicio->subtotalCents());
        $this->assertSame(45000, $segundoServicio->subtotalCents());
        $this->assertSame(105000, $orden->quoteTotalCents());
    }

    public function test_una_orden_lista_puede_avanzar_a_entregada(): void
    {
        $orden = new WorkOrder();
        $orden->status = 'ready';

        $this->assertSame('delivered', $orden->nextStatus());
    }


    public function test_una_orden_en_proceso_debe_pasar_por_lista_para_entrega(): void
    {
        $orden = new WorkOrder();
        $orden->status = 'in_progress';

        $this->assertSame('ready', $orden->nextStatus());
    }

    public function test_una_orden_abierta_tiene_que_pasar_a_en_proceso(): void
    {
        $orden = new WorkOrder();
        $orden->status = 'open';

        $this->assertSame('in_progress', $orden->nextStatus());
    }
}    
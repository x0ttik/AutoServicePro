<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_no_permite_registrar_servicios_con_nombre_duplicado(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $nombrePrueba = 'Servicio Duplicado Test ' . uniqid();

        $servicioExistente = new Service();
        $servicioExistente->name = $nombrePrueba;
        $servicioExistente->base_price_cents = 45000;
        $servicioExistente->is_active = true;
        $servicioExistente->save();

        $response = $this
            ->actingAs($admin)
            ->from(route('services.create'))
            ->post(route('services.store'), [
                'name' => $nombrePrueba,
                'price' => '500.00',
            ]);

        $response->assertRedirect(route('services.create'));
        $response->assertSessionHasErrors(['name']);
    }

    public function test_permite_editar_un_servicio_conservando_su_propio_nombre(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $nombrePrueba = 'Servicio Editable Test ' . uniqid();

        $servicio = new Service();
        $servicio->name = $nombrePrueba;
        $servicio->base_price_cents = 120000;
        $servicio->is_active = true;
        $servicio->save();

        $response = $this
            ->actingAs($admin)
            ->put(route('services.update', $servicio), [
                'name' => $nombrePrueba,
                'price' => '1350.00',
            ]);

        $response->assertRedirect(route('services.index'));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('services', [
            'id' => $servicio->id,
            'name' => $nombrePrueba,
            'base_price_cents' => 135000,
        ]);
    }
}
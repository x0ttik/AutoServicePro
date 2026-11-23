<?php

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    // Exercise the merge against an isolated database, never Supabase.
    config([
        'database.default' => 'sqlite',
        'database.connections.sqlite.database' => ':memory:',
        'database.connections.sqlite.url' => null,
    ]);
    DB::purge('sqlite');

    Schema::create('clients', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone');
        $table->timestamps();
    });

    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained('clients');
        $table->string('plate')->unique();
        $table->string('make');
        $table->string('model');
        $table->smallInteger('model_year');
        $table->timestamps();
    });
});

test('client screens and writes require authentication', function () {
    $client = Client::create(['name' => 'Cliente de prueba', 'phone' => '6641234567']);

    foreach (['clients.index', 'clients.create', 'clients.show', 'clients.edit'] as $route) {
        $this->get(route($route, ['cliente' => $client->id]))
            ->assertRedirect(route('login'));
    }

    $this->post(route('clients.store'), [])->assertRedirect(route('login'));
    $this->put(route('clients.update', ['cliente' => $client->id]), [])
        ->assertRedirect(route('login'));
});

test('client registration detail and editing survive the controller rename', function () {
    $this->actingAs(User::factory()->make(['id' => 1, 'role' => 'user']));

    $this->get(route('clients.create'))->assertOk()->assertSee('Guardar cliente');
    $this->post(route('clients.store'), ['name' => 'Cliente de prueba', 'phone' => '6641234567'])
        ->assertRedirect(route('clients.index'))
        ->assertSessionHas('success');

    $client = Client::sole();
    $this->get(route('clients.show', ['cliente' => $client->id]))
        ->assertOk()->assertSee('Cliente de prueba')->assertSee('6641234567');
    $this->get(route('clients.edit', ['cliente' => $client->id]))
        ->assertOk()->assertSee('Cliente de prueba');

    $this->put(route('clients.update', ['cliente' => $client->id]), [
        'name' => 'Nombre actualizado', 'phone' => '6647654321',
    ])->assertRedirect(route('clients.show', ['cliente' => $client->id]));

    expect(Client::count())->toBe(1);
    $this->get(route('clients.show', ['cliente' => $client->id]))
        ->assertOk()->assertSee('Nombre actualizado')->assertSee('6647654321');
});

test('invalid client registration and editing preserve existing records', function (string $field) {
    $this->actingAs(User::factory()->make(['id' => 1, 'role' => 'user']));
    $client = Client::create(['name' => 'Original', 'phone' => '6641234567']);
    $data = ['name' => 'Modificado', 'phone' => '6647654321'];
    $data[$field] = '   ';

    $this->from(route('clients.create'))->post(route('clients.store'), $data)
        ->assertRedirect(route('clients.create'))->assertSessionHasErrors($field);
    $this->from(route('clients.edit', ['cliente' => $client->id]))
        ->put(route('clients.update', ['cliente' => $client->id]), $data)
        ->assertRedirect(route('clients.edit', ['cliente' => $client->id]))
        ->assertSessionHasErrors($field);

    expect(Client::count())->toBe(1)
        ->and($client->fresh()->name)->toBe('Original')
        ->and($client->fresh()->phone)->toBe('6641234567');
})->with(['name', 'phone']);

test('client listing retains styling detail buttons and vehicle integration', function () {
    $this->actingAs(User::factory()->make(['id' => 1, 'role' => 'user']));
    $client = Client::create(['name' => 'Cliente de prueba', 'phone' => '6641234567']);

    $this->get(route('clients.index'))->assertOk()
        ->assertSee('tabla-clientes')->assertSee('Ver ficha')->assertSee('Registrar vehículo')
        ->assertSee(route('clients.show', ['cliente' => $client->id]), false)
        ->assertSee(route('vehicles.create', ['client' => $client->id]), false);
    $this->get(route('vehicles.create', ['client' => $client->id]))
        ->assertOk()->assertSee('Cliente de prueba');

    $client->vehicles()->create([
        'plate' => 'TEST123', 'make' => 'Toyota', 'model' => 'Corolla', 'model_year' => 2020,
    ]);
    expect($client->vehicles()->sole()->client->id)->toBe($client->id);
});

test('empty listings and missing client identifiers are handled', function () {
    $this->actingAs(User::factory()->make(['id' => 1, 'role' => 'user']));
    $this->get(route('clients.index'))->assertOk()->assertSee('No hay clientes registrados.');
    $this->get(route('clients.show', ['cliente' => 999]))->assertNotFound();
    $this->get(route('clients.edit', ['cliente' => 999]))->assertNotFound();
});

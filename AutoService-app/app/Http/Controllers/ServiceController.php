<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $esAdministrador = $request->user()->role === 'admin';

        $consulta = Service::query()->orderBy('name');

        if (! $esAdministrador) {
            $consulta->where('is_active', true);
        }

        $servicios = $consulta->get();

        return view('services.index', [
            'servicios' => $servicios,
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Location;

class LocationController extends Controller
{
    // Función que recibe una id por parámetro y hace la llamada a bd para traer la info de esa ubicación
    public function showLocation(Request $request)
    {
        try {
            Log::info('Llamada al método LocationController.getLocationById');
            $id = $request->query('id');
            $location = Location::getLocationById($id);
            return $location;
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->route('events.create')->with('error', '¡Atención! No se ha podido guardar la ubicación en nuestra base de datos.');
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info("Llamada al método LocationController.store");
            // Validación de la información del formulario
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'capacity' => 'required|integer|max:999999',
                'province' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'street' => 'required|string|max:150',
                'number' => 'required|string|max:3|numeric',
                'cp' => 'required|string|max:10|numeric',
            ]);
            if ($validatedData) {
                $location = Location::create($validatedData);
                $validatedData['id'] = $location->id;
                return redirect()->route('events.create')->with('newLocation', $validatedData);
            }
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->route('events.create')->with('error', '¡Atención! No se ha podido guardar la ubicación en nuestra base de datos.');
        }
    }
}

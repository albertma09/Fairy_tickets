<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Location;

class LocationController extends Controller
{
    public function store(Request $request){
        try {
            Log::info("Llamada al método LocationController.store");
            // Validación de la información del formulario
            $validatedData = $request->validate([
                'name' => 'required|string',
                'capacity' => 'required|integer',
                'province' => 'required|string',
                'city' => 'required|string',
                'street' => 'required|string',
                'number' => 'required|string',
                'cp' => 'required|string',
            ]);
            if($validatedData){
                Location::create($validatedData);
                return redirect()->route('events.create')->with('success', 'Los datos de la nueva ubicación han sido guardados con éxito en nuestra base de datos.');
            }
            
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->route('events.create')->with('error', '¡Atención! No se ha podido guardar la ubicación en nuestra base de datos.');
        }
    }
}

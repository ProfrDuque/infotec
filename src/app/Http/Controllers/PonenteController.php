<?php

namespace App\Http\Controllers;

use App\Models\Ponente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PonenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recuperar todos los ponentes
        $ponentes = Ponente::all();

        // Retornar los recursos recuperados
        $respuesta = [
            'ponentes' => $ponentes,
            'status' => 200,
        ];
        return response()->json($respuesta);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar que la petición contenga todos los datos necesarios
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'biografia' => 'required',
            'especialidad' => 'required',
        ]);

        // Si la petición no contiene todos los datos necesarios, retornar un mensaje de error
        if ($validator->fails()) {
            $respuesta = [
                'message' => 'Datos faltantes',
                'status' => 400, // Petición inválida
            ];
            return response()->json($respuesta, 400);
        }

        // Crear un nuevo recursos con los datos de la petición
        $ponente = Ponente::create([
            'nombre' => $request->nombre,
            'biografia' => $request->biografia,
            'especialidad' => $request->especialidad,
        ]);

        // Si el recurso no se pudo crear, retornar un mensaje deerror
        if (!$ponente) {
            $respuesta = [
                'message' => 'Error al crear el ponente',
                'status' => 500, // Error interno del servidor
            ];
            return response()->json($respuesta, 500);
        }

        // Retornar el recurso creado
        $respuesta = [
            'ponente' => $ponente,
            'status' => 201,
        ];
        return response()->json($respuesta, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        // Recurso especificado
        $ponente = Ponente::find($id);

        // Si el recurso no se pudo recuperar, retornar un mensaje de error
        if (!$ponente) {
            $respuesta = [
                'message' => 'Ponente no encontrado',
                'status' => 404, // No encontrado
            ];
            return response()->json($respuesta, 404);
        }

        // Retornar el recurso recuperado
        $respuesta = [
            'ponente' => $ponente,
            'status' => 200, // OK
        ];
        return response()->json($respuesta);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Recuperar el recurso especificado
        $ponente = Ponente::find($id);

        // Si el recurso no se pudo recuperar, retornar un mensaje de error
        if (!$ponente) {
            $respuesta = [
                'message' => 'Ponente no encontrado',
                'status' => 404, // No encontrado
            ];
            return response()->json($respuesta, 404);
        }
        // Validar que la petición contenga todos los datos necesarios
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'biografia' => 'required',
            'especialidad' => 'required',
        ]);
        // Si la petición no contiene todos los datos necesarios, retornar un mensaje de error
        if ($validator->fails()) {
            $respuesta = [
                'message' => 'Datos faltantes',
                'status' => 400, // Petición inválida
            ];
            return response()->json($respuesta, 400);
        }
        // Actualizar el recurso especificado con los datos de la petición
        $ponente->nombre = $request->nombre;
        $ponente->biografia = $request->biografia;
        $ponente->especialidad = $request->especialidad;
        $ponente->save();

        // Retornar el recurso actualizado
        $respuesta = [
            'ponente' => $ponente,
            'status' => 200, // OK
        ];
        return response()->json($respuesta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //  Recuperar el recurso especificado
        $ponente = Ponente::find($id);

        // Si el recurso no se pudo recuperar, retornar un mensaje de error
        if (!$ponente) {
            $respuesta = [
                'message' => 'Ponente no encontrado',
                'status' => 404, // No encontrado
            ];
            return response()->json($respuesta, 404);
        } else {
            // Eliminar el recurso especificado
            $ponente->delete();
            // Retornar un mensaje de éxito
            $respuesta = [
                'message' => 'Ponente eliminado',
                'status' => 200, // OK
            ];
            return response()->json($respuesta);
        }
    }
}
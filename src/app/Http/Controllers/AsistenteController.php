<?php

namespace App\Http\Controllers;

// Agregar el modelo Asistente
use App\Models\Asistente;
// Agregar el modelo Evento
use App\Models\Evento;
// Agregar la clase Request para manejar las peticiones
use Illuminate\Http\Request;
// Agregar la clase Validator para validaciones
use Illuminate\Support\Facades\Validator;

class AsistenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recuperar todos los asistentes
        $asistentes = Asistente::all();

        // Retornar los recursos recuperados
        $respuesta = [
            'asistentes' => $asistentes,
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
            'email' => 'required',
            'telefono' => 'required',
            'evento_id' => 'required',
        ]);

        // Si la petición no contiene todos los datos necesarios, retornar un mensaje de error
        if ($validator->fails()) {
            $respuesta = [
                'message' => 'Datos faltantes',
                'status' => 400, // Petición inválida
            ];
            return response()->json($respuesta, 400);
        }

        $evento = Evento::find($request->evento_id);

        // Si el evento no se pudo recuperar, retornar un mensaje de error
        if (!$evento) {
            $respuesta = [
                'message' => 'Evento no encontrado',
                'status' => 404, // No encontrado
            ];
            return response()->json($respuesta, 404);
        }

        // Crear un nuevo recursos con los datos de la petición
        $asistente = Asistente::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'evento_id' => $request->evento_id,
        ]);
        // Si el recurso no se pudo crear, retornar un mensaje de error
        if (!$asistente) {
            $respuesta = [
                'message' => 'Error al crear el asistente',
                'status' => 500, // Error interno del servidor
            ];
            return response()->json($respuesta, 500);
        }

        // Retornar el recurso creado
        $respuesta = [
            'asistente' => $asistente,
            'status' => 201,
        ];
        return response()->json($respuesta, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Recursoe especificado
        $asistente = Asistente::find($id);

        // Si el recurso no se pudo recuperar, retornar un mensaje de error
        if (!$asistente) {
            $respuesta = [
                'message' => 'Asistente no encontrado',
                'status' => 404, // No encontrado
            ];
            return response()->json($respuesta, 404);
        }

        // Retornar el recurso recuperado
        $respuesta = [
            'asistente' => $asistente,
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
        $asistente = Asistente::find($id);

        // Si el recurso no se pudo recuperar, retornar un mensaje de error
        if (!$asistente) {
            $respuesta = [
                'message' => 'Asistente no encontrado',
                'status' => 404, // No encontrado
            ];
            return response()->json($respuesta, 404);
        }

        // Validar que la petición contenga todos los datos necesarios
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'email' => 'required',
            'telefono' => 'required',
            'evento_id' => 'required|integer',
        ]);

        // Si la petición no contiene todos los datos necesarios, retornar un mensaje de error
        if ($validator->fails()) {
            $respuesta = [
                'message' => 'Datos faltantes',
                'status' => 400, // Petición inválida
            ];
            return response()->json($respuesta, 400);
        }

        $evento = Evento::find($request->evento_id);
        // Si el evento no se pudo recuperar, retornar un mensaje de error
        if (!$evento) {
            $respuesta = [
                'message' => 'Evento no encontrado',
                'status' => 404, // No encontrado
            ];
            return response()->json($respuesta, 404);
        }

        // Actualizar el recurso con los datos de la petición
        $asistente->nombre = $request->nombre;
        $asistente->email = $request->email;
        $asistente->telefono = $request->telefono;
        $asistente->evento_id = $request->evento_id;
        $asistente->save();

        // Retornar el recurso actualizado
        $respuesta = [
            'asistente' => $asistente,
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
        $asistente = Asistente::find($id);

        // Si el recurso no se pudo recuperar, retornar un mensaje de error
        if (!$asistente) {
            $respuesta = [
                'message' => 'Asistente no encontrado',
                'status' => 404, // No encontrado
            ];
            return response()->json($respuesta, 404);
        }

        // Eliminar el recurso especificado
        $asistente->delete();

        // Retornar un mensaje de éxito
        $respuesta = [
            'message' => 'Asistente eliminado',
            'status' => 200, // OK
        ];
        return response()->json($respuesta);
    }
}

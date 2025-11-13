<?php

namespace App\Http\Controllers;

//Agrega el modelo Evento
use App\Models\Evento;
// Agrega la clase Request para manejar las solicitudes HTTP
use Illuminate\Http\Request;
// Agrega la clase Validator para validar los datos de entrada
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recupera todos los recursos
        $eventos = Evento::all();
        // Retorna los recursos recuperados en formato JSON
        $respuesta = [
            'eventos' => $eventos,
            'status' => 200,
        ];
        return response()->json($respuesta);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar que la petición contenga los campos requeridos
        $validator = Validator::make($request->all(), [
            'titulo' => 'required',
            'descripcion' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'ubicacion' => 'required',
        ]);
        // Si la petición no contiene todos los datos requeridos, retorna un error 400
        if ($validator->fails()) {
            $respuesta = [
                'message' => 'Datos faltantes o incorrectos',
                'status' => 400, // Código de estado HTTP 400
            ];
            return response()->json($respuesta, 400);
        }
        // Crea un nuevo recurso Evento con los datos de la petición
        $evento = Evento::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'ubicacion' => $request->ubicacion,
        ]);

        // Si el recurso no se pudo crear, retorna un error 500
        if (!$evento) {
            $respuesta = [
                'message' => 'Error al crear el evento',
                'status' => 500, // Error interno del servidor Código de estado HTTP 500
            ];
            return response()->json($respuesta, 500);
        }

        // Retorna el recurso creado con un código de estado 201
        $respuesta = [
            'evento' => $evento,
            'status' => 201, // Código de estado HTTP 201
        ];
        return response()->json($respuesta, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Recuperar el recurso específico por ID
        $evento = Evento::find($id);

        // Si el recurso no se pudo recuperar, retorna un error 404
        if (!$evento) {
            $respuesta = [
                'message' => 'Evento no encontrado',
                'status' => 404, // Código de estado HTTP 404
            ];
            return response()->json($respuesta, 404);
        }
        
        // Retorna el recurso recuperado con un código de estado 200
        $respuesta = [
            'evento' => $evento,
            'status' => 200, // Código de estado HTTP 200
        ];
        return response()->json($respuesta);
    }

    /**
     * actualizar el recurso especificado en almacenamiento.
     */
    public function update(Request $request, $id)
    {
        // Recuperar el recurso especificado por ID
        $evento = Evento::find($id);

        // Si el recurso no se pudo recuperar, retorna un error 400
        if (!$evento) {
            $respuesta = [
                'message' => 'Evento no encontrado',
                'status' => 404, // Código de estado HTTP 404, no encontrado
            ];
            return response()->json($respuesta, 404);
        }

        // Validar que la petición contenga todos los datos requeridos
        $validator = Validator::make($request->all(), [
            'titulo' => 'required',
            'descripcion' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'ubicacion' => 'required',
        ]);

        // Si la petición no contiene todos los datos requeridos, retorna un error 400
        if ($validator->fails()) {
            $respuesta = [
                'message' => 'Datos faltantes o incorrectos',
                'status' => 400, // Código de estado HTTP 400
            ];
            return response()->json($respuesta, 400);
        }

        // Actualizar el recurso especificado con los datos de la petición
        $evento->titulo = $request->titulo;
        $evento->descripcion = $request->descripcion;
        $evento->fecha_inicio = $request->fecha_inicio;
        $evento->fecha_fin = $request->fecha_fin;
        $evento->ubicacion = $request->ubicacion;
        $evento->save();

        // Retorna el recurso actualizado con un código de estado 200
        $respuesta = [
            'evento' => $evento,
            'status' => 200, // Código de estado HTTP 200
        ];
        return response()->json($respuesta);
    }

    /**
     * Eliminar del almacenamiento el recurso especificado
     */
    public function destroy($id)
    {
        // Recuperar el recurso especificado por ID
        $evento = Evento::find($id);

        // Si el recurso no se pudo recuperar, retorna un error 404
        if (!$evento) {
            $respuesta = [
                'message' => 'Evento no encontrado',
                'status' => 404, // Código de estado HTTP 404, no encontrado
            ];
            return response()->json($respuesta, 404);
        }

        // Eliminar el recurso especificado
        $evento->delete();

        // Retorna una respuesta de éxito con un código de estado 200
        $respuesta = [
            'message' => 'Evento eliminado correctamente',
            'status' => 200, // Código de estado HTTP 200
        ];
        return response()->json($respuesta);
    }
}
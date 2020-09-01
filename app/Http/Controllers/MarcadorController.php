<?php

namespace App\Http\Controllers;

use App\Marcador;
use Illuminate\Http\Request;

class MarcadorController extends Controller
{
    public function index(Request $request)
    {
        $marcadores = Marcador::where("descricao", 'like', '%' . $request->value . '%')
            ->get()
            ->pluck("descricao")
            ->toArray();

        return response()->json($marcadores, 200);
    }
}

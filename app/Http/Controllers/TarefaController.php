<?php

namespace App\Http\Controllers;

use App\Marcador;
use App\Tarefa;
use Exception;
use App\Http\Classes\Util;
use App\Http\Requests\IndexTarefaRequest;
use App\Http\Requests\StoreTarefaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TarefaController extends Controller
{
    public function __construct()
    {
        $this->middleware('verificar.token');
    }

    public function index(IndexTarefaRequest $request)
    {
        try {
            $status = isset($request->status) ? $request->status : "aberto";

            $tarefas = Tarefa::where("usuario_id", auth()->user()->id)
                ->where("status", $status)
                ->with([
                    "marcadores" => function($query) {
                        $query->select("marcadores.id", "marcadores.descricao");
                    }
                ])
                ->select("id", "titulo", "descricao", DB::raw('DATE_FORMAT(created_at, "%d/%m/%y %H:%i") as criado'))
                ->get();

            return response()->json($tarefas, 200);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json(["message" => "Internal Server Error"], 500);
        }
    }

    public function store(StoreTarefaRequest $request)
    {
        try {
            DB::beginTransaction();

            $tarefa = Tarefa::create([
                "usuario_id" => auth()->user()->id,
                "titulo" => $request->titulo,
                "descricao" => $request->descricao
            ]);

            if (!is_null($request->marcadores))
            {
                $marcadoresDescricao = Util::explode(",", $request->marcadores);
                $marcadores = Marcador::whereIn("descricao", $marcadoresDescricao)->get();

                foreach ($marcadoresDescricao as $marcadorDescricao)
                {
                    if ($marcadores->where("descricao", $marcadorDescricao)->count() == 0)
                    {
                        $marcador = Marcador::create(["descricao" => $marcadorDescricao]);
                        $marcadores->push($marcador);
                    }
                }

                $tarefa->marcadores()->sync($marcadores->pluck("id"));
            }

            DB::commit();

            return response()->json(["message" => "Tarefa cadastrada"], 201);

        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response()->json(["message" => "Internal Server Error"], 500);
        }
    }

    public function closeTask($id)
    {
        try {
            $tarefa = Tarefa::where("usuario_id", auth()->user()->id)
                ->where("id", $id)
                ->firstOrFail();

            if ($tarefa->status == "fechado")
            {
                return response()->json(["message" => "A tarefa já foi fechada anteriormente"], 400);
            }

            $tarefa->update(["status" => "fechado"]);

            return response()->json(["message" => "Tarefa fechada"], 200);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json(["message" => "Internal Server Error"], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Tarefa::where("usuario_id", auth()->user()->id)
                ->where("id", $id)
                ->delete();

            return response()->json(["message" => "Tarefa excluída"], 200);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json(["message" => "Internal Server Error"], 500);
        }
    }
}

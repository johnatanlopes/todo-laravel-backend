<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    protected $table = "tarefas";
    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function marcadores()
    {
        return $this->belongsToMany(Marcador::class, 'tarefa_marcadores', 'tarefa_id', 'marcador_id');
    }
}

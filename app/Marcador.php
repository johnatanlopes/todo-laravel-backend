<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marcador extends Model
{
    protected $table = "marcadores";
    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function tarefa()
    {
        return $this->belongsToMany(Tarefa::class, 'tarefa_marcadores', 'marcador_id', 'tarefa_id');
    }
}

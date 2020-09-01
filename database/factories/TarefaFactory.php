<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tarefa;
use Faker\Generator as Faker;

$factory->define(Tarefa::class, function (Faker $faker) {
    return [
        'usuario_id' => auth()->user()->id,
        'titulo' => $faker->title,
        'descricao' => $faker->text
    ];
});

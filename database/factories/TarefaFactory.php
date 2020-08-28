<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tarefa;
use Faker\Generator as Faker;

$factory->define(Tarefa::class, function (Faker $faker) {
    return [
        'titulo' => $faker->title,
        'descricao' => $faker->text
    ];
});

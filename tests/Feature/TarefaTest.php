<?php

namespace Tests\Feature;

use App\Tarefa;
use App\User;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TarefaTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $usuario = User::first();
        $token = JWTAuth::fromUser($usuario);
        $this->actingAs($usuario);

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);
    }

    public function testCamposObrigatoriosCadastroTarefa()
    {
        $response = $this->json('POST', '/api/tarefa');
        $response->assertStatus(422);
        $response->assertJson([
            "messages" => [
                "The titulo field is required."
            ]
        ]);
    }

    public function testCadastrarTarefaSemMarcador()
    {
        $tarefa = [
            "titulo" => $this->faker->realText(100),
            "descricao" => $this->faker->realText(200)
        ];

        $response = $this->json('POST', '/api/tarefa', $tarefa);
        $response->assertStatus(201);
        $response->assertJson(["message" => "Tarefa cadastrada"]);
    }

    public function testCadastrarTarefaComMarcador()
    {
        $marcadores = implode(",", $this->faker->words(3));

        $tarefa = [
            "titulo" => $this->faker->realText(100),
            "descricao" => $this->faker->realText(200),
            "marcadores" => $marcadores
        ];

        $response = $this->json('POST', '/api/tarefa', $tarefa);
        $response->assertStatus(201);
        $response->assertJson(["message" => "Tarefa cadastrada"]);
    }

    public function testListarTodasTarefas()
    {
        $response = $this->json('GET', '/api/tarefas');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'titulo',
                'descricao',
                'created_at',
                'marcadores'
            ]
        ]);
    }

    public function testListarTarefasAbertas()
    {
        $response = $this->json('GET', '/api/tarefas?status=aberto');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'titulo',
                'descricao',
                'created_at',
                'marcadores'
            ]
        ]);
    }

    public function testListarTarefasFechadas()
    {
        $response = $this->json('GET', '/api/tarefas?status=fechado');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'titulo',
                'descricao',
                'created_at',
                'marcadores'
            ]
        ]);
    }

    public function testConcluirTarefa()
    {
        $tarefa = factory(Tarefa::class)->create();

        $response = $this->json('PUT', '/api/tarefa/' . $tarefa->id);
        $response->assertStatus(200);
        $response->assertJson(["message" => "Tarefa fechada"]);

        return $tarefa->id;
    }

    /**
     * @depends testConcluirTarefa
     */
    public function testTarefaJaConcluida($id)
    {
        $response = $this->json('PUT', '/api/tarefa/' . $id);
        $response->assertStatus(400);
        $response->assertJson(["message" => "A tarefa já foi fechada anteriormente"]);
    }

    public function testExcluirTarefa()
    {
        $tarefa = factory(Tarefa::class)->create();

        $response = $this->json('DELETE', '/api/tarefa/' . $tarefa->id);
        $response->assertStatus(200);
        $response->assertJson(["message" => "Tarefa excluída"]);
    }

}

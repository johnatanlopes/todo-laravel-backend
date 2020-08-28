<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class AutenticacaoTest extends TestCase
{
    public function testLoginSenhaErrada()
    {
        $usuario = [
            "email" => "test@test.com",
            "password" => "12345"
        ];

        $response = $this->json('POST', '/api/login', $usuario, ['Accept' => 'application/json']);
        $response->assertStatus(401);
        $response->assertJson(["error" => "Unauthorized"]);
    }

    public function testLoginTokenNaoPresente()
    {
        $response = $this->json('POST', '/api/me', [], ['Accept' => 'application/json', 'Authorization' => '']);
        $response->assertStatus(401);
        $response->assertJson(["error" => "token_not_present"]);
    }

    public function testLoginTokenInvalido()
    {
        $usuario = factory(User::class)->create();
        $token = JWTAuth::fromUser($usuario);
        $token .= "invalido";

        $response = $this->json('POST', '/api/me', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer' . $token]);
        $response->assertStatus(401);
        $response->assertJson(["error" => "token_invalid"]);
    }

    public function testLoginComSucesso()
    {
        $userFactory = factory(User::class)->create();

        $usuario = [
            "email" => $userFactory->email,
            "password" => "123456"
        ];

        $response = $this->json('POST', '/api/login', $usuario, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token_type', 'access_token', 'expires_in']);
    }

    public function testMe()
    {
        $usuario = factory(User::class)->create();
        $token = JWTAuth::fromUser($usuario);

        $response = $this->json('POST', '/api/me', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
    }

    public function testTokenLogout()
    {
        $usuario = factory(User::class)->create();
        $token = JWTAuth::fromUser($usuario);

        $response = $this->json('POST', '/api/logout', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
        $response->assertJson(["message" => "Successfully logged out"]);

        return $token;
    }

    /**
     * @depends testTokenLogout
     */
    public function testTokenLogoutJaRealizado($token)
    {
        $response = $this->json('POST', '/api/me', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
    }
}

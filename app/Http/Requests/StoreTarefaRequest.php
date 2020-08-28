<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StoreTarefaRequest extends FormRequest
{
    public function rules()
    {
        return [
            "titulo" => "required|string",
            "descricao" => "nullable|string",
            "marcadores" => "nullable|string"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse(["messages" => $validator->errors()->all()], 422);
        throw new ValidationException($validator, $response);
    }
}

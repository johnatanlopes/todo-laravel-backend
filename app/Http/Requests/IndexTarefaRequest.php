<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class IndexTarefaRequest extends FormRequest
{
    public function rules()
    {
        return [
            "status" => "nullable|in:aberto,fechado"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse(["messages" => $validator->errors()->all()], 422);
        throw new ValidationException($validator, $response);
    }
}

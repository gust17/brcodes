<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodigoPromocionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Regras iniciais básicas
        $rules = [
            'codigo' => 'required|string|unique:codigo_promocionals,codigo,' . $this->route('id'),
            'pontuacao' => 'required|integer',
        ];

        // Validação para quando não for resgate único
        if (!$this->has('resgate_unico') || !$this->input('resgate_unico')) {
            $rules['limite_resgate'] = 'required|integer|min:1';
        }

        // Validação para decrescimo, caso resgate_unico seja falso
        if (!$this->input('resgate_unico') && $this->input('decrescimo')) {
            $rules['decrescimo'] = 'required|boolean';
            $rules['valor_decrescimo'] = 'required|integer|min:1';
        }

        return $rules;
    }

    /**
     * Adiciona validador condicional extra
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator)
    {
        // Verifica se o valor da pontuação permite resgate caso decrescimo seja ativado
        $validator->sometimes('pontuacao', 'min:1', function ($input) {
            return $input->decrescimo && $input->pontuacao <= 0;
        });

        // Caso seja resgate único, remover as validações desnecessárias
        if ($this->input('resgate_unico')) {
            $validator->sometimes(['limite_resgate', 'decrescimo', 'valor_decrescimo'], 'nullable', function ($input) {
                return $input->resgate_unico;
            });
        }

        // Se limite de resgate for maior que 1, resgate_unico e decrescimo não são aplicáveis
        $validator->sometimes(['resgate_unico', 'decrescimo', 'valor_decrescimo'], 'nullable', function ($input) {
            return $input->limite_resgate > 1;
        });
    }

    /**
     * Mensagens personalizadas de validação
     *
     * @return array
     */
    public function messages()
    {
        return [
            'pontuacao.min' => 'A pontuação deve ser maior que 0 quando há decrescimo.',
            'codigo.unique' => 'Este código já está em uso.',
        ];
    }
}

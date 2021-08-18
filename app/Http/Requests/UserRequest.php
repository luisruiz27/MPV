<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var \App\Models\User */
        $user = Auth::user();
        if ($this->method() == 'POST' && $user->can('CREAR USUARIO')) {
            return true;
        } elseif ((in_array($this->method(), ['PATCH', 'PUT'])) && ($this->user->id == $user->id || $user->can('EDITAR USUARIO'))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'password' => 'string|min:4',
            'name' => 'alpha_spaces|min:3',
            'email' => 'email:rfc',
            'address' => 'string|min:3',
            'phone' => 'numeric',
            'document_type_id' => 'exists:document_types,id',
            'area_id' => 'exists:areas,id',
        ];
        switch ($this->method()) {
            case 'POST': {
                $rules['username'] = 'string|min:3|unique:users,username';
                foreach ($rules as $key => $rule) {
                    $rules[$key] = implode('|', ['required', $rule]);
                }
            }
            case 'PUT':
            case 'PATCH': {
                if (($this->id == auth()->user()->id)) {
                    $rules = [
                        'old_password' => 'string|min:4'
                    ] + $rules;
                    $rules[] = [
                        'username' => '',
                    ];
                    foreach (array_slice($rules, 2) as $key => $rule) {
                        $rules[$key] = 'prohibited';
                    }
                    foreach (array_slice($rules, 0, 2) as $key => $rule) {
                        $rules[$key] = implode('|', ['required', $rule]);
                    }
                } else {
                    $rules['username'] = 'string|min:3';
                    logger($rules);
                    foreach ($rules as $key => $rule) {
                        $rules[$key] = implode('|', ['sometimes|required', $rule]);
                    }
                }
            }
        }
        return $rules;
    }

    protected function prepareForValidation()
    {
        if (isset($this->username)) {
            $this->merge([
                'username' => trim(mb_strtoupper($this->username)),
            ]);
        }
    }
}

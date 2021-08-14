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
        if ($this->id == $user->id || $user->can('ACTUALIZAR USUARIO')) {
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
        ];
        switch ($this->method()) {
            case 'POST': {
                $rules['username'] = 'alpha_num|min:3|unique:users,username';
                foreach (array_slice($rules, 0, 5) as $key => $rule) {
                    $rules[$key] = implode('|', ['required', $rule]);
                }
                return $rules;
            }
            case 'PUT':
            case 'PATCH': {
                $rules['old_password'] = 'string|min:4';
                if (($this->user->id == auth()->user()->id)) {
                    unset($rules['name']);
                    foreach (array_slice($rules, 0, 5) as $key => $rule) {
                        $rules[$key] = implode('|', ['required', $rule]);
                    }
                } else {
                    foreach (array_slice($rules, 0, 5) as $key => $rule) {
                        $rules[$key] = implode('|', ['sometimes|required', $rule]);
                    }
                }
                return $rules;
            }
        }
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'username' => mb_strtolower($this->username),
        ]);
    }
}

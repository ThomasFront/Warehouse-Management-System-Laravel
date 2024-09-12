<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $input = [];

        if ($this->has('firstName')) {
            $input['first_name'] = $this->input('firstName');
        }

        if ($this->has('lastName')) {
            $input['last_name'] = $this->input('lastName');
        }

        if ($this->has('colorTheme')) {
            $input['color_theme'] = $this->input('colorTheme');
        }

        $this->merge($input);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userIdToIgnore = $this->route('user')['id'];

        return [
            'first_name' => ['sometimes', 'string', 'min:3', 'max:50'],
            'last_name' => ['sometimes', 'string', 'min:3', 'max:50'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $userIdToIgnore],
            'color_theme' => ['sometimes', 'string', 'regex:/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/'],
            'role' => ['sometimes', 'in:admin,user'],
            'password' => ['sometimes', 'string', 'min:6']
        ];
    }
}

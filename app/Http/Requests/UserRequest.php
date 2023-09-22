<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $id = $this->segment(count($this->segments()));
        return [
            'name' => 'required|string|min:4|max:255|unique:users,name,' . $id . ',id',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'password' => 'string|min:4|max:255|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
            'password_confirmation' => 'string|min:4|max:255|same:password',
        ];
    }
}
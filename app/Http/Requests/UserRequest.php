<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'permissions' => 'nullable|array|max:255',
            'permissions.*.name' => 'nullable|string|distinct|min:4|max:255',
            'roles' => 'nullable|array|max:255',
            'roles.*.name' => 'nullable|string|distinct|min:4|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please Provide Your Name For Better Communication, Thank You.',
            'name.unique' => 'Sorry, This Name Is Already Used By Another User. Please Try With Different One, Thank You.',
            'name.min' => 'Please Provide Your Name With Minimum 4 Characters, Thank You.',
            'name.max' => 'Please Provide Your Name With Maximum 255 Characters, Thank You.',
            'email.required' => 'Please Provide Your Email Address For Better Communication, Thank You.',
            'email.email' => 'Please Provide Your Email Address In Valid Format, Thank You.',
            'email.unique' => 'Sorry, This Email Address Is Already Used By Another User. Please Try With Different One, Thank You.',
            'password.required' => 'Please Provide Your Password For Better Security, Thank You.',
            'password.string' => 'Password Should Be String, Thank You.',
            'password.min' => 'Password Length Should Be More Than 4 Character Or Digit Or Mix, Thank You.',
            'password.max' => 'Password Length Should Be Less Than 255 Character Or Digit Or Mix, Thank You.',
            'password.regex' => 'Password Should Be Mix Of Character And Digit, Thank You.',
            'password_confirmation.required' => 'Please Provide Your Confirm Password For Better Security, Thank You.',
            'password_confirmation.string' => 'Confirm Password Should Be String, Thank You.',
            'password_confirmation.min' => 'Confirm Password Length Should Be More Than 4 Character Or Digit Or Mix, Thank You.',
            'password_confirmation.max' => 'Confirm Password Length Should Be Less Than 255 Character Or Digit Or Mix, Thank You.',
            'password_confirmation.same' => 'Password And Confirm Password Should Be Same, Thank You.',
            'permissions.array' => 'Please Provide Your Permissions As Array, Thank You.',
            'permissions.max' => 'Please Provide Your Permissions With Maximum 255 Permissions, Thank You.',
            'permissions.*.name.string' => 'Please Provide Your Permissions As String, Thank You.',
            'permissions.*.name.distinct' => 'Please Provide Your Permissions Without Duplicates, Thank You.',
            'permissions.*.name.min' => 'Please Provide Your Permissions With Minimum 4 Characters, Thank You.',
            'permissions.*.name.max' => 'Please Provide Your Permissions With Maximum 255 Characters, Thank You.',
            'roles.array' => 'Please Provide Your Roles As Array, Thank You.',
            'roles.max' => 'Please Provide Your Roles With Maximum 255 Roles, Thank You.',
            'roles.*.name.string' => 'Please Provide Your Roles As String, Thank You.',
            'roles.*.name.distinct' => 'Please Provide Your Roles Without Duplicates, Thank You.',
            'roles.*.name.min' => 'Please Provide Your Roles With Minimum 4 Characters, Thank You.',
            'roles.*.name.max' => 'Please Provide Your Roles With Maximum 255 Characters, Thank You.',
        ];
    }

    protected function failedValidation(Validator $validator): array
    {
        $name = $this->input('name');
        $email = $this->input('email');
        $response = [
            'severity' => 'error',
            'summary' => 'Error',
            'detail' => 'Error in the validation of ',
            'name' => $name,
            'email' => $email,
            'errors' => $validator->errors()
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
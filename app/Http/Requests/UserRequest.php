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
            'email.unique' => 'Sorry, This Email Address Is Already Used By Another User. Please Try With Different One, Thank You.',
            'password.min' => 'Password Length Should Be More Than 4 Character Or Digit Or Mix, Thank You.',
            'password.max' => 'Password Length Should Be Less Than 255 Character Or Digit Or Mix, Thank You.',
            'password.regex' => 'Password Should Be Mix Of Character And Digit, Thank You.',
            'password_confirmation.same' => 'Password And Confirm Password Should Be Same, Thank You.',
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
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TagRequest extends FormRequest
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
            'name' => 'required|string|min:4|max:255|unique:tags,name,' . $id . ',id',
            'description' => 'nullable|string|min:4|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please Provide Your Name For Better Communication, Thank You.',
            'name.string' => 'Name Should Be String, Thank You.',
            'name.unique' => 'Sorry, This Name Is Already Used By Another Tag. Please Try With Different One, Thank You.',
            'name.min' => 'Please Provide Your Name With Minimum 4 Characters, Thank You.',
            'name.max' => 'Please Provide Your Name With Maximum 255 Characters, Thank You.',
            'description.required' => 'Please Provide Your Description For Better Communication, Thank You.',
            'description.string' => 'Description Should Be String, Thank You.',
            'description.min' => 'Please Provide Your Description With Minimum 4 Characters, Thank You.',
            'description.max' => 'Please Provide Your Description With Maximum 255 Characters, Thank You.',
        ];
    }

    protected function failedValidation(Validator $validator): array
    {
        $name = $this->input('name');
        $response = [
            'severity' => 'error',
            'summary' => 'Error',
            'detail' => 'Error in the validation of ',
            'name' => $name,
            'errors' => $validator->errors()
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionRequest extends FormRequest
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
            'name' => 'required|string|min:4|max:255|unique:permissions,name,' . $id . ',id',
            'description' => 'nullable|string|min:4|max:255',
            'tags' => 'nullable|array|min:0|max:255',
            'tags.*.id' => 'required|integer|exists:tags,id',
            'tags.*.name' => 'required|string|min:3|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Please Provide Your Name As String, Thank You.',
            'name.required' => 'Please Provide Your Name For Better Communication, Thank You.',
            'name.unique' => 'Sorry, This Name Is Already Used By Another Role. Please Try With Different One, Thank You.',
            'name.min' => 'Please Provide Your Name With Minimum 4 Characters, Thank You.',
            'name.max' => 'Please Provide Your Name With Maximum 255 Characters, Thank You.',
            'description.string' => 'Please Provide Your Description As String, Thank You.',
            'description.min' => 'Please Provide Your Description With Minimum 4 Characters, Thank You.',
            'description.max' => 'Please Provide Your Description With Maximum 255 Characters, Thank You.',
            'tags.array' => 'Please Provide Your Tags As Array, Thank You.',
            'tags.min' => 'Please provide at least one tag, Thank You.',
            'tags.max' => 'Please Provide Your Description With Maximum 255 Tags, Thank You.',
            'tags.*.id.required' => 'Each tag must have an ID',
            'tags.*.id.integer' => 'The tag ID must be an integer.',
            'tags.*.id.exists' => 'The tag ID is not valid.',
            'tags.*.name.required' => 'Each tag must have a name.',
            'tags.*.name.string' => 'The tag name must be a string.',
            'tags.*.name.min' => 'The tag name must be at least 3 characters.',
            'tags.*.name.max' => 'The tag name must not exceed 255 characters.',
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
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleRequest extends FormRequest
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
            'name' => 'required|string|min:4|max:255|unique:roles,name,' . $id . ',id',
            'description' => 'nullable|string|min:4|max:255',
            'permissions' => 'nullable|array|max:255',
            'permissions.*.name' => 'nullable|string|distinct|min:4|max:255',
            'tags' => 'nullable|array|max:255',
            'tags.*' => 'nullable|string|distinct|min:4|max:255',
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
            'permissions.array' => 'Please Provide Your Permissions As Array, Thank You.',
            'permissions.max' => 'Please Provide Your Permissions With Maximum 255 Permissions, Thank You.',
            'permissions.*.name.string' => 'Please Provide Your Permissions As String, Thank You.',
            'permissions.*.name.distinct' => 'Please Provide Your Permissions Without Duplicates, Thank You.',
            'permissions.*.name.min' => 'Please Provide Your Permissions With Minimum 4 Characters, Thank You.',
            'permissions.*.name.max' => 'Please Provide Your Permissions With Maximum 255 Characters, Thank You.',
            'tags.array' => 'Please Provide Your Tags As Array, Thank You.',
            'tags.min' => 'Please Provide Your Tags With Minimum 0 Tag, Thank You.',
            'tags.max' => 'Please Provide Your Tags With Maximum 255 Tags, Thank You.',
            'tags.*.string' => 'Please Provide Your Tags As String, Thank You.',
            'tags.*.distinct' => 'Please Provide Your Tags Without Duplicates, Thank You.',
            'tags.*.min' => 'Please Provide Your Tags With Minimum 4 Characters, Thank You.',
            'tags.*.max' => 'Please Provide Your Tags With Maximum 255 Characters, Thank You.',
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
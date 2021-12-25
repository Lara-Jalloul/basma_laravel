<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
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
        return [
            'first_name' => ['required', 'regex:/^[a-zA-Z]+$/u','max:255'],
            'last_name' => ['required', 'regex:/^[a-zA-Z]+$/u','max:255'],
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:7',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*_#?&]/', // must contain a special character
            ],
        ];
    }

    public function messages()
    {
        return [
            'required'=> ':attribute must be provided',
            "max" => ":attribute should be less than 10MB",
            "regex" => ":attribute must contain at least one lowercase, uppercase, digit and special character",
            "unique" => ":attribute already exists",
            "email" => ":attribute is unvalid",
            "min"=>":attribute should be minimum 7 characters",
        ];
    }

    protected function failedValidation (Validator $validator){
        $errors = collect($validator->errors());
        $errors = $errors->collapse();
        $response = response()->json([
            'success' => false,
            'message' => 'Error Validation',
            'errors' => $errors
        ]);  
        throw(new ValidationException($validator, $response));
    }
}

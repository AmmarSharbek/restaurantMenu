<?php

namespace App\Http\Requests\User;

use App\Enums\ErrorCode;
use App\Enums\ValidationErrorCode;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserRegisterRequest extends FormRequest
{
    use ResponseHandler;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:users,name',
            'phone' => 'required|max:255|unique:users,phone',
            'password' => 'required|max:255',
            'isAdmin' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'name.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'name.unique' => new ValidationErrorCode(ValidationErrorCode::Unique),
            'phone.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'phone.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'phone.unique' => new ValidationErrorCode(ValidationErrorCode::Unique),
            'password.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'password.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'isAdmin.required' => new ValidationErrorCode(ValidationErrorCode::Required),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response($this->error(new ErrorCode(ErrorCode::ValidationError), $validator->errors()), 422);
        throw new ValidationException($validator, $response);
    }
}

<?php

namespace App\Http\Requests\UserTenant;

use App\Enums\ErrorCode;
use App\Enums\ValidationErrorCode;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserTenantRegisterRequest extends FormRequest
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
            'idRestaurant' => 'required',
            'userNameTenant' => 'required|max:255',
            'phone' => 'required|max:255|unique:user_tenants,phone',
            'userPassTenant' => 'required|max:255',
            'isAdmin' => 'required',
            'sumPermessions' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'idRestaurant.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'userNameTenant.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'userNameTenant.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'phone.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'phone.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'phone.unique' => new ValidationErrorCode(ValidationErrorCode::Unique),
            'userPassTenant.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'userPassTenant.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'isAdmin.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'sumPermessions.required' => new ValidationErrorCode(ValidationErrorCode::Required),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response($this->error(new ErrorCode(ErrorCode::ValidationError), $validator->errors()), 422);
        throw new ValidationException($validator, $response);
    }
}

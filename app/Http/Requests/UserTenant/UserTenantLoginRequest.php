<?php

namespace App\Http\Requests\UserTenant;

use App\Enums\ErrorCode;
use App\Enums\ValidationErrorCode;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserTenantLoginRequest extends FormRequest
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
            'phone' => 'required|max:255',
            'userPassTenant' => 'required|max:255'
        ];
    }
    public function messages()
    {
        return [
            'phone.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'phone.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'userPassTenant.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'userPassTenant.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response($this->error(new ErrorCode(ErrorCode::ValidationError), $validator->errors()), 422);
        throw new ValidationException($validator, $response);
    }
}

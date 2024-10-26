<?php

namespace App\Http\Requests\Branch;

use App\Enums\ErrorCode;
use App\Enums\ValidationErrorCode;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BranchRequest extends FormRequest
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
            'restaurant_id' => 'required',
            'name_ar' => 'required|max:255',
            'name_en' => 'required|max:255',
            'description_ar',
            'description_en',
            'address_ar',
            'address_en',
            'phone',
            'mobile',
            'QR',
            'num'
        ];
    }

    public function messages()
    {
        return [
            'restaurant_id.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'name_ar.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'name_ar.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'name_en.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'name_en.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = new Response($this->error(new ErrorCode(ErrorCode::ValidationError), $validator->errors()), 422);
        throw new ValidationException($validator, $response);
    }
}

<?php

namespace App\Http\Requests\Product;

use App\Enums\ErrorCode;
use App\Enums\ValidationErrorCode;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;


class ProductRequest extends FormRequest
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
            'category_id' => 'required',
            'name_ar' => 'required|max:255',
            'name_en' => 'required|max:255',
            'description_ar' => 'required|max:255',
            'description_en' => 'required|max:255',
            'price' => 'required|double',
            'price_offer' => 'required|double',
            'image',
            'common',
            'new',
            'hidden',
            'unavailable',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'name_ar.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'name_ar.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'name_en.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'name_en.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'description_ar.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'description_ar.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'description_en.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'description_en.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'price.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'price.double' => new ValidationErrorCode(ValidationErrorCode::_Double),
            'price_offer.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'price_offer.double' => new ValidationErrorCode(ValidationErrorCode::_Double),
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = new Response($this->error(new ErrorCode(ErrorCode::ValidationError), $validator->errors()), 422);
        throw new ValidationException($validator, $response);
    }
}

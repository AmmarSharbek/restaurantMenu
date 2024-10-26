<?php

namespace App\Http\Requests\SocialMedia;

use App\Enums\ErrorCode;
use App\Enums\ValidationErrorCode;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SocialMediaRequest extends FormRequest
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
            'restaurant_id',
            'name' => 'required|max:255',
            'type' => 'required',
            'value' => 'required|unique:social_media,value',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'name.max' => new ValidationErrorCode(ValidationErrorCode::MaxLength255),
            'type.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'value.required' => new ValidationErrorCode(ValidationErrorCode::Required),
            'value.unique' => new ValidationErrorCode(ValidationErrorCode::Unique),
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = new Response($this->error(new ErrorCode(ErrorCode::ValidationError), $validator->errors()), 422);
        throw new ValidationException($validator, $response);
    }
}

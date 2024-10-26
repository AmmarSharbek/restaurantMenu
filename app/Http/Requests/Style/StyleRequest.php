<?php

namespace App\Http\Requests\Style;

use App\Enums\ErrorCode;
use App\Enums\ValidationErrorCode;
use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class StyleRequest extends FormRequest
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
            'primary' => 'nullable',
            'onPrimary' => 'nullable',
            'secondary' => 'nullable',
            'onSecondary' => 'nullable',
            'enable' => 'nullable',
            'disable' => 'nullable',
            'background' => 'nullable',
            'onBackground' => 'nullable',
        ];
    }


    public function messages()
    {
        return [
            'restaurant_id.required' => new ValidationErrorCode(ValidationErrorCode::Required),
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = new Response($this->error(new ErrorCode(ErrorCode::ValidationError), $validator->errors()), 422);
        throw new ValidationException($validator, $response);
    }
}

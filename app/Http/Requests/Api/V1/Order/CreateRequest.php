<?php

namespace App\Http\Requests\Api\V1\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class CreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'phone' => 'required|regex:/^\d(\d{3})(\d{3})(\d{4})$/',
            'host' => 'nullable',
            'city' => 'nullable',
            'cost' => 'nullable',
            'comment' => 'nullable',
            'referrer' => 'nullable',
            'url_query_string' => 'nullable',
        ];
    } //rules

    public function messages()
    {
        return [
            'name.required' => 'Поле name является обязательным',
            'name.min' => 'name должно иметь длину не менее 3 символов',

            'phone.required' => 'Поле phone является обязательным',
            'phone.regex' => 'phone должно быть указано в формате 70001112233',
        ];
    } //messages

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    } //failedValidation
}

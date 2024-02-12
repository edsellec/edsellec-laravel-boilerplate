<?php

namespace App\Http\Requests\Auth;

use App\Data\Auth\AuthData;
use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Determine if the authenticated user is authorized to make this request.
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
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'The password field is required.',
        ];
    }

    /**
     * Transform request to data object.
     *
     * @return AuthData
     */
    public function toData(): AuthData
    {
        return AuthData::from([
            'email' => $this->getInputAsString('email'),
            'password' => $this->getInputAsString('password'),
        ]);
    }
}

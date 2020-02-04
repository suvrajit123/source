<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'first_name' => 'required|string|min:1',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ];
    }


    public function messages()
    {
        return [
                'first_name.required' => 'First name required!',
                'last_name.required' => 'Last name required!',
                'email.required' => 'Email required!',
                'email.unique' => 'This email id is already used by another user!',
                'username.required' => 'Username required!',
                'username.unique' => 'This username id is already used by another user!',
                'password.required' => 'Password is required!'
            ];
    }
}

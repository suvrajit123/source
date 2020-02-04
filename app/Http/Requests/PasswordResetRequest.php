<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
            'current_password'          => 'required',
            'new_password'              => 'required|string|min:8|different:current_password',
        ];
    }


    public function messages()
    {
        return [
                'current_password.required' => 'Old Password is required!',
                'new_password.required' => 'New Password is required!',
                'new_password.different' => 'Old password and new password must be different!',
            ];
    }
}

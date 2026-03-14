<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $nikRules = ['required', 'string', 'max:32'];
        if (Schema::hasColumn('users', 'nik')) {
            $nikRules[] = Rule::unique(User::class)->ignore($this->user()->id);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'nik' => $nikRules,
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'password' => ['required', Password::defaults()],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:classrooms,name,' . $this->classroom->id, 'min:5', 'max:6', 'regex:/(TI-|SIB-).*[1-4]{1}[A-Z]{1}/'],
            'semester' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Kelas wajib diisi',
            'name.string' => 'Masukkan Nama Kelas dalam format teks',
            'name.unique' => 'Kelas dengan nama :input sudah terdaftar',
            'name.min' => 'Nama Kelas harus 5-6 karakter',
            'name.max' => 'Nama Kelas harus 5-6 karakter',
            'name.regex' => 'Nama Kelas harus sesuai format TI/SIB-TingkatKelas (misal: TI-1A atau SIB-2C)',

            'semester.required' => 'Semester wajib diisi',
            'semester.numeric' => 'Masukkan Nama Kelas dalam format angka',
            'semester.digits' => 'Semester harus 1 karakter angka',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                $validator->errors()->add('update-invalid-fields', 'some fields are invalid');
                $validator->errors()->add('classroom-id', $this->classroom->id);
            }
        });
    }
}

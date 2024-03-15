<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:classrooms', 'min:5', 'max:6', 'regex:/(TI-|SIB-).*[1-4]{1}[A-Z]{1}/'],
            'user_id' => 'required|numeric',
            'semester' => 'required|numeric|digits:1',
            'file-students' => 'file|mimes:xls,xlsx,csv'
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

            'file-students.file' => 'Daftar Mahasiswa harus dalam format File Excel',
            'file-students.mimes' => 'Format file Daftar Mahasiswa harus berkestensi .xls, .xlsx, atau .csv'
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
                $validator->errors()->add('insert-invalid-fields', 'some fields are invalid');
            }
        });
    }
}

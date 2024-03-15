<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentImportRequest extends FormRequest
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
            'classrooms_id'=>'required|numeric|exists:classrooms,id',
            'user_id' => 'required|numeric',
            'file-students' => 'required|file|mimes:xls,xlsx,csv'
        ];
    }

    public function messages()
    {
        return [
            'classrooms_id.required' => 'Kelas wajib diisi',
            'classrooms_id.numeric' => 'Kelas harus 1 karakter angka',
            'classrooms_id.exists' => 'Masukkan Kelas yang telah terdaftar',

            'file-students.required' => 'File wajib diisi',
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
                $validator->errors()->add('import-invalid-fields', 'some fields are invalid');
            }
        });
    }
}


<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'student_id_number'=>'required|numeric|digits:10|unique:students',
            'name'=>['required', 'string', 'regex:/^[A-Za-z ]+$/'],
            'classrooms_id'=>'required|numeric|exists:classrooms,id'
        ];
    }

    public function messages()
    {
        return [
            'student_id_number.required' => 'NIM wajib diisi',
            'student_id_number.numeric' => 'Masukkan NIM dalam bentuk angka',
            'student_id_number.digits' => 'NIM harus 10 karakter angka',
            'student_id_number.unique' => 'NIM telah terdaftar',

            'name.required' => 'Nama Mahasiswa wajib diisi',
            'name.string' => 'Masukkan Nama Mahasiswa dalam format teks',
            'name.regex' => 'Nama tidak boleh berisi karakter dan angka',

            'classrooms_id.required' => 'Kelas wajib diisi',
            'classrooms_id.numeric' => 'Kelas harus 1 karakter angka',
            'classrooms_id.exists' => 'Masukkan Kelas yang telah terdaftar',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                $validator->errors()->add('insert-invalid-fields', 'some fields are invalid');
            }
        });
    }
}

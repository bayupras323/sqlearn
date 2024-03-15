<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreScheduleRequest extends FormRequest
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
            'name' => 'required|string',
            'type' => 'required|string',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date',
            'package_id' => 'required|numeric'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => 'Nama Jadwal wajib diisi',
            'type.required' => 'Tipe jadwal wajib dipilih',
            'start_date.required' => 'Waktu mulai wajib diisi',
            'end_date.required' => 'Waktu berakhir wajib diisi',
            'end_date.after' => 'Waktu berakhir harus setelah waktu mulai',
            'package_id.required' => 'Paket soal wajib dipilih',
        ];
    }

    /**
     * Configure the validator instance.
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

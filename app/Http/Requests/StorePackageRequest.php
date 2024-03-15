<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
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
            'name' => ['required', 'string', ],
            'topic_id' => 'required|string',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Nama Paket Soal wajib diisi',
            'name.string' => 'Masukan Nama Paket Soal dalam format teks',
            'name.unique' => 'Paket soal dengan nama telah terdaftar',

            'topic_id.required' => 'Topik Wajib Di isi',

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator){
            if ($validator->errors()->isNotEmpty()){
                $validator->errors()->add('insert-invalid-fields', 'some fields are invalid');
            }
        });
    }
}

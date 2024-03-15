<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
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
    
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'topic_id' => 'required|string',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Nama Paket Soal wajib diisi',
            'name.string' => 'Masukan Nama Paket Soal dalam format teks',

            'topic_id.required' => 'Topik Wajib Di isi',

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator){
            if ($validator->errors()->isNotEmpty()){
                $validator->errors()->add('update-invalid-fields', 'some fields are invalid');
                $validator->errors()->add('package-id', $this->package->id);
            }
        });
    }
}

<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    private $classroom_id;

    public function __construct($classroom_id)
    {
        $this->classroom_id = $classroom_id;
    }

    /**
     * @param Collection $rows
     *
     * @return Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $user = User::create([
                'name'      => $row['nama'],
                'email'     => $row['nim'] . '@student.polinema.ac.id',
                'password'  => Hash::make($row['nim']),
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i'),
            ]);

            Student::create([
                'user_id' => $user->id,
                'classrooms_id' => $this->classroom_id,
                'student_id_number' => $row['nim']
            ]);
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nim' => 'required|numeric|digits:10|unique:students,student_id_number',
            '*.nama' => 'required|string'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nim.required' => ':attribute wajib diisi. Pastikan file excel sesuai template yang disediakan',
            'nim.numeric' => ':attribute harus berupa angka',
            'nim.digits' => ':attribute harus berupa terdiri dari 10 digit NIM',
            'nim.unique' => 'Mahasiswa degan NIM :input sudah terdaftar',
            'nama.required' => ':attribute wajib diisi. Pastikan file excel sesuai template yang disediakan',
            'nama.required' => ':attribute harus berupa teks',
        ];
    }
}

<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Alumni;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AlumniImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $user = User::create([
            'foto' => 'default.png',
            'username' => $row['nim'],
            'password' => bcrypt($row['nim']),
            'role' => 'alumni'
        ]);

        Alumni::create([
            'user_id' => $user->id,
            'nim'  => $row['nim'],
            'nama' => $row['nama'],
            'tgl_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_lahir']),
            'prodi' => $row['prodi'],
            'sk_kelulusan' => $row['sk_kelulusan'],
            'tgl_kelulusan' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_kelulusan']),
        ]);
    }

    public function rules(): array
    {
        return [
            'nim' => Rule::unique('alumni', 'nim'),
            'sk_kelulusan' => Rule::unique('alumni', 'sk_kelulusan')
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nim.unique' => 'NIM Mahasiswa sudah ada',
            'sk_kelulusan.unique' => 'SK Kelulusan sudah ada'
        ];
    }
}

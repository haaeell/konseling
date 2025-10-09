<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class SiswaTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'nisn',
            'nis',
            'kelas_id',
            'jenis_kelamin',
            'nama_orangtua',
            'hubungan_dengan_siswa',
            'no_telp_orangtua',
            'alamat'
        ];
    }
}

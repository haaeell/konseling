<?php

namespace App\Exports;

use App\Models\Kelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua nama kelas
        $kelasList = Kelas::pluck('nama')->toArray();

        // Jika belum ada data kelas, kasih contoh default
        if (empty($kelasList)) {
            $kelasList = ['X MIPA 1', 'XI IPS 2', 'XII MIPA 3'];
        }

        return new Collection([
            [
                'name' => 'Ahmad Fajar',
                'email' => 'ahmad.fajar@example.com',
                'nisn' => '0078912345',
                'nis' => '2300456',
                'nama_kelas' => $kelasList[0] ?? 'X MIPA 1',
                'jenis_kelamin' => 'L',
                'nama_orangtua' => 'Budi Santoso',
                'hubungan_dengan_siswa' => 'Ayah',
                'no_telp_orangtua' => '081234567890',
                'alamat' => 'Jl. Merpati No. 45, Cirebon',
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => 'siti.rahmawati@example.com',
                'nisn' => '0078912346',
                'nis' => '2300457',
                'nama_kelas' => $kelasList[1] ?? 'XI IPS 2',
                'jenis_kelamin' => 'P',
                'nama_orangtua' => 'Nurhayati',
                'hubungan_dengan_siswa' => 'Ibu',
                'no_telp_orangtua' => '082145678912',
                'alamat' => 'Jl. Kenanga No. 22, Majalengka',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'nisn',
            'nis',
            'nama_kelas',
            'jenis_kelamin',
            'nama_orangtua',
            'hubungan_dengan_siswa',
            'no_telp_orangtua',
            'alamat',
        ];
    }
}

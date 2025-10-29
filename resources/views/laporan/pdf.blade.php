<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Konseling</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Laporan Konseling Bulan {{ $month ? DateTime::createFromFormat('!m', $month)->format('F') : 'Semua' }}
        {{ $year ?? '' }}</h2>
    <p><strong>Total Konseling Selesai:</strong> {{ $totalKonseling }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kategori</th>
                <th>Tanggal Pengajuan</th>
                <th>Deskripsi</th>
                <th>Tempat</th>
                <th>Rangkuman</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->siswa->user->name }}</td>
                    <td>{{ $item->kategori->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}</td>
                    <td>{{ Str::limit($item->deskripsi_permasalahan, 50) }}</td>
                    <td>{{ $item->tempat ?? '-' }}</td>
                    <td>{{ $item->rangkuman ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data konseling.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

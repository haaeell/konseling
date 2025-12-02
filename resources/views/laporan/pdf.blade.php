<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Konseling Siswa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h3>Laporan Konseling Siswa</h3>

    <table>
        <tbody>
            <tr>
                <td><strong>Tahun Akademik</strong></td>
                <td>
                    @php
                        $tahun = $request->tahun_akademik
                            ? $tahunAjaranList->firstWhere('id', $request->tahun_akademik)
                            : $tahunAjaranList->first();
                    @endphp

                    {{ $tahun->tahun ?? '-' }}
                </td>
            </tr>


            <tr>
                <td><strong>Kelas</strong></td>
                <td>
                    @if ($request->kelas)
                        {{ $laporan->first()->siswa->kelas->nama ?? '-' }}
                    @else
                        Semua Kelas
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Kategori</th>
                <th>Masalah</th>
                <th>Penyelesaian</th>
                <th>Konselor</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($laporan as $i => $row)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('d-m-Y') }}</td>
                    <td>{{ $row->siswa->user->name }}</td>
                    <td>{{ $row->siswa->kelas->nama }}</td>
                    <td>{{ $row->kategori_masalah_label }}</td>
                    <td>{{ $row->deskripsi_permasalahan }}</td>
                    <td>{{ $row->rangkuman ?? '-' }}</td>
                    <td>{{ $row->nama_konselor }}</td>
                    <td class="center">{{ ucfirst($row->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

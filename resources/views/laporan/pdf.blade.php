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
            margin-bottom: 10px;
        }

        h4 {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>Laporan Konseling
        {{ $month ? DateTime::createFromFormat('!m', $month)->format('F') : 'Semua Bulan' }}
        {{ $year ?? '' }}
    </h2>

    <p><strong>Total Konseling Selesai:</strong> {{ $total }}</p>

    {{-- REKAP 4 FAKTOR --}}
    <h4>Rekap Faktor Penilaian</h4>

    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap['kategori'] as $label => $jumlah)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $jumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>Urgensi</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap['urgensi'] as $label => $jumlah)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $jumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>Dampak</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap['dampak'] as $label => $jumlah)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $jumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th>Riwayat Konseling</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap['riwayat'] as $label => $jumlah)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="center">{{ $jumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- DETAIL --}}
    <h4>Detail Konseling</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Urgensi</th>
                <th>Dampak</th>
                <th>Riwayat</th>
                <th>Skor Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $i => $l)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $l->siswa->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($l->tanggal_pengajuan)->format('d-m-Y') }}</td>
                    <td>{{ $l->kategori_masalah_label }}</td>
                    <td>{{ $l->tingkat_urgensi_label }}</td>
                    <td>{{ $l->dampak_masalah_label }}</td>
                    <td>{{ $l->riwayat_konseling_label }}</td>
                    <td class="center">{{ $l->skor_prioritas }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

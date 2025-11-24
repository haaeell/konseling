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

        h2,
        h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 15px;
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
            vertical-align: top;
        }

        th {
            background: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .small {
            font-size: 11px;
        }

        .mt-2 {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <h2>LAPORAN KONSELING</h2>
    <div class="subtitle">
        Periode:
        <strong>
            {{ $month ? DateTime::createFromFormat('!m', $month)->format('F') : 'Semua Bulan' }}
            {{ $year ?? date('Y') }}
        </strong>
    </div>

    <p><strong>Total Konseling Selesai:</strong> {{ $total }}</p>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Nama Siswa</th>

                <th>Urgensi</th>
                <th>Dampak</th>
                <th>Kategori</th>
                <th>Riwayat</th>

                <th>Skor Total</th>

                <th>Deskripsi Permasalahan</th>
                <th width="10%">Tanggal</th>
                <th width="10%">Tempat</th>
                <th width="15%">Rangkuman</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($laporan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>

                    <td>{{ $item->siswa->user->name }}</td>

                    {{-- FAKTOR URGENSI --}}
                    <td>
                        <div><strong>{{ $item->tingkat_urgensi_label }}</strong></div>
                        <div class="small">Skor: {{ $item->tingkat_urgensi_skor }}</div>
                    </td>

                    {{-- FAKTOR DAMPAK --}}
                    <td>
                        <div><strong>{{ $item->dampak_masalah_label }}</strong></div>
                        <div class="small">Skor: {{ $item->dampak_masalah_skor }}</div>
                    </td>

                    {{-- FAKTOR KATEGORI --}}
                    <td>
                        <div><strong>{{ $item->kategori_masalah_label }}</strong></div>
                        <div class="small">Skor: {{ $item->kategori_masalah_skor }}</div>
                    </td>

                    {{-- RIWAYAT --}}
                    <td>
                        <div><strong>{{ $item->riwayat_konseling_label }}</strong></div>
                        <div class="small">Skor: {{ $item->riwayat_konseling_skor }}</div>
                    </td>

                    {{-- TOTAL PRIORITAS --}}
                    <td class="text-center fw-bold">
                        {{ $item->skor_prioritas }}
                    </td>

                    {{-- DESKRIPSI --}}
                    <td>{{ Str::limit($item->deskripsi_permasalahan, 80) }}</td>

                    {{-- TANGGAL --}}
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}
                    </td>

                    {{-- TEMPAT --}}
                    <td>{{ $item->tempat ?? '-' }}</td>

                    {{-- RANGKUMAN --}}
                    <td>{{ $item->rangkuman ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data konseling.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>

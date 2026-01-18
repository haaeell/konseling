<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%); color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 30px 20px; }
        .greeting { font-size: 16px; margin-bottom: 20px; }
        .section h2 { font-size: 18px; color: #007BFF; border-bottom: 2px solid #007BFF; padding-bottom: 10px; margin: 20px 0 15px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table tr { border-bottom: 1px solid #E9ECEF; }
        table td { padding: 12px; text-align: left; }
        table td:first-child { font-weight: 600; color: #007BFF; width: 35%; background-color: #F8F9FA; }
        .button-group { text-align: center; margin: 30px 0; }
        .button { display: inline-block; background-color: #007BFF; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; font-weight: 600; }
        .button:hover { background-color: #0056b3; }
        .footer { background-color: #F8F9FA; padding: 20px; text-align: center; color: #6C757D; font-size: 12px; border-top: 1px solid #E9ECEF; }
        .footer p { margin: 5px 0; }
        .alert { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .text-muted { color: #6C757D; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Permohonan Konseling Baru</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Halo <strong>{{ $notifiable->name }}</strong>,</p>
            
            <p>Ada <strong>permohonan konseling baru</strong> yang menunggu persetujuan Anda.</p>
            
            <div class="section">
                <h2>Detail Permohonan</h2>
                <table>
                    <tr>
                        <td>Nama Siswa</td>
                        <td>{{ $permohonan->siswa->user->name }}</td>
                    </tr>
                    <tr>
                        <td>NISN</td>
                        <td>{{ $permohonan->siswa->nisn }}</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>{{ $permohonan->siswa->kelas->nama }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pengajuan</td>
                        <td>{{ $permohonan->tanggal_pengajuan?->format('d-m-Y H:i') ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="section">
                <h2>Deskripsi Masalah</h2>
                <p>{{ $permohonan->deskripsi_permasalahan }}</p>
            </div>
            
            <div class="section">
                <h2>Tipe Laporan</h2>
                <p>
                    <strong>{{ ucfirst($permohonan->report_type) }}</strong>
                    @if($permohonan->report_type === 'self')
                        (Permohonan dari siswa sendiri)
                    @else
                        (Laporan dari guru)
                    @endif
                </p>
            </div>
            
            <div class="alert">
                <strong>⚠️ Tindakan Diperlukan:</strong><br>
                Mohon review permohonan ini dan ambil keputusan (setujui/tolak) sesegera mungkin.
            </div>
            
            <div class="button-group">
                <a href="{{ route('permohonan-konseling.index') }}" class="button">Lihat Permohonan Menunggu</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Sistem Konseling Siswa</p>
            <p class="text-muted">Email ini dikirim otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>

@php
$primaryColor = '#007BFF';
$successColor = '#28A745';
$dangerColor = '#DC3545';
$secondaryColor = '#6C757D';
$lightBg = '#F8F9FA';
$borderColor = '#E9ECEF';
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, #0056b3 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }
        .section {
            margin: 25px 0;
        }
        .section h2 {
            font-size: 18px;
            color: {{ $primaryColor }};
            border-bottom: 2px solid {{ $primaryColor }};
            padding-bottom: 10px;
            margin: 20px 0 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table tr {
            border-bottom: 1px solid {{ $borderColor }};
        }
        table td {
            padding: 12px;
            text-align: left;
        }
        table td:first-child {
            font-weight: 600;
            color: {{ $primaryColor }};
            width: 35%;
            background-color: {{ $lightBg }};
        }
        .alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .alert.success {
            background-color: #d4edda;
            border-left-color: {{ $successColor }};
        }
        .alert.danger {
            background-color: #f8d7da;
            border-left-color: {{ $dangerColor }};
        }
        .button-group {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background-color: {{ $primaryColor }};
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 0 10px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            background-color: {{ $lightBg }};
            padding: 20px;
            text-align: center;
            color: {{ $secondaryColor }};
            font-size: 12px;
            border-top: 1px solid {{ $borderColor }};
        }
        .footer p {
            margin: 5px 0;
        }
        .text-muted {
            color: {{ $secondaryColor }};
            font-size: 13px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 0 5px;
        }
        .badge.primary {
            background-color: {{ $primaryColor }};
            color: white;
        }
        .badge.success {
            background-color: {{ $successColor }};
            color: white;
        }
        .badge.danger {
            background-color: {{ $dangerColor }};
            color: white;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            .content {
                padding: 20px 15px;
            }
            table td {
                padding: 8px;
                font-size: 14px;
            }
            .button {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{ $slot }}
        </div>
        <div class="content">
            {{ $slot }}
        </div>
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Sistem Konseling Siswa</p>
            <p class="text-muted">Email ini dikirim otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>

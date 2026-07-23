<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Siswa - {{ $kelas->nama_kelas }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 10px;
            color: #64748b;
        }
        .title-box {
            text-align: center;
            margin-bottom: 15px;
            padding: 8px;
            background-color: #f1f5f9;
            border-radius: 6px;
        }
        .title-box h3 {
            margin: 0;
            font-size: 13px;
            color: #0f172a;
        }
        .title-box p {
            margin: 2px 0 0 0;
            font-size: 10px;
            color: #475569;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
        td.center {
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .stat-badge {
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .footer table {
            border: none;
        }
        .footer td {
            border: none;
            text-align: center;
        }
    </style>
</head>
<body>
    {{-- KOP SURAT --}}
    @include('partials.kop-surat')

    <div class="title-box">
        <h3>REKAP PRESENSI SISWA - KELAS {{ strtoupper($kelas->nama_kelas) }}</h3>
        <p>Periode: {{ $periodeLabel }} | Wali Kelas: {{ $kelas->guru->nama_guru ?? '-' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">NIS</th>
                <th>Nama Siswa</th>
                <th style="width: 50px;">Hadir</th>
                <th style="width: 50px;">Sakit</th>
                <th style="width: 50px;">Izin</th>
                <th style="width: 50px;">Alfa</th>
                @if($tipe === 'semester')
                <th style="width: 70px;">% Kehadiran</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($rekap as $index => $row)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td class="center">{{ $row['nis'] }}</td>
                <td>{{ $row['nama_siswa'] }}</td>
                <td class="center" style="color: #16a34a; font-weight: bold;">{{ $row['hadir'] }}</td>
                <td class="center" style="color: #d97706;">{{ $row['sakit'] }}</td>
                <td class="center" style="color: #2563eb;">{{ $row['izin'] }}</td>
                <td class="center" style="color: #dc2626; font-weight: bold;">{{ $row['alfa'] }}</td>
                @if($tipe === 'semester')
                <td class="center" style="font-weight: bold; color: {{ $row['persen_hadir'] >= 85 ? '#16a34a' : ($row['persen_hadir'] >= 75 ? '#d97706' : '#dc2626') }}">
                    {{ $row['persen_hadir'] }}%
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ $tipe === 'semester' ? 8 : 7 }}" class="center" style="color: #94a3b8; padding: 15px;">
                    Belum ada data presensi pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%;">
                    <p>{{ $sekolah->kota ?? 'Bantul' }}, {{ date('d F Y') }}</p>
                    <p style="margin-top: 5px;">Wali Kelas {{ $kelas->nama_kelas }}</p>
                    <br><br><br>
                    <p style="font-weight: bold; text-decoration: underline;">{{ $kelas->guru->nama_guru ?? 'Wali Kelas' }}</p>
                    <p>NBM/NIP: {{ $kelas->guru->no_id ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

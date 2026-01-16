<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Karyawan - Nasi Jinggo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
        }
        
        .header h1 {
            color: #4F46E5;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 11px;
        }
        
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stat-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            background: #EEF2FF;
            border: 1px solid #C7D2FE;
        }
        
        .stat-item .number {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
        }
        
        .stat-item .label {
            font-size: 10px;
            color: #6B7280;
            margin-top: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table thead {
            background: #4F46E5;
            color: white;
        }
        
        table th {
            padding: 12px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
        }
        
        table td {
            padding: 10px 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 11px;
        }
        
        table tbody tr:nth-child(even) {
            background: #F9FAFB;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        
        .badge-green {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .badge-orange {
            background: #FED7AA;
            color: #9A3412;
        }
        
        .badge-blue {
            background: #DBEAFE;
            color: #1E40AF;
        }
        
        .badge-red {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>NASI JINGGO MANAGEMENT</h1>
        <p>Laporan Data Karyawan</p>
        <p style="margin-top: 5px;">Tanggal Cetak: {{ date('d F Y, H:i') }} WITA</p>
    </div>

    <!-- Statistics -->
    <div class="stats">
        <div class="stat-item">
            <div class="number">{{ $totalEmployees }}</div>
            <div class="label">Total Karyawan</div>
        </div>
        <div class="stat-item">
            <div class="number">{{ $activeEmployees }}</div>
            <div class="label">Karyawan Aktif</div>
        </div>
        <div class="stat-item">
            <div class="number">{{ $staffDapur }}</div>
            <div class="label">Staff Dapur</div>
        </div>
        <div class="stat-item">
            <div class="number">{{ $kasir }}</div>
            <div class="label">Kasir</div>
        </div>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama</th>
                <th width="28%">Email</th>
                <th width="12%">Telepon</th>
                <th width="15%">Posisi</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>
                    @if($user->role === 'staff_dapur')
                        <span class="badge badge-green">Staff Dapur</span>
                    @elseif($user->role === 'kasir')
                        <span class="badge badge-orange">Kasir</span>
                    @endif
                </td>
                <td>
                    @if($user->is_active)
                        <span class="badge badge-blue">Aktif</span>
                    @else
                        <span class="badge badge-red">Nonaktif</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px; color: #9CA3AF;">
                    Tidak ada data karyawan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Nasi Jinggo Management</p>
        <p style="margin-top: 5px;">© {{ date('Y') }} Nasi Jinggo. All rights reserved.</p>
    </div>
</body>
</html>
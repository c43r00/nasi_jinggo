<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
            color: #666;
        }
        .stats {
            width: 100%;
            margin-bottom: 25px;
        }
        .stats table {
            width: 100%;
            border-collapse: collapse;
        }
        .stats td {
            width: 50%;
            padding: 15px;
            text-align: center;
            border: 2px solid #333;
        }
        .stats .danger {
            background-color: #ffebee;
            border-color: #f44336;
        }
        .stats .success {
            background-color: #e8f5e9;
            border-color: #4caf50;
        }
        .stats h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .stats h2 {
            margin: 0;
            font-size: 36px;
            font-weight: bold;
        }
        .section-title {
            background-color: #f5f5f5;
            padding: 10px;
            margin-top: 25px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
            border-left: 5px solid #333;
        }
        .section-danger {
            background-color: #ffebee;
            border-left-color: #f44336;
            color: #c62828;
        }
        .section-success {
            background-color: #e8f5e9;
            border-left-color: #4caf50;
            color: #2e7d32;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th {
            background-color: #4CAF50;
            color: white;
            padding: 10px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #388e3c;
        }
        table.data td {
            padding: 8px 5px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        table.data tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            text-align: right;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .footer p {
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p><strong>Dapur - Nasi Jinggo Apel</strong></p>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <div class="stats">
        <table>
            <tr>
                <td class="danger">
                    <h3>Stok Menipis</h3>
                    <h2>{{ $stockMenipis }}</h2>
                </td>
                <td class="success">
                    <h3>Stok Normal</h3>
                    <h2>{{ $normal }}</h2>
                </td>
            </tr>
        </table>
    </div>

    @if(isset($groupedIngredients['stok_menipis']) && $groupedIngredients['stok_menipis']->count() > 0)
    <div class="section-title section-danger">
        BAHAN DENGAN STOK MENIPIS ({{ $groupedIngredients['stok_menipis']->count() }} Item)
    </div>
    
    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Bahan</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 12%;">Stok Saat Ini</th>
                <th style="width: 12%;">Min. Stok</th>
                <th style="width: 15%;">Harga/Unit</th>
                <th style="width: 16%;">Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedIngredients['stok_menipis'] as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $item['ingredient']->name }}</strong></td>
                <td>{{ $item['ingredient']->category->name ?? '-' }}</td>
                <td style="color: #c62828; font-weight: bold;">
                    {{ $item['ingredient']->stock_quantity }} {{ $item['ingredient']->unit }}
                </td>
                <td>{{ $item['ingredient']->minimum_stock }} {{ $item['ingredient']->unit }}</td>
                <td>Rp {{ number_format($item['ingredient']->price_per_unit, 0, ',', '.') }}</td>
                <td>{{ $item['ingredient']->supplier_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(isset($groupedIngredients['normal']) && $groupedIngredients['normal']->count() > 0)
    <div class="section-title section-success">
        BAHAN DENGAN STOK NORMAL ({{ $groupedIngredients['normal']->count() }} Item)
    </div>
    
    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Bahan</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 12%;">Stok Saat Ini</th>
                <th style="width: 12%;">Min. Stok</th>
                <th style="width: 15%;">Harga/Unit</th>
                <th style="width: 16%;">Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedIngredients['normal'] as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item['ingredient']->name }}</td>
                <td>{{ $item['ingredient']->category->name ?? '-' }}</td>
                <td style="color: #2e7d32; font-weight: bold;">
                    {{ $item['ingredient']->stock_quantity }} {{ $item['ingredient']->unit }}
                </td>
                <td>{{ $item['ingredient']->minimum_stock }} {{ $item['ingredient']->unit }}</td>
                <td>Rp {{ number_format($item['ingredient']->price_per_unit, 0, ',', '.') }}</td>
                <td>{{ $item['ingredient']->supplier_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p><strong>Dicetak pada:</strong> {{ now()->format('d F Y, H:i:s') }} WIB</p>
        <p>Sistem Manajemen Dapur - Nasi Jinggo Apel</p>
    </div>
</body>
</html>
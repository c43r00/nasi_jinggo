<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran | Nasi Jinggo Apel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', sans-serif;
        }
        .receipt-card {
            border: none;
            border-radius: 24px;
            width: 100%;
            max-width: 550px; /* Ukuran dibuat lebih besar */
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .receipt-header {
            background: #198754;
            padding: 40px 20px;
            color: white;
            text-align: center;
            position: relative;
        }
        .receipt-header::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 20px;
            background: radial-gradient(circle, transparent, transparent 50%, #fff 50%, #fff);
            background-size: 20px 20px;
        }
        .icon-box {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
        }
        .detail-table th {
            color: #6c757d;
            font-weight: 500;
            padding: 15px 0;
        }
        .detail-table td {
            text-align: right;
            font-weight: 600;
            padding: 15px 0;
        }
        .total-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }
        .dashed-line {
            border-top: 2px dashed #dee2e6;
            margin: 20px 0;
        }
        .btn-new {
            border-radius: 12px;
            padding: 12px;
            font-weight: bold;
            transition: 0.3s;
        }
    </style>
</head>
<body>

<div class="card receipt-card shadow-lg">
    <div class="receipt-header">
        <div class="icon-box">
            <i class="bi bi-check2-circle"></i>
        </div>
        <h3 class="mb-0 fw-bold">PEMBAYARAN BERHASIL</h3>
        <p class="opacity-75 mb-0">Nasi Jinggo Apel - Cabang Utama</p>
    </div>

    <div class="card-body p-5 bg-white">
        <div class="text-center mb-4">
            <h6 class="text-muted text-uppercase mb-1" style="letter-spacing: 2px;">Total Tagihan</h6>
            <h1 class="display-5 fw-bold text-success">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</h1>
        </div>

        <div class="dashed-line"></div>

        <table class="table table-borderless detail-table mb-0">
            <tr>
                <th><i class="bi bi-person me-2"></i>Nama Pelanggan</th>
                <td>{{ $transaksi->nama_pembeli }}</td>
            </tr>
            <tr>
                <th><i class="bi bi-box-seam me-2"></i>Menu Pesanan</th>
                <td>{{ $transaksi->varian }}</td>
            </tr>
            <tr>
                <th><i class="bi bi-tag me-2"></i>Harga Satuan</th>
                <td>Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th><i class="bi bi-hash me-2"></i>Jumlah Porsi</th>
                <td>{{ $transaksi->jumlah }}x</td>
            </tr>
            <tr>
                <th><i class="bi bi-credit-card me-2"></i>Metode Bayar</th>
                <td><span class="badge bg-light text-dark border p-2">{{ $transaksi->metode_pembayaran }}</span></td>
            </tr>
        </table>

        <div class="total-section d-flex justify-content-between align-items-center">
            <span class="text-muted">Waktu Transaksi</span>
            <span class="small fw-bold">{{ date('d M Y, H:i') }} WIB</span>
        </div>

        <div class="row g-2 mt-4">
            <div class="col-6">
                <button onclick="window.print()" class="btn btn-light border w-100 btn-new">
                    <i class="bi bi-printer me-2"></i>Cetak Struk
                </button>
            </div>
            <div class="col-6">
                <a href="{{ url('/kasir/transaksi') }}" class="btn btn-success w-100 btn-new">
                    <i class="bi bi-plus-lg me-2"></i>Baru
                </a>
            </div>
        </div>
    </div>

    <div class="card-footer bg-white border-0 text-center pb-4">
        <p class="text-muted small">Terima kasih atas kunjungan Anda!<br>Simpan struk ini sebagai bukti pembayaran sah.</p>
        <div class="d-flex justify-content-center gap-3 mt-2">
            <i class="bi bi-instagram text-muted"></i>
            <i class="bi bi-facebook text-muted"></i>
            <i class="bi bi-whatsapp text-muted"></i>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
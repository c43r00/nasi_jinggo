<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir | Nasi Jinggo Apel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { background: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,.1); }
        
        /* Menu Styling */
        .menu-card { 
            border: none; 
            border-radius: 15px; 
            transition: all 0.3s ease; 
            background: white;
            text-align: center;
        }
        .menu-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
            border: 1px solid #198754;
        }
        .menu-emoji { font-size: 2.5rem; margin-bottom: 10px; }
        .price-badge { 
            background: #e8f5e9; 
            color: #1b5e20; 
            font-weight: bold; 
            padding: 5px 10px; 
            border-radius: 20px; 
            display: inline-block;
        }

        /* Form Styling */
        .order-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 -5px 25px rgba(0,0,0,0.05);
            margin-top: 40px;
            border-top: 5px solid #198754;
        }
        .form-label { font-weight: 600; color: #444; }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #dee2e6;
        }
        .btn-order {
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-shop-window me-2"></i> NASI JINGGO APEL
        </a>
        <div class="ms-auto text-white">
            <i class="bi bi-person-circle me-1"></i> Kasir: <strong>Dita Aulia</strong>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    
    <div class="text-center mb-4">
        <h3 class="fw-bold">Pilih Menu Spesial</h3>
        <p class="text-muted">Klik atau lihat menu favorit pelanggan hari ini</p>
    </div>

    <div class="row g-4 mb-5">
        @php
        $menus = [
            ['🍗','Nasi Jinggo Ayam', 8000],
            ['🥚','Nasi Jinggo Telur', 6000],
            ['🦐','Nasi Jinggo Udang', 10000],
            ['🐟','Nasi Jinggo Ikan', 9000],
            ['🌱','Nasi Jinggo Tempe', 5000],
            ['🧈','Nasi Jinggo Tahu', 5000],
            ['🍱','Nasi Jinggo Combo', 12000],
        ];
        @endphp

        @foreach($menus as $menu)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card menu-card p-3 shadow-sm h-100">
                <div class="menu-emoji">{{ $menu[0] }}</div>
                <h6 class="fw-bold mb-1">{{ $menu[1] }}</h6>
                <div class="price-badge small">Rp {{ number_format($menu[2],0,',','.') }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="order-section">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-success text-white rounded-circle p-2 me-3">
                        <i class="bi bi-cart-check-fill fs-4"></i>
                    </div>
                    <h4 class="mb-0 fw-bold">Input Pesanan Baru</h4>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ url('/kasir/simpan') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" name="nama_pembeli" class="form-control" placeholder="" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Varian Nasi</label>
                            <select name="varian" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Menu --</option>
                                @foreach($menus as $m)
                                    <option value="{{ $m[1] }}">{{ $m[1] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Porsi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-plus-minus"></i></span>
                                <input type="number" name="jumlah" class="form-control" min="" value="" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-select" required>
                                <option value="Cash">💵 Tunai / Cash</option>
                                <option value="QRIS">📱 QRIS</option>
                                <option value="Transfer">🏦 Transfer Bank</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 btn-order shadow">
                        <i class="bi bi-printer me-2"></i> Simpan Transaksi & Cetak
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
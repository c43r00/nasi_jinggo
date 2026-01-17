<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir | Nasi Jinggo Apel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }
        .navbar { 
            box-shadow: 0 2px 10px rgba(0,0,0,.2); 
            backdrop-filter: blur(10px);
        }
        .stat-card {
            border: none;
            border-radius: 20px;
            padding: 25px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        .btn-action {
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .content-wrapper {
            background: white;
            border-radius: 25px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .transaction-card {
            border: 1px solid #e9ecef;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.2s ease;
        }
        .transaction-card:hover {
            border-color: #198754;
            background: #f8f9fa;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-shop-window me-2"></i> NASI JINGGO APEL
        </a>
        <div class="navbar-nav ms-auto">
            <span class="nav-link text-white">
                <i class="bi bi-person-circle me-1"></i> Kasir: <strong>{{ Auth::user()->name }}</strong>
            </span>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    
    <!-- Welcome Section -->
    <div class="text-center text-white mb-4">
        <h2 class="fw-bold mb-2">Dashboard Kasir</h2>
        <p class="opacity-75">Selamat datang kembali, {{ Auth::user()->name }}! 👋</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <h6 class="text-muted mb-1">Penjualan Hari Ini</h6>
                <h3 class="fw-bold text-success mb-0">Rp {{ number_format($totalSalesToday ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-receipt"></i>
                </div>
                <h6 class="text-muted mb-1">Total Transaksi</h6>
                <h3 class="fw-bold text-primary mb-0">{{ $totalTransactionsToday ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h6 class="text-muted mb-1">Produk Tersedia</h6>
                <h3 class="fw-bold text-info mb-0">{{ $availableProducts ?? 0 }}</h3>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h6 class="text-muted mb-1">Produk Habis</h6>
                <h3 class="fw-bold text-warning mb-0">{{ $outOfStockProducts ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center mb-4">
        <a href="{{ route('kasir.transaksi') }}" class="btn btn-success btn-action me-3">
            <i class="bi bi-cart-plus me-2"></i> Transaksi Baru
        </a>
        <a href="{{ route('logout') }}" class="btn btn-outline-light btn-action" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <!-- Recent Transactions -->
    <div class="content-wrapper">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-success text-white rounded-circle p-2 me-3">
                <i class="bi bi-clock-history fs-4"></i>
            </div>
            <h4 class="mb-0 fw-bold">Transaksi Terbaru</h4>
        </div>

        @if(isset($recentTransaksi) && $recentTransaksi->count() > 0)
            @foreach($recentTransaksi as $t)
            <div class="transaction-card">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            {{ $t->created_at->format('H:i') }}
                        </small>
                        <div class="fw-bold">{{ $t->created_at->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Pembeli</small>
                        <div class="fw-bold">{{ $t->nama_pembeli }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Menu</small>
                        <div>{{ $t->varian }}</div>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Jumlah</small>
                        <div class="fw-bold">{{ $t->jumlah }} porsi</div>
                    </div>
                    <div class="col-md-2 text-end">
                        <div class="text-success fw-bold fs-5">
                            Rp {{ number_format($t->total, 0, ',', '.') }}
                        </div>
                        <a href="{{ route('kasir.rincian', $t->id) }}" class="btn btn-sm btn-outline-success mt-1">
                            <i class="bi bi-eye me-1"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">Belum ada transaksi hari ini</h5>
                <p class="text-muted">Mulai transaksi pertama Anda sekarang!</p>
                <a href="{{ route('kasir.transaksi') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i> Buat Transaksi
                </a>
            </div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

@extends('layouts.admin')

@section('title', 'Katalog Pengajuan Barang')

@section('content')
    <style>
        /* Style Khusus Halaman Katalog */
        .catalog-card {
            background: #fff;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
        }

        .catalog-card:hover {
            border-color: #f39c12;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .item-icon {
            font-size: 40px;
            color: #3498db;
            margin-bottom: 10px;
        }

        .item-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            height: 40px;
            /* Biar tinggi kartu rata */
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-stock {
            font-size: 12px;
            color: #777;
            margin-bottom: 10px;
        }

        .btn-add {
            width: 100%;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        /* Keranjang Belanja (Kanan) */
        .cart-panel {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 20px;
            position: sticky;
            top: 20px;
            /* Biar nempel pas discroll */
        }

        .cart-item {
            border-bottom: 1px dashed #eee;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-qty-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 2px;
        }

        /* === GAYA SEARCH BAR (PILL STYLE) === */
        .search-container {
            position: relative;
            margin-bottom: 25px;
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 15px;
            color: #aaa;
            z-index: 10;
        }

        .search-input {
            height: 50px;
            padding-left: 45px;
            /* Biar teks gak nabrak ikon */
            border-radius: 50px;
            border: 1px solid #eee;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .search-input:focus {
            border-color: #f39c12;
            box-shadow: 0 4px 20px rgba(243, 156, 18, 0.2);
        }

        /* === GAYA LIST BARANG (MODERN LIST) === */
        .item-list-row {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            /* Sudut halus */
            padding: 15px 20px;
            margin-bottom: 10px;
            display: flex;
            /* Kiri Kanan Sejajar */
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        /* Efek Garis Warna di Kiri (Pemanis) */
        .item-list-row::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #eee;
            /* Default abu */
            transition: all 0.2s;
        }

        .item-list-row:hover {
            transform: translateX(5px);
            /* Geser kanan dikit pas dihover */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-color: #f39c12;
        }

        .item-list-row:hover::before {
            background: #f39c12;
            /* Garis jadi oranye pas dihover */
        }

        /* Teks Nama Barang */
        .item-name-text {
            font-weight: 700;
            font-size: 15px;
            color: #333;
            margin-bottom: 3px;
        }

        /* Teks Stok */
        .item-stock-text {
            font-size: 12px;
            color: #888;
        }

        /* Tombol Tambah (Kanan) */
        .btn-add-list {
            background: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 30px;
            padding: 6px 15px;
            font-size: 12px;
            font-weight: bold;
            transition: 0.2s;
        }

        .item-list-row:hover .btn-add-list {
            background: #f39c12;
            color: #fff;
            border-color: #f39c12;
        }

        /* Keranjang Belanja (Kanan) Tetap Sama */
        .cart-panel {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 20px;
            position: sticky;
            top: 20px;
        }

        .cart-item {
            border-bottom: 1px dashed #eee;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-qty-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 2px;
        }
    </style>

    <div class="row">

        <div class="col-md-8">

            <div class="search-container">
                <i class="glyphicon glyphicon-search search-icon"></i>
                <input type="text" id="searchItem" class="form-control search-input"
                    placeholder="Ketik nama barang untuk mencari...">
            </div>

            <div id="catalog-list">
                @forelse($items as $item)
                    <div class="item-list-row" data-name="{{ strtolower($item->name) }}"
                        onclick="addToCart({{ $item->id }}, '{{ $item->name }}', '{{ $item->unit }}', {{ $item->stock }})">

                        <div>
                            <div class="item-name-text">
                                {{ $item->name }}
                            </div>
                            <div class="item-stock-text">
                                <i class="glyphicon glyphicon-tags" style="font-size:10px; margin-right:3px;"></i>
                                Sisa Stok: <b>{{ $item->stock }}</b> {{ $item->unit }}
                            </div>
                        </div>

                        <div>
                            <button class="btn btn-add-list">
                                <i class="glyphicon glyphicon-plus"></i> TAMBAH
                            </button>
                        </div>

                    </div>
                @empty
                    <div class="text-center" style="padding: 50px; color: #999;">
                        <i class="glyphicon glyphicon-inbox" style="font-size: 40px; margin-bottom: 10px;"></i><br>
                        Stok barang sedang kosong semua.
                    </div>
                @endforelse
            </div>

        </div>

        <div class="col-md-4">
            <form action="{{ route('user.request.store') }}" method="POST" id="requestForm">
                {{ csrf_field() }}

                <div class="cart-panel">
                    <h4 style="margin-top:0; font-weight:bold; border-bottom: 2px solid #f39c12; padding-bottom:10px;">
                        <i class="glyphicon glyphicon-shopping-cart"></i> KERANJANG
                    </h4>

                    <div id="cart-items-container" style="min-height: 100px;">
                        <p class="text-muted text-center" id="empty-cart-msg" style="margin-top: 30px;">
                            Belum ada barang dipilih.
                        </p>
                    </div>

                    <hr>

                    <div class="form-group">
    <label style="font-weight: bold;">Departemen Peminta <span class="text-danger">*</span></label>
    
    <select name="department" class="form-control" required style="border-radius:4px; height: 40px;">
        <option value="">-- Pilih Dept --</option>
        
        <option value="Office IT" {{ Auth::user()->department == 'Office IT' ? 'selected' : '' }}>
            Office IT
        </option>
        
        <option value="HRD & GA" {{ Auth::user()->department == 'HRD & GA' ? 'selected' : '' }}>
            HRD & GA
        </option>
        
        <option value="Production" {{ Auth::user()->department == 'Production' ? 'selected' : '' }}>
            Produksi / Pabrik
        </option>
        
        <option value="Marketing" {{ Auth::user()->department == 'Marketing' ? 'selected' : '' }}>
            Marketing & Sales
        </option>
        
        <option value="Finance" {{ Auth::user()->department == 'Finance' ? 'selected' : '' }}>
            Finance & Accounting
        </option>
        
        <option value="Warehouse" {{ Auth::user()->department == 'Warehouse' ? 'selected' : '' }}>
            Warehouse / Gudang
        </option>
        
        <option value="Purchasing" {{ Auth::user()->department == 'Purchasing' ? 'selected' : '' }}>
            Purchasing
        </option>
    </select>
    
    <small class="text-muted" style="font-size: 11px;">
        <i class="glyphicon glyphicon-info-sign"></i> 
        Otomatis terisi sesuai profil Anda, namun dapat diubah jika mewakili divisi lain.
    </small>
</div>

                    <div class="form-group">
                        <label>Keperluan (Opsional)</label>
                        <textarea name="reason" class="form-control" rows="2"
                            placeholder="Contoh: Untuk project bulan depan..."
                            style="resize:none; border-radius:4px;"></textarea>
                    </div>

                    <button type="submit" class="btn btn-warning btn-block btn-lg"
                        style="font-weight:bold; border-radius:4px;">
                        AJUKAN PERMINTAAN
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // === LOGIC KERANJANG BELANJA (JAVASCRIPT) === //

        // 1. Fungsi Tambah ke Keranjang
        function addToCart(id, name, unit, maxStock) {
            var container = document.getElementById('cart-items-container');
            var emptyMsg = document.getElementById('empty-cart-msg');

            // Hilangkan pesan "kosong" jika ada
            if (emptyMsg) emptyMsg.style.display = 'none';

            // Cek apakah barang sudah ada di keranjang?
            var existingRow = document.getElementById('row-item-' + id);

            if (existingRow) {
                // Kalau sudah ada, tambah qty-nya aja
                var inputQty = existingRow.querySelector('.cart-qty-input');
                var currentQty = parseInt(inputQty.value);
                if (currentQty < maxStock) {
                    inputQty.value = currentQty + 1;
                } else {
                    alert('Stok maksimal terpilih!');
                }
            } else {
                // Kalau belum ada, bikin baris HTML baru
                var html = `
                                        <div class="cart-item" id="row-item-${id}">
                                            <div style="flex-grow: 1;">
                                                <input type="hidden" name="item_id[]" value="${id}">
                                                <div style="font-weight:bold; font-size:13px;">${name}</div>
                                                <small class="text-muted">${unit}</small>
                                            </div>
                                            <div style="display:flex; align-items:center;">
                                                <input type="number" name="qty[]" class="cart-qty-input" value="1" min="1" max="${maxStock}">
                                                <button type="button" class="btn btn-danger btn-xs" onclick="removeItem(${id})" style="margin-left:5px; border-radius:50%;">
                                                    &times;
                                                </button>
                                            </div>
                                        </div>
                                    `;
                container.insertAdjacentHTML('beforeend', html);
            }
        }

        // 2. Fungsi Hapus dari Keranjang
        function removeItem(id) {
            var row = document.getElementById('row-item-' + id);
            if (row) row.remove();

            // Cek kalau kosong lagi, munculin pesan "Belum ada barang"
            var container = document.getElementById('cart-items-container');
            if (container.children.length === 1) { // 1 itu si emptyMsg yang di-hide
                document.getElementById('empty-cart-msg').style.display = 'block';
            }
        }

        // 3. Fungsi Search Barang (Update untuk List Style)
        document.getElementById('searchItem').addEventListener('keyup', function () {
            var value = this.value.toLowerCase();
            // Target class yang baru: .item-list-row
            var items = document.querySelectorAll('.item-list-row');

            items.forEach(function (item) {
                var name = item.getAttribute('data-name');
                if (name.indexOf(value) > -1) {
                    item.style.display = "flex"; // Pake flex biar layout gak hancur
                } else {
                    item.style.display = "none";
                }
            });
        });
    </script>
@endsection
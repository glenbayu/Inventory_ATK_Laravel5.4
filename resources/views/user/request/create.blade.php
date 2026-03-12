@extends('layouts.admin')

@section('title', 'Katalog Pengajuan Barang')

@section('content')
    <style>
        .request-page {
            margin-top: 4px;
        }

        .search-container {
            position: relative;
            margin-bottom: 18px;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 11px;
            color: #8f9ba7;
            z-index: 2;
        }

        .search-input {
            height: 40px;
            padding-left: 38px;
            border-radius: 4px;
            border: 1px solid #d8dee4;
            box-shadow: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .search-input:focus {
            border-color: #d88a14;
            box-shadow: 0 0 0 2px rgba(216, 138, 20, 0.12);
        }

        .item-list-row {
            background: #fff;
            border: 1px solid #dfe5ea;
            border-radius: 6px;
            padding: 14px 16px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .item-list-row::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: transparent;
            transition: background-color 0.2s ease;
        }

        .item-list-row:hover {
            border-color: #d88a14;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
        }

        .item-list-row:hover::before {
            background: #d88a14;
        }

        .item-name-text {
            font-weight: 700;
            font-size: 14px;
            color: #1f2933;
            margin-bottom: 2px;
        }

        .item-stock-text {
            font-size: 12px;
            color: #5f6b76;
        }

        .btn-add-list {
            background: #f3f6f8;
            color: #2b3742;
            border: 1px solid #d8dee4;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .item-list-row:hover .btn-add-list {
            background: #d88a14;
            color: #fff;
            border-color: #d88a14;
        }

        .cart-panel {
            background: #fff;
            border: 1px solid #d8dee4;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
            padding: 16px;
            position: sticky;
            top: 20px;
        }

        .cart-title {
            margin-top: 0;
            font-weight: 700;
            border-bottom: 1px solid #d8dee4;
            padding-bottom: 10px;
        }

        .cart-item {
            border-bottom: 1px dashed #d8dee4;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-meta {
            flex-grow: 1;
        }

        .cart-item-name {
            font-weight: 700;
            font-size: 13px;
            color: #1f2933;
        }

        .cart-item-actions {
            display: flex;
            align-items: center;
        }

        .cart-qty-input {
            width: 54px;
            text-align: center;
            border: 1px solid #cfd7de;
            border-radius: 4px;
            padding: 3px;
        }

        .empty-state {
            padding: 40px 12px;
            color: #80909d;
        }

        .dept-note {
            font-size: 11px;
        }

        .btn-submit-request {
            font-weight: 700;
            border-radius: 4px;
        }

        .alert-inline {
            margin-bottom: 12px;
        }
    </style>

    <div class="row request-page">
        <div class="col-md-8">
            <div class="search-container">
                <i class="glyphicon glyphicon-search search-icon"></i>
                <input type="text" id="searchItem" class="form-control search-input"
                    placeholder="Ketik nama barang untuk mencari...">
            </div>

            <div id="catalog-list">
                @forelse($items as $item)
                    <div class="item-list-row"
                        data-name="{{ strtolower($item->name) }}"
                        data-item-id="{{ $item->id }}"
                        data-item-name="{{ e($item->name) }}"
                        data-item-unit="{{ e($item->unit) }}"
                        data-item-stock="{{ $item->stock }}"
                        onclick="addItemFromRow(this)">

                        <div>
                            <div class="item-name-text">
                                {{ $item->name }}
                            </div>
                            <div class="item-stock-text">
                                <i class="glyphicon glyphicon-tags"></i>
                                Sisa Stok: <b>{{ $item->stock }}</b> {{ $item->unit }}
                            </div>
                        </div>

                        <div>
                            <button type="button" class="btn btn-add-list">
                                <i class="glyphicon glyphicon-plus"></i> TAMBAH
                            </button>
                        </div>

                    </div>
                @empty
                    <div class="text-center empty-state">
                        <i class="glyphicon glyphicon-inbox" style="font-size: 32px; margin-bottom: 8px;"></i><br>
                        Stok barang sedang kosong semua.
                    </div>
                @endforelse
            </div>

        </div>

        <div class="col-md-4">
            <form action="{{ route('user.request.store') }}" method="POST" id="requestForm">
                {{ csrf_field() }}

                <div class="cart-panel">
                    @if(session('error'))
                        <div class="alert alert-danger alert-inline">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-inline">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <h4 class="cart-title">
                        <i class="glyphicon glyphicon-shopping-cart"></i> KERANJANG
                    </h4>

                    <div id="cart-items-container" style="min-height: 96px;">
                        <p class="text-muted text-center" id="empty-cart-msg" style="margin-top: 22px;">
                            Belum ada barang dipilih.
                        </p>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label style="font-weight: bold;">Departemen Peminta <span class="text-danger">*</span></label>

                        @php
                            $selectedDepartment = old('department', Auth::user()->department);
                            $departmentOptions = isset($departments) ? $departments : \App\User::departmentOptions();
                        @endphp
                        <select name="department" class="form-control" required style="height: 40px;">
                            <option value="">-- Pilih Dept --</option>
                            @foreach($departmentOptions as $department)
                                <option value="{{ $department }}" {{ $selectedDepartment == $department ? 'selected' : '' }}>
                                    {{ $department }}
                                </option>
                            @endforeach
                        </select>

                        <small class="text-muted dept-note">
                            <i class="glyphicon glyphicon-info-sign"></i>
                            Otomatis terisi sesuai profil Anda, namun dapat diubah jika mewakili divisi lain.
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Keperluan (Opsional)</label>
                        <textarea name="reason" class="form-control" rows="2"
                            placeholder="Contoh: Untuk project bulan depan..."
                            style="resize:none;">{{ old('reason') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-warning btn-block btn-lg btn-submit-request">
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
        function addItemFromRow(el) {
            var id = parseInt(el.dataset.itemId, 10);
            var name = el.dataset.itemName;
            var unit = el.dataset.itemUnit;
            var maxStock = parseInt(el.dataset.itemStock, 10);

            addToCart(id, name, unit, maxStock);
        }

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
                                            <div class="cart-item-meta">
                                                <input type="hidden" name="item_id[]" value="${id}">
                                                <div class="cart-item-name">${name}</div>
                                                <small class="text-muted">${unit}</small>
                                            </div>
                                            <div class="cart-item-actions">
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
            var cartItems = container.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
                document.getElementById('empty-cart-msg').style.display = 'block';
            }
        }

        document.getElementById('cart-items-container').addEventListener('input', function (e) {
            if (!e.target.classList.contains('cart-qty-input')) return;

            var max = parseInt(e.target.getAttribute('max'), 10);
            var value = parseInt(e.target.value, 10);

            if (isNaN(value) || value < 1) {
                e.target.value = 1;
                return;
            }

            if (!isNaN(max) && value > max) {
                e.target.value = max;
                alert('Qty melebihi stok yang tersedia.');
            }
        });

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

        document.getElementById('requestForm').addEventListener('submit', function (e) {
            var cartItems = document.querySelectorAll('#cart-items-container .cart-item');
            if (cartItems.length === 0) {
                e.preventDefault();
                alert('Pilih minimal 1 barang sebelum mengajukan.');
            }
        });
    </script>
@endsection

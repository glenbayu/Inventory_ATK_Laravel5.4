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
                <form method="GET" action="{{ route('user.request.create') }}">
                    <div style="display: flex; gap: 10px;">
                        <div style="position: relative; flex: 1;">
                            <i class="glyphicon glyphicon-search search-icon"></i>
                            <input 
                                type="text" 
                                name="search" 
                                class="form-control search-input" 
                                placeholder="Ketik nama barang..."
                                value="{{ request('search') }}"
                                style="margin-bottom: 0;"
                            >
                        </div>
                        <button type="submit" class="btn btn-warning" style="border-radius: 50px; padding: 0 25px; font-weight: bold;">
                            <i class="glyphicon glyphicon-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>

            <div id="catalog-list">
                @forelse($items as $item)
                    <div class="item-list-row js-add-to-cart"
                        data-name="{{ strtolower($item->name) }}"
                        data-item-id="{{ $item->id }}"
                        data-item-name="{{ e($item->name) }}"
                        data-item-unit="{{ e($item->unit) }}"
                        data-item-stock="{{ $item->stock }}">

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
            <div class="text-center" style="margin-top: 20px;">
                {{ $items->appends(request()->query())->links() }}
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
        
        <option value="Assy" {{ Auth::user()->department == 'Assy' ? 'selected' : '' }}>
            Assy
        </option>
        
        <option value="Mach" {{ Auth::user()->department == 'Mach' ? 'selected' : '' }}>
            Mach
        </option>
        
        <option value="PPC" {{ Auth::user()->department == 'PPC' ? 'selected' : '' }}>
            PPC
        </option>
        
        <option value="MTC/Facility" {{ Auth::user()->department == 'MTC/Facility' ? 'selected' : '' }}>
            MTC/Facility
        </option>
        
        <option value="Finance" {{ Auth::user()->department == 'Finance' ? 'selected' : '' }}>
            Finance
        </option>
        
        <option value="QC" {{ Auth::user()->department == 'QC' ? 'selected' : '' }}>
            QC
        </option>
        
        <option value="HRGA" {{ Auth::user()->department == 'HRGA' ? 'selected' : '' }}>
            HRGA
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
        var REQUEST_DRAFT_KEY = 'atk_request_draft_v1';

        function escapeHtml(text) {
            return String(text).replace(/[&<>"']/g, function (char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[char];
            });
        }

        function normalizeQty(value, maxStock) {
            var qty = parseInt(value, 10);
            var max = parseInt(maxStock, 10);

            if (isNaN(qty) || qty < 1) {
                qty = 1;
            }

            if (!isNaN(max) && max > 0 && qty > max) {
                qty = max;
            }

            return qty;
        }

        function updateEmptyCartState() {
            var emptyMsg = document.getElementById('empty-cart-msg');
            var rows = document.querySelectorAll('#cart-items-container .cart-item');
            if (emptyMsg) {
                emptyMsg.style.display = rows.length > 0 ? 'none' : 'block';
            }
        }

        function collectDraftItems() {
            var rows = document.querySelectorAll('#cart-items-container .cart-item');
            var items = [];

            rows.forEach(function (row) {
                var qtyInput = row.querySelector('.cart-qty-input');
                if (!qtyInput) {
                    return;
                }

                var maxStock = parseInt(row.getAttribute('data-max-stock'), 10);
                var qty = normalizeQty(qtyInput.value, maxStock);
                qtyInput.value = qty;

                items.push({
                    id: parseInt(row.getAttribute('data-item-id'), 10),
                    name: row.getAttribute('data-item-name') || '',
                    unit: row.getAttribute('data-item-unit') || '',
                    maxStock: !isNaN(maxStock) && maxStock > 0 ? maxStock : qty,
                    qty: qty
                });
            });

            return items;
        }

        function saveDraft() {
            try {
                var departmentField = document.querySelector('select[name="department"]');
                var reasonField = document.querySelector('textarea[name="reason"]');

                var draft = {
                    items: collectDraftItems(),
                    department: departmentField ? departmentField.value : '',
                    reason: reasonField ? reasonField.value : ''
                };

                localStorage.setItem(REQUEST_DRAFT_KEY, JSON.stringify(draft));
            } catch (error) {
                // Abaikan kalau browser block localStorage
            }
        }

        function upsertCartItem(id, name, unit, maxStock, qty, skipSave) {
            var container = document.getElementById('cart-items-container');
            if (!container) {
                return;
            }

            var parsedId = parseInt(id, 10);
            var parsedMax = parseInt(maxStock, 10);
            var safeMax = !isNaN(parsedMax) && parsedMax > 0 ? parsedMax : 1;

            var existingRow = document.getElementById('row-item-' + parsedId);
            if (existingRow) {
                var inputQty = existingRow.querySelector('.cart-qty-input');
                var currentQty = parseInt(inputQty.value, 10);
                if (isNaN(currentQty)) {
                    currentQty = 1;
                }

                var existingMax = parseInt(existingRow.getAttribute('data-max-stock'), 10);
                if (isNaN(existingMax) || existingMax < safeMax) {
                    existingMax = safeMax;
                }

                existingRow.setAttribute('data-max-stock', existingMax);
                inputQty.max = existingMax;
                inputQty.value = normalizeQty(qty || currentQty + 1, existingMax);
            } else {
                var row = document.createElement('div');
                row.className = 'cart-item';
                row.id = 'row-item-' + parsedId;
                row.setAttribute('data-item-id', parsedId);
                row.setAttribute('data-item-name', name);
                row.setAttribute('data-item-unit', unit);
                row.setAttribute('data-max-stock', safeMax);

                row.innerHTML = '' +
                    '<div style="flex-grow: 1;">' +
                    '<input type="hidden" name="item_id[]" value="' + parsedId + '">' +
                    '<div style="font-weight:bold; font-size:13px;">' + escapeHtml(name) + '</div>' +
                    '<small class="text-muted">' + escapeHtml(unit) + '</small>' +
                    '</div>' +
                    '<div style="display:flex; align-items:center;">' +
                    '<input type="number" name="qty[]" class="cart-qty-input" value="' + normalizeQty(qty || 1, safeMax) + '" min="1" max="' + safeMax + '">' +
                    '<button type="button" class="btn btn-danger btn-xs" onclick="removeItem(' + parsedId + ')" style="margin-left:5px; border-radius:50%;">&times;</button>' +
                    '</div>';

                container.appendChild(row);
            }

            updateEmptyCartState();
            if (!skipSave) {
                saveDraft();
            }
        }

        function addToCart(id, name, unit, maxStock) {
            var parsedId = parseInt(id, 10);
            var parsedMax = parseInt(maxStock, 10);
            var safeMax = !isNaN(parsedMax) && parsedMax > 0 ? parsedMax : 1;

            var existingRow = document.getElementById('row-item-' + parsedId);
            if (existingRow) {
                var inputQty = existingRow.querySelector('.cart-qty-input');
                var currentQty = parseInt(inputQty.value, 10);
                var rowMax = parseInt(existingRow.getAttribute('data-max-stock'), 10);
                var effectiveMax = Math.max(!isNaN(rowMax) ? rowMax : 1, safeMax);

                existingRow.setAttribute('data-max-stock', effectiveMax);
                inputQty.max = effectiveMax;

                if (currentQty < effectiveMax) {
                    inputQty.value = currentQty + 1;
                } else {
                    alert('Stok maksimal terpilih!');
                }
            } else {
                upsertCartItem(parsedId, name, unit, safeMax, 1, true);
            }

            updateEmptyCartState();
            saveDraft();
        }

        function removeItem(id) {
            var row = document.getElementById('row-item-' + id);
            if (row) {
                row.remove();
            }
            updateEmptyCartState();
            saveDraft();
        }

        function restoreDraft() {
            try {
                var raw = localStorage.getItem(REQUEST_DRAFT_KEY);
                if (!raw) {
                    updateEmptyCartState();
                    return;
                }

                var draft = JSON.parse(raw);
                if (!draft || typeof draft !== 'object') {
                    updateEmptyCartState();
                    return;
                }

                if (Array.isArray(draft.items)) {
                    draft.items.forEach(function (item) {
                        if (!item || !item.id) {
                            return;
                        }
                        upsertCartItem(
                            item.id,
                            item.name || ('Item #' + item.id),
                            item.unit || '',
                            item.maxStock || item.qty || 1,
                            item.qty || 1,
                            true
                        );
                    });
                }

                var departmentField = document.querySelector('select[name="department"]');
                if (departmentField && typeof draft.department === 'string' && draft.department !== '') {
                    departmentField.value = draft.department;
                }

                var reasonField = document.querySelector('textarea[name="reason"]');
                if (reasonField && typeof draft.reason === 'string') {
                    reasonField.value = draft.reason;
                }
            } catch (error) {
                // Abaikan error parse draft
            }

            updateEmptyCartState();
        }

        function bindDraftEvents() {
            var cartContainer = document.getElementById('cart-items-container');
            if (cartContainer) {
                cartContainer.addEventListener('input', function (event) {
                    if (!event.target.classList.contains('cart-qty-input')) {
                        return;
                    }

                    var row = event.target.closest('.cart-item');
                    var maxStock = row ? parseInt(row.getAttribute('data-max-stock'), 10) : null;
                    event.target.value = normalizeQty(event.target.value, maxStock);
                    saveDraft();
                });

                cartContainer.addEventListener('change', function (event) {
                    if (!event.target.classList.contains('cart-qty-input')) {
                        return;
                    }

                    var row = event.target.closest('.cart-item');
                    var maxStock = row ? parseInt(row.getAttribute('data-max-stock'), 10) : null;
                    event.target.value = normalizeQty(event.target.value, maxStock);
                    saveDraft();
                });
            }

            var departmentField = document.querySelector('select[name="department"]');
            if (departmentField) {
                departmentField.addEventListener('change', saveDraft);
            }

            var reasonField = document.querySelector('textarea[name="reason"]');
            if (reasonField) {
                reasonField.addEventListener('input', saveDraft);
            }

            var requestForm = document.getElementById('requestForm');
            if (requestForm) {
                requestForm.addEventListener('submit', saveDraft);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            restoreDraft();
            bindDraftEvents();

            var itemRows = document.querySelectorAll('.js-add-to-cart');
            itemRows.forEach(function (row) {
                row.addEventListener('click', function () {
                    addToCart(
                        parseInt(row.getAttribute('data-item-id'), 10),
                        row.getAttribute('data-item-name') || '',
                        row.getAttribute('data-item-unit') || '',
                        parseInt(row.getAttribute('data-item-stock'), 10) || 1
                    );
                });
            });

            var searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    if (searchInput.value === '') {
                        window.location.href = "{{ route('user.request.create') }}";
                    }
                });
            }
        });
    </script>
@endsection

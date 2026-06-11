@extends('layouts.app')

@section('title', 'Pembayaran - MyStock')

@section('content')
    @include('components.appbar', ['color' => 'blue', 'icon' => 'bi-receipt', 'title' => 'Detail Pembayaran', 'subtitle' => 'Selesaikan transaksi'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="page-header">
        <div class="title-group"></div>
        <div class="actions">
            <a href="{{ route('transactions.index') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="form-card" style="max-width:560px; margin:0 auto;">
        <h6 style="font-weight:600; margin:0 0 12px;">Daftar Item Belanja</h6>
        <table class="data-table" style="margin-bottom:12px;">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingTransaction['items'] as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-end">{{ $item['quantity'] }}</td>
                    <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="cart-total" style="display:flex; justify-content:space-between; padding-top:12px; border-top:1px solid var(--border-soft); font-weight:700; margin-bottom:20px;">
            <span>Total Tagihan</span>
            <span style="color:#0B3BB6; font-size:18px;">Rp {{ number_format($pendingTransaction['total'], 0, ',', '.') }}</span>
        </div>

        <form action="{{ route('transactions.process') }}" method="POST" id="paymentForm">
            @csrf
            <div class="form-field">
                <label>Metode Pembayaran</label>
                <div class="pay-methods">
                    <div class="pay-method" onclick="selectPayment('cash', this)">
                        <i class="bi bi-cash-stack"></i>
                        <span>Tunai</span>
                        <input type="radio" name="payment_method" value="cash" hidden required>
                    </div>
                    <div class="pay-method" onclick="selectPayment('qris', this)">
                        <i class="bi bi-qr-code"></i>
                        <span>QRIS</span>
                        <input type="radio" name="payment_method" value="qris" hidden>
                    </div>
                </div>
                @error('payment_method')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div id="cashPayment" style="display:none;" class="pay-detail">
                <div class="form-field">
                    <label>Jumlah Uang Diterima (Rp)</label>
                    <input type="number" name="payment_amount" class="form-control" id="paymentAmount" placeholder="0">
                    <input type="hidden" name="change_amount" id="changeAmountHidden">
                </div>
                <div class="form-field" style="margin-bottom:0;">
                    <label>Kembalian</label>
                    <input type="text" class="form-control" id="changeAmount" readonly value="Rp 0" style="font-weight:600;">
                </div>
            </div>

            <div id="qrisPayment" style="display:none;" class="pay-detail text-center">
                @if($qris)
                    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                        <p style="font-weight:600; margin-bottom:8px; text-align:center;">{{ $qris->nama_merchant }}</p>
                        <img src="{{ asset('storage/' . $qris->foto) }}" alt="QRIS" onclick="openQrModal(this.src)" title="Klik untuk perbesar" style="max-width:200px; padding:10px; background:#fff; border-radius:10px; border:1px solid var(--border-soft); display:block; margin:0 auto; cursor:zoom-in;">
                        <p class="form-hint mt-2" style="text-align:center;"><i class="bi bi-zoom-in"></i> Klik QR untuk memperbesar</p>
                        @if($qris->keterangan)
                            <p class="form-hint mt-2" style="text-align:center;">{{ $qris->keterangan }}</p>
                        @endif
                    </div>
                @else
                    <p class="text-muted" style="text-align:center;">Tidak ada QRIS aktif. Hubungi admin untuk upload QR Code.</p>
                @endif
                <input type="hidden" name="payment_amount" value="{{ $pendingTransaction['total'] }}">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Proses Pembayaran</button>
                <a href="{{ route('transactions.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>

    <div id="qrModal" class="qr-modal" onclick="closeQrModal()">
        <span class="qr-modal-close" onclick="closeQrModal()">&times;</span>
        <img id="qrModalImg" src="" alt="QRIS Diperbesar">
    </div>

    <style>
        .pay-methods { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        .pay-method { border:2px solid var(--border-soft); border-radius:10px; padding:16px; text-align:center; cursor:pointer; transition:all .2s; }
        .pay-method i { font-size:28px; color:var(--text-muted); display:block; margin-bottom:6px; }
        .pay-method span { font-weight:600; color:var(--text-secondary); }
        .pay-method.selected { border-color:#0B3BB6; background:rgba(11,59,182,.05); }
        .pay-method.selected i, .pay-method.selected span { color:#0B3BB6; }
        .pay-detail { margin-top:16px; padding:16px; background:var(--bg-soft); border-radius:10px; border:1px solid var(--border-soft); }

        .qr-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,.85); z-index:9999; align-items:center; justify-content:center; padding:24px; cursor:zoom-out; animation:fadeIn .2s ease; }
        .qr-modal.open { display:flex; }
        .qr-modal img { max-width:90vw; max-height:90vh; background:#fff; padding:20px; border-radius:14px; box-shadow:0 20px 60px rgba(0,0,0,.5); }
        .qr-modal-close { position:absolute; top:16px; right:24px; color:#fff; font-size:42px; font-weight:300; cursor:pointer; line-height:1; user-select:none; }
        .qr-modal-close:hover { color:#ddd; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    </style>

    <script>
        function openQrModal(src) {
            document.getElementById('qrModalImg').src = src;
            document.getElementById('qrModal').classList.add('open');
        }
        function closeQrModal() {
            document.getElementById('qrModal').classList.remove('open');
        }
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeQrModal(); });

        const total = {{ $pendingTransaction['total'] }};
        const paymentInput = document.getElementById('paymentAmount');
        const changeDisplay = document.getElementById('changeAmount');

        function selectPayment(method, element) {
            document.querySelectorAll('.pay-method').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            element.querySelector('input[type="radio"]').checked = true;
            document.getElementById('cashPayment').style.display = method === 'cash' ? 'block' : 'none';
            document.getElementById('qrisPayment').style.display = method === 'qris' ? 'block' : 'none';
            if (method === 'qris') {
                document.querySelector('#qrisPayment input[name="payment_amount"]').value = total;
            }
        }

        paymentInput?.addEventListener('input', function() {
            const payment = parseFloat(this.value) || 0;
            const change = payment - total;
            changeDisplay.value = change >= 0 ? `Rp ${change.toLocaleString('id-ID')}` : 'Pembayaran belum mencukupi';
            document.getElementById('changeAmountHidden').value = change >= 0 ? change : 0;
        });

        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedMethod) { alert('Pilih metode pembayaran terlebih dahulu'); return; }

            if (selectedMethod.value === 'cash') {
                const payment = parseFloat(paymentInput.value) || 0;
                const change = payment - total;
                if (payment < total) { alert('Jumlah pembayaran kurang dari total tagihan'); return; }
                try {
                    const response = await fetch('{{ route("transactions.save-payment") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ payment_amount: payment, change_amount: change })
                    });
                    const result = await response.json();
                    if (result.success) HTMLFormElement.prototype.submit.call(form);
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan saat menyimpan data pembayaran');
                }
            } else {
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    </script>
@endsection

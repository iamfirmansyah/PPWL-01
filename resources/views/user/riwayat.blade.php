@extends('layouts.user.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Riwayat Pesanan</h2>

    @if($orders->count() > 0)
        @foreach($orders as $order)
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="row">
                    <div class="col-md-6">
                        <strong>No. Pesanan:</strong> {{ $order->id }}
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($order->tanggal)->format('d M Y') }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->products as $product)
                        <tr>
                            <td>{{ $product->nama }}</td>
                            <td>{{ $product->pivot->jumlah }}</td>
                            <td>Rp {{ number_format($product->pivot->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($product->pivot->jumlah * $product->pivot->harga_satuan, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <strong>Status Pembayaran:</strong>
                        @if($order->status_pembayaran == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($order->status_pembayaran == 'diproses')
                            <span class="badge bg-info">Diproses</span>
                        @elseif($order->status_pembayaran == 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @else
                            <span class="badge bg-danger">Gagal</span>
                        @endif
                    </div>
                    <div class="col-md-6 text-end">
                        @if($order->bukti_pembayaran)
                            <a href="{{ asset('storage/' . $order->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-info">
                                Lihat Bukti Pembayaran
                            </a>
                        @else
                            <span class="text-muted">Belum ada bukti pembayaran</span>
                        @endif
                    </div>
                </div>

                @if(!$order->bukti_pembayaran)
                <div class="mt-3">
                    <form action="{{ route('checkout.updatePaymentProof', $order->id) }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                        @csrf
                        @method('PUT')
                        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                        <button type="submit" class="btn btn-primary">Upload Bukti</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info">
            Belum ada riwayat pesanan.
        </div>
        <a href="/" class="btn btn-primary">Mulai Belanja</a>
    @endif
</div>
@endsection

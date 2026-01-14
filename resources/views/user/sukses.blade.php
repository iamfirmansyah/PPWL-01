@extends('layouts.user.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Pesanan Berhasil Dibuat!</h2>
                    <p class="text-muted mb-4">Terima kasih telah berbelanja di TokoKu</p>

                    <div class="alert alert-info">
                        <strong>No. Pesanan:</strong> {{ $order->id }}
                    </div>

                    <h5 class="fw-bold mt-4 mb-3">Detail Pesanan</h5>
                    <table class="table table-bordered">
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

                    <div class="alert alert-warning mt-4">
                        <strong>Status Pembayaran:</strong> {{ ucfirst($order->status_pembayaran) }}
                    </div>

                    <h5 class="fw-bold mt-4 mb-3">Upload Bukti Pembayaran</h5>
                    <form action="{{ route('checkout.updatePaymentProof', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload Bukti Pembayaran</button>
                    </form>

                    <div class="mt-4">
                        <a href="{{ route('orders.history') }}" class="btn btn-secondary me-2">Lihat Riwayat Pesanan</a>
                        <a href="/" class="btn btn-success">Lanjut Belanja</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

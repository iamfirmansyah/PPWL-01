@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> Kelola Pesanan
    </h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <h5 class="card-header">Daftar Semua Pesanan</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 180px;">No. Pesanan</th>
                        <th style="min-width: 100px;">Tanggal</th>
                        <th style="min-width: 120px;">Pembeli</th>
                        <th style="min-width: 120px;">Total</th>
                        <th style="min-width: 100px;">Status</th>
                        <th style="min-width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><small>{{ $order->id }}</small></td>
                        <td>{{ \Carbon\Carbon::parse($order->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            @if($order->status_pembayaran == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($order->status_pembayaran == 'diproses')
                                <span class="badge bg-info">Diproses</span>
                            @elseif($order->status_pembayaran == 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-danger">Gagal</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}">
                                    Detail
                                </button>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                    Status
                                </button>
                                @if($order->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $order->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-primary">
                                        Bukti
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->
    @foreach($orders as $order)
    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan {{ $order->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Produk yang Dipesan:</h6>
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
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Modal -->
    <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                            <select name="status_pembayaran" class="form-select" required>
                                <option value="pending" {{ $order->status_pembayaran == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="diproses" {{ $order->status_pembayaran == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="lunas" {{ $order->status_pembayaran == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="gagal" {{ $order->status_pembayaran == 'gagal' ? 'selected' : '' }}>Gagal</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

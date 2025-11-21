@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="mb-3 text-left">
        <label for="nama" class="form-label">Search product..</label>
                <input
                    type="text"
                    class="form-control @error('nama') is-invalid @enderror"
                    id="search"
                    name="search"
                    placeholder="Enter product name"
                    required
                />
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Add Product
        </a>
    </div>

    

    @if(session('success'))
        <div class="alert alert-success alert-dismissible mx-4 mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->foto)
                                <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}" class="rounded" width="50" height="50">
                            @else
                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="No image" class="rounded" width="50" height="50">
                            @endif
                        </td>
                        <td><strong>{{ $product->nama }}</strong></td>
                        <td>
                            <span class="badge bg-label-primary">{{ $product->category->nama }}</span>
                        </td>
                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>
                            @if($product->stok > 10)
                                <span class="badge bg-label-success">{{ $product->stok }}</span>
                            @elseif($product->stok > 0)
                                <span class="badge bg-label-warning">{{ $product->stok }}</span>
                            @else
                                <span class="badge bg-label-danger">{{ $product->stok }}</span>
                            @endif
                        </td>
                        <td>
                            <a class="dropdown-item" href="{{ route('products.edit', $product->id) }}">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $("#search").on("keyup", function () {
        let value = $(this).val().toLowerCase();

        $("table tbody tr").filter(function () {
            $(this).toggle(
                $(this).text().toLowerCase().indexOf(value) > -1
            );
        });
    });
});
</script>

@extends('layouts.dashboard')
@section('title','Edit Produk')
@section('page-title','Edit Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            @if($errors->any())
            <div class="alert alert-danger small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form action="{{ route('owner.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name',$product->name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id',$product->category_id)==$cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Harga (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text small">Rp</span>
                            <input type="number" name="price" value="{{ old('price',$product->price) }}" class="form-control" min="0" step="500" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock',$product->stock) }}" class="form-control" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description',$product->description) }}</textarea>
                    </div>
                    @if($product->image)
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Foto Saat Ini</label>
                        <div><img src="{{ asset('storage/'.$product->image) }}" class="img-thumbnail" style="max-width:160px"></div>
                    </div>
                    @endif
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Ganti Foto</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_available" value="1" class="form-check-input" id="isAvail" @checked(old('is_available',$product->is_available))>
                            <label class="form-check-label small fw-semibold" for="isAvail">Produk Tersedia</label>
                        </div>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-orange">Update Produk</button>
                        <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
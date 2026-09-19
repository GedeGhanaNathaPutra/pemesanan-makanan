@extends('layouts.dashboard')
@section('title','Edit Restoran')
@section('page-title','Edit Restoran')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            @if($errors->any())
            <div class="alert alert-danger small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form action="{{ route('owner.restaurant.update', $restaurant) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Nama Restoran</label>
                        <input type="text" name="name" value="{{ old('name',$restaurant->name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone',$restaurant->phone) }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Alamat</label>
                        <textarea name="address" class="form-control" rows="3" required>{{ old('address',$restaurant->address) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description',$restaurant->description) }}</textarea>
                    </div>
                    @if($restaurant->image)
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Foto Saat Ini</label>
                        <div><img src="{{ asset('storage/'.$restaurant->image) }}" class="img-thumbnail" style="max-width:200px"></div>
                    </div>
                    @endif
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Ganti Foto</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_open" value="1" class="form-check-input" id="isOpen" @checked(old('is_open', $restaurant->is_open))>
                            <label class="form-check-label fw-semibold small" for="isOpen">Restoran Sedang Buka</label>
                        </div>
                    </div>
                    <div class="col-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-orange">Update</button>
                        <a href="{{ route('owner.restaurant.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
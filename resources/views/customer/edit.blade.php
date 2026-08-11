@extends('layouts.app')

@section('content')
<div class="content mt-5">
    <div class="intro-y box p-5">
        <h2 class="font-medium text-base mb-5">{{ $title }}</h2>
        <form action="{{ route('Customer.update', $customer->id_costomer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label>Nama</label>
                <input type="text" name="nama" value="{{ $customer->cos_nama }}" class="input w-full border" required>
            </div>
            
            <div class="mb-4">
                <label>Alamat</label>
                <input type="text" name="alamat" value="{{ $customer->cos_alamat }}" class="input w-full border">
            </div>
            
            <div class="mb-4">
                <label>No HP</label>
                <input type="text" name="tlp" value="{{ $customer->cos_hp }}" class="input w-full border">
            </div>
            
            <div class="mt-5">
                <button type="submit" class="button bg-theme-1 text-white">Simpan</button>
                <a href="{{ route('Customer.index') }}" class="button border text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

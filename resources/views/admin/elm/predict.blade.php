@extends('layouts.dashboard')
@section('content')
<div class="card shadow mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Prediksi</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('prediksi.create') }}" class="btn btn-primary btn-block">
                <i class="fas fa-fw fa-plus-circle"></i> Tambah Data
            </a>
        </div>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Hasil Prediksi</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th style="color: navy;">Data Produksi</th>
                    <th style="color: navy;">Prediksi Produksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $baris1)
                <tr>
                    @foreach ($baris1 as $nilai1)
                    <td>{{ $nilai1 }}</td>
                    @endforeach
                    @foreach ($prediksi[$index] as $nilai)
                    <td>{{ $nilai }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
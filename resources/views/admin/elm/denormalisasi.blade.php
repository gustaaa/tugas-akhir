@extends('layouts.dashboard')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Denormalisasi</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th style="color: navy;">Denormalisasi</th>
                    <th style="color: navy;">Data Aktual</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($denormalisasi as $index => $baris1)
                <tr>
                    @foreach ($baris1 as $nilai1)
                    <td>{{ number_format($nilai1,0) }}</td>
                    @endforeach
                    @foreach ($aktual[$index] as $nilai)
                    <td>{{ number_format($nilai,0) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
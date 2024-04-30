@extends('layouts.dashboard')
@section('content')
<!-- DataTales Example -->
<div class="col-md-12">
    <h1 class="text-center">Grafik SPK Penilaian Dosen</h1>
    <div class="col-md-12">
        <div id="chart"></div>
    </div>
</div>


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Penilaian Dosen</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered sortable" id="" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>WSM</th>
                    <th>WPM</th>
                    <th>WASPAS</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
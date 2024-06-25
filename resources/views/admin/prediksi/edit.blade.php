@extends('layouts.dashboard')
@section('content')
@if(count($errors)>0)
@foreach($errors->all() as $error)
<div class="alert alert-danger" role="alert">
    {{ $error }}
</div>
@endforeach
@endif

@if(Session::has('success'))
<div class="alert alert-success" role="alert">
    {{ Session('success') }}
</div>

@endif
<div class="col-lg-12 order-lg-1">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Mapel</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('prediksi.update' , $mapel->id ) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Tebu Masuk</label>
                    <input type="text" step="0.01" class="form-control" name="C1" value="{{ $mapel->C1 }}">
                </div>
                <div class=" form-group">
                    <label>Rendemen Tebu</label>
                    <input type="text" step="0.01" class="form-control" name="C2" value="{{ $mapel->C2 }}">
                </div>
                <div class=" form-group">
                    <label>Luas Lahan</label>
                    <input type="text" step="0.01" class="form-control" name="C3" value="{{ $mapel->C3 }}">
                </div>
                <div class=" form-group">
                    <label>Produktivitas</label>
                    <input type="text" step="0.01" class="form-control" name="C4" value="{{ $mapel->C4 }}">
                </div>
                <div class=" form-group">
                    <label>Produksi Gula</label>
                    <input type="text" step="0.01" class="form-control" name="C5" value="{{ $mapel->C5 }}">
                </div>

                <div class=" form-group">
                    <button class="btn btn-primary btn-block">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
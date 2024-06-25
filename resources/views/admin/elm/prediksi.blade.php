@extends('layouts.dashboard')
@section('content')
<div class="card shadow mb-4">
    <div class="card">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Parameter</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('parameter') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label for="input" class="form-label">Input:</label>
                        <input type="number" id="input" name="input" class="form-control" value="2" required><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="training_percentage" class="form-label">Persentase Training:</label><br>
                        <select id="training_percentage" name="training_percentage" class="form-select custom-select-full-width">
                            <option value="0.1">10%:90%</option>
                            <option value="0.2">20%:80%</option>
                            <option value="0.3">30%:70%</option>
                            <option value="0.4">40%:60%</option>
                            <option value="0.5">50%:50%</option>
                            <option value="0.6">60%:40%</option>
                            <option value="0.7" selected>70%:30%</option>
                            <option value="0.8">80%:20%</option>
                            <option value="0.9">90%:10%</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block">Bagi Data</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <p>Jumlah Data Testing: {{ $trainingCount }}</p>
                    <p>Jumlah Data Testing: {{ $testingCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.elm.inputWeight')
<style>
    .custom-select-full-width {
        width: 100%;
    }
</style>
@endsection
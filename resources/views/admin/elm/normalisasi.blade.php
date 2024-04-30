@extends('layouts.dashboard')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Max</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th style="color: navy;">C1</th>
                    <th style="color: navy;">C2</th>
                    <th style="color: navy;">C3</th>
                    <th style="color: navy;">C4</th>
                    <th style="color: navy;">C5</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                <td>{{ number_format($C1max['mapel'], 1) }}</td>
                <td>{{ number_format($C2max['mapel'], 1) }}</td>
                <td>{{ number_format($C3max['mapel'], 1) }}</td>
                <td>{{ number_format($C4max['mapel'], 1) }}</td>
                <td>{{ number_format($C5max['mapel'], 1) }}</td>
                </tr>
            </tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Min</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th style="color: navy;">C1</th>
                    <th style="color: navy;">C2</th>
                    <th style="color: navy;">C3</th>
                    <th style="color: navy;">C4</th>
                    <th style="color: navy;">C5</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                <td>{{ number_format($C1min['mapel'], 1) }}</td>
                <td>{{ number_format($C2min['mapel'], 1) }}</td>
                <td>{{ number_format($C3min['mapel'], 1) }}</td>
                <td>{{ number_format($C4min['mapel'], 1) }}</td>
                <td>{{ number_format($C5min['mapel'], 1) }}</td>
                </tr>
            </tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Normalisasi</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th style="color: navy;">No</th>
                    <th style="color: navy;">C1</th>
                    <th style="color: navy;">C2</th>
                    <th style="color: navy;">C3</th>
                    <th style="color: navy;">C4</th>
                    <th style="color: navy;">C5</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($normalizedData as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ number_format($item['C1'], 3) }}</td>
                    <td>{{ number_format($item['C2'], 3) }}</td>
                    <td>{{ number_format($item['C3'], 3) }}</td>
                    <td>{{ number_format($item['C4'], 3) }}</td>
                    <td>{{ number_format($item['C5'], 3) }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection
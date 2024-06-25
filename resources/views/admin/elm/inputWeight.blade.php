<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Input Weight</h5>
    </div>
    <div class="card-body">
        @if(isset($bobotMatrix))
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th style="color: navy;">W1</th>
                    <th style="color: navy;">W2</th>
                    <th style="color: navy;">W3</th>
                    <th style="color: navy;">W4</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bobotMatrix as $row)
                <tr>
                    @foreach ($row as $value)
                    <td>{{ $value }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Input Bias</h5>
    </div>
    <div class="card-body">
        @if(isset($bobotBias))
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th style="color: navy;">W1</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bobotBias as $row)
                <tr>
                    <td>{{ $row }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Transpose Input Weight</h5>
    </div>
    <div class="card-body">
        @if(isset($transposedMatrix))
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <tbody>
                @foreach ($transposedMatrix as $row)
                <tr>
                    @foreach ($row as $value)
                    <td>{{ $value }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
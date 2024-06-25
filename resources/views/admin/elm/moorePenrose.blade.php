<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Transpose Hidden Layer</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <tbody>
                @foreach ($matriksTranspose as $baris)
                <tr>
                    @foreach ($baris as $nilai)
                    <td>{{ number_format($nilai,3) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Perkalian Transpose Matriks Hidden Layer</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <tbody>
                @foreach ($hasilKali as $baris)
                <tr>
                    @foreach ($baris as $nilai)
                    <td>{{ number_format($nilai,3) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Invers Matriks</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <tbody>
                @foreach ($inverseMatriks as $baris)
                <tr>
                    @foreach ($baris as $nilai)
                    <td>{{ number_format($nilai,3) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Moore-Penrose Pseudo Invers</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <tbody>
                @foreach ($hDagger as $baris)
                <tr>
                    @foreach ($baris as $nilai)
                    <td>{{ number_format($nilai,3) }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Output Weight</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <tbody>
                @foreach ($outputWeight as $baris)
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
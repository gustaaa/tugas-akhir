<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Fungsi Aktivasi</title>
</head>

<body>
    <h1>Hasil Fungsi Aktivasi</h1>

    @if(is_string($datafungsiAktivasi))
    <p>{{ $datafungsiAktivasi }}</p>
    @else
    <table border="1">
        @foreach ($datafungsiAktivasi as $baris)
        <tr>
            @foreach ($baris as $nilai)
            <td>{{ $nilai }}</td>
            @endforeach
        </tr>
        @endforeach
    </table>
    @endif
</body>

</html>
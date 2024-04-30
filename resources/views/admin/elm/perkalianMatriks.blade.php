<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perkalian Matriks</title>
</head>

<body>
    <h1>Hasil Perkalian Matriks</h1>

    @if(is_string($hasilMatriks))
    <p>{{ $hasilMatriks }}</p>
    @else
    <table border="1">
        @foreach ($hasilMatriks as $baris)
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
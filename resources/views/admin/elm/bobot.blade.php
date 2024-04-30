<!-- resources/views/admin/elm/normalisasi.blade.php -->
@extends('layouts.dashboard')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normalisasi</title>
</head>

<body>
    <h1>Normalisasi</h1>

    <form method="POST" action="{{ route('bobotMatriks') }}">
        @csrf
        <label for="input">Input:</label>
        <input type="number" id="input" name="input" required>
        <button type="submit">Generate</button>
    </form>

    @if(isset($bobotMatrix))
    <p>Matrix Bobot Acak:</p>
    <table border="1">
        @foreach ($bobotMatrix as $row)
        <tr>
            @foreach ($row as $value)
            <td>{{ $value }}</td>
            @endforeach
        </tr>
        @endforeach
    </table>
    @endif
    @if(isset($transposedMatrix))
    <p>Transpose Matrix Bobot Acak:</p>
    <table border="1">
        @foreach ($transposedMatrix as $row)
        <tr>
            @foreach ($row as $value)
            <td>{{ $value }}</td>
            @endforeach
        </tr>
        @endforeach
    </table>
    @endif
    @if(isset($resultMatrix))
    <p>Perkalian:</p>
    <table border="1">
        @foreach ($resultRow as $row)
        <tr>
            @foreach ($row as $element)
            <td>{{ $element }}</td>
            @endforeach
        </tr>
        @endforeach
    </table>
    @endif
</body>

</html>
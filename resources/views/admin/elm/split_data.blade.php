<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pembagian Data Training dan Testing</title>
</head>

<body>
    <h2>Masukkan Persentase Pembagian Data Training</h2>
    <form action="{{ route('splitDataAndView') }}" method="post">
        @csrf
        <label for="training_percentage">Persentase Training:</label><br>
        <select id="training_percentage" name="training_percentage">
            <option value="0.5">50%</option>
            <option value="0.6">60%</option>
            <option value="0.7">70%</option>
            <option value="0.8" selected>80%</option>
            <option value="0.9">90%</option>
        </select>
        <button type="submit">Bagi Data</button>
    </form>

    <h2>Data Training</h2>
    <table border="1">
        <thead>
            <tr>
                <th>C1</th>
                <th>C2</th>
                <th>C3</th>
                <th>C4</th>

            </tr>
        </thead>
        <tbody>
            @if(isset($trainingData))
            @foreach ($trainingData as $item)
            <tr>
                <td>{{ number_format($item['C1'], 3) }}</td>
                <td>{{ number_format($item['C2'], 3) }}</td>
                <td>{{ number_format($item['C3'], 3) }}</td>
                <td>{{ number_format($item['C4'], 3) }}</td>

            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

    <h2>Data Testing</h2>
    <table border="1">
        <thead>
            <tr>
                <th>C1</th>
                <th>C2</th>
                <th>C3</th>
                <th>C4</th>

            </tr>
        </thead>
        <tbody>
            @if(isset($trainingData))
            @foreach ($testingData as $item)
            <tr>
                <td>{{ number_format($item['C1'], 3) }}</td>
                <td>{{ number_format($item['C2'], 3) }}</td>
                <td>{{ number_format($item['C3'], 3) }}</td>
                <td>{{ number_format($item['C4'], 3) }}</td>

            </tr>

            @endforeach
            @endif
        </tbody>
    </table>
</body>

</html>
@extends('layouts.dashboard')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">MAPE</h5>
    </div>
    <div class="card-body">

        <table class="table table-bordered" id="" width="100%" cellspacing="0">
            <tbody>
                <tr>
                    <td>{{ number_format($mape ,2) }} %</td>
                </tr>
            </tbody>
        </table>
        <div class="section-header d-flex">
            <div class="flex-fill">
                <figure class="highcharts-figure ml-5">
                    <div id="barChart"></div>
                </figure>
                <script src="https://code.highcharts.com/highcharts.js"></script>
                <script>
                    // Data retrieved https://en.wikipedia.org/wiki/List_of_cities_by_average_temperature
                    var aktual = <?php echo json_encode($matriksA) ?>;
                    var denormalisasi = <?php echo json_encode($matriksB) ?>;
                    Highcharts.chart('barChart', {
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: 'Grafik Hasil Prediksi dan Data Aktual'
                        },
                        yAxis: {
                            title: {
                                text: ''
                            }
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: true
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: [{
                            name: 'Data Aktual',
                            data: aktual
                        }, {
                            name: 'Data Prediksi',
                            data: denormalisasi
                        }, ]
                    });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
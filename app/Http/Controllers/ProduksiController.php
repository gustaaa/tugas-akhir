<?php

namespace App\Http\Controllers;

use App\Models\alternatif;
use App\Models\Bobot;
use App\Models\TrainingData;
use App\Models\TestingData;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function normalisasi(Request $request)
    {
        $minC1 = alternatif::min('C1');
        $maxC1 = alternatif::max('C1');
        $minC2 = alternatif::min('C2');
        $maxC2 = alternatif::max('C2');
        $minC3 = alternatif::min('C3');
        $maxC3 = alternatif::max('C3');
        $minC4 = alternatif::min('C4');
        $maxC4 = alternatif::max('C4');
        $minC5 = alternatif::min('C5');
        $maxC5 = alternatif::max('C5');

        $C1min = [
            'mapel' => $minC1,
        ];
        $C1max = [
            'mapel' => $maxC1,
        ];
        $C2min = [
            'mapel' => $minC2,
        ];
        $C2max = [
            'mapel' => $maxC2,
        ];
        $C3min = [
            'mapel' => $minC3,
        ];
        $C3max = [
            'mapel' => $maxC3,
        ];
        $C4min = [
            'mapel' => $minC4,
        ];
        $C4max = [
            'mapel' => $maxC4,
        ];
        $C5min = [
            'mapel' => $minC5,
        ];
        $C5max = [
            'mapel' => $maxC5,
        ];

        $data = alternatif::orderby('id', 'asc')->get();

        $normalisasiData = [];

        // Normalisasi setiap fitur dalam setiap data alternatif
        foreach ($data as $item) {
            $normalizedItem = [
                'C1' => ($item->C1 - $minC1) / ($maxC1 - $minC1),
                'C2' => ($item->C2 - $minC2) / ($maxC2 - $minC2),
                'C3' => ($item->C3 - $minC3) / ($maxC3 - $minC3),
                'C4' => ($item->C4 - $minC4) / ($maxC4 - $minC4),
            ];

            // Menyimpan hasil normalisasi
            $normalisasiData[] = $normalizedItem;
        }
        // Inisialisasi array untuk menyimpan hasil normalisasi
        $normalizedData = [];

        // Normalisasi setiap fitur dalam setiap data alternatif
        foreach ($data as $item) {
            $normalizedItem = [
                'C1' => ($item->C1 - $minC1) / ($maxC1 - $minC1),
                'C2' => ($item->C2 - $minC2) / ($maxC2 - $minC2),
                'C3' => ($item->C3 - $minC3) / ($maxC3 - $minC3),
                'C4' => ($item->C4 - $minC4) / ($maxC4 - $minC4),
                'C5' => ($item->C5 - $minC5) / ($maxC5 - $minC5),
            ];

            // Menyimpan hasil normalisasi
            $normalizedData[] = $normalizedItem;
        }
        return view('admin.elm.normalisasi', compact(
            'data',
            'C1min',
            'C1max',
            'C2min',
            'C2max',
            'C3min',
            'C3max',
            'C4min',
            'C4max',
            'C5min',
            'C5max',
            'normalisasiData',
            'normalizedData'
        ));
    }

    public function bobotMatriks(Request $request)
    {
        // Periksa apakah ada input
        $input = $request->input('input');

        // Jika ada input, lakukan pembuatan data baru dan simpan ke dalam tabel bobot
        if ($input !== null && $input !== false) {
            $columnCount = count(alternatif::first()->getFillable()) - 1;

            // Menghitung bobot input
            $bobotMatrix = [];
            for ($i = 0; $i < $input; $i++) {
                $row = [];
                for ($j = 0; $j < $columnCount; $j++) {
                    $row[] = mt_rand(0, 100) / 100;
                }
                $bobotMatrix[] = $row;
            }

            // Simpan matriks bobot ke dalam database
            $bobot = new Bobot();
            $bobot->matrix = $bobotMatrix;
            $bobot->save();

            // Menghitung transpose bobot input
            $transposedMatrix = $this->transposeMatrix($bobotMatrix);
        } else {
            // Jika tidak ada input, ambil data terakhir dari tabel bobot
            $latestBobot = Bobot::latest()->first();

            if ($latestBobot) {
                // Ambil matriks bobot dari data terakhir dalam tabel bobot
                $bobotMatrix = $latestBobot->matrix;
                // Menghitung transpose bobot input
                $transposedMatrix = $this->transposeMatrix($bobotMatrix);
            } else {
                // Jika tidak ada data bobot tersimpan, beri nilai default pada kedua matriks
                $bobotMatrix = [];
                $transposedMatrix = [];
            }
        }

        // Load view dan kirimkan data bobot ke view
        return view('admin.elm.bobot', compact('bobotMatrix', 'transposedMatrix'));
    }

    private function transposeMatrix($matrix)
    {
        $transposedMatrix = [];
        foreach ($matrix as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $transposedMatrix[$colIndex][$rowIndex] = $value;
            }
        }
        return $transposedMatrix;
    }

    public function splitDataAndView(Request $request)
    {
        // Lakukan normalisasi pada data
        $normalisasiData = $this->normalisasi($request)->getData()['normalisasiData'];

        // Melakukan pembagian data training dan testing
        $totalData = count($normalisasiData);
        $trainingPercentage = $request->input('training_percentage');

        if ($trainingPercentage !== null && $trainingPercentage !== false) {
            // Jika ada input, lakukan pembagian data training dan testing
            $trainingCount = (int) ($totalData * $trainingPercentage);
            $testingCount = $totalData - $trainingCount;

            // Pisahkan data menjadi data training dan data testing
            $trainingData = array_slice($normalisasiData, 0, $trainingCount);
            $testingData = array_slice($normalisasiData, $trainingCount);

            // Simpan data training dan data testing ke dalam database
            $this->storeTrainingData($trainingData);
            $this->storeTestingData($testingData);
        } else {
            // Jika tidak ada input, ambil data terakhir dari tabel training dan testing
            $latestTraining = TrainingData::latest()->first();
            $latestTesting = TestingData::latest()->first();

            if ($latestTraining && $latestTesting) {
                // Ambil data training dan testing dari database
                $trainingData = json_decode($latestTraining->data, true);
                $testingData = json_decode($latestTesting->data, true);
            } else {
                // Jika tidak ada data training dan testing tersimpan, inisialisasi dengan array kosong
                $trainingData = [];
                $testingData = [];
            }
        }

        // Mengirim data training dan testing ke view
        return view('admin.elm.split_data', compact('trainingData', 'testingData'));
    }

    private function storeTrainingData($trainingData)
    {
        // Hapus kunci kolom dari setiap array
        $trainingDataWithoutKeys = array_map('array_values', $trainingData);

        // Simpan data training ke dalam tabel data_training
        TrainingData::create(['data' => json_encode($trainingDataWithoutKeys)]);
    }

    private function storeTestingData($testingData)
    {
        // Hapus kunci kolom dari setiap array
        $testingDataWithoutKeys = array_map('array_values', $testingData);

        // Simpan data testing ke dalam tabel data_testing
        TestingData::create(['data' => json_encode($testingDataWithoutKeys)]);
    }

    public function perkalian_matriks(Request $request)
    {

        $matriksA = $this->splitDataAndView($request)->getData()['trainingData'];

        // Matriks B
        $matriksB = $this->bobotMatriks($request)->getData()['transposedMatrix'];

        // Inisialisasi matriks hasil
        $hasilMatriks = [];

        // Perkalian matriks
        for ($i = 0; $i < count($matriksA); $i++) {
            for ($j = 0; $j < count($matriksB[0]); $j++) {
                $hasil = 0;
                for ($k = 0; $k < count($matriksA[0]); $k++) {
                    $hasil += $matriksA[$i][$k] * $matriksB[$k][$j];
                }
                $hasilMatriks[$i][$j] = $hasil;
            }
        }
        // Output hasil matriks
        return view('admin.elm.perkalianMatriks', compact('hasilMatriks'));
    }
    public function fungsiAktivasi(Request $request)
    {
        $data = $this->perkalian_matriks($request)->getData()['hasilMatriks'];
        for ($i = 0; $i < sizeof($data); $i++) {
            for ($j = 0; $j < sizeof($data[0]); $j++) {
                $datafungsiAktivasi[$i][$j] = round(1 / (1 + exp(-$data[$i][$j])), 6);
            }
        }
        return view('admin.elm.fungsiAktivasi', compact('datafungsiAktivasi'));
    }
    public function moorePenroseInverse(Request $request)
    {
        $data = $this->fungsiAktivasi($request)->getData()['datafungsiAktivasi'];
        // Mendapatkan jumlah baris dan kolom
        $rowCount = count($data);
        $columnCount = count($data[0]);

        // Membuat matriks transpos
        $transposedMatrix = [];
        for ($i = 0; $i < $columnCount; $i++) {
            $row = [];
            for ($j = 0; $j < $rowCount; $j++) {
                $row[] = $data[$j][$i];
            }
            $transposedMatrix[] = $row;
        }

        // Inisialisasi matriks hasil
        $hasilMatriks = [];

        // Perkalian matriks
        for ($i = 0; $i < count($data); $i++) {
            for ($j = 0; $j < count($transposedMatrix[0]); $j++) {
                $hasil = 0;
                for ($k = 0; $k < count($data[0]); $k++) {
                    $hasil += $data[$i][$k] * $transposedMatrix[$k][$j];
                }
                $hasilMatriks[$i][$j] = $hasil;
            }
        }
        // $inverseMatrix = $this->inverseMatrix($hasilMatriks);

        return view('admin.elm.moorePenrose', compact('hasilMatriks'));
    }


    private function determinant($hasilMatriks)
    {
        $n = count($hasilMatriks);

        if ($n == 1) {
            return $hasilMatriks[0][0];
        }

        $det = 0;
        $sign = 1;

        for ($i = 0; $i < $n; $i++) {
            $minor = $this->getMinorMatrix($hasilMatriks, 0, $i);
            $det += $sign * $hasilMatriks[0][$i] * $this->determinant($minor);
            $sign = -$sign;
        }

        return $det;
    }

    private function inverseMatrix($matrix)
    {
        $determinant = $this->determinant($matrix);

        if ($determinant == 0) {
            throw new Exception("Matriks tidak memiliki invers karena determinan nol.");
        }

        $n = count($matrix);
        $inverseMatrix = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $minor = $this->getMinorMatrix($matrix, $i, $j);
                $cofactor = pow(-1, $i + $j) * $this->determinant($minor);
                $inverseMatrix[$j][$i] = $cofactor / $determinant;
            }
        }

        return $inverseMatrix;
    }

    private function getMinorMatrix($matrix, $row, $col)
    {
        $minor = [];
        $n = count($matrix);

        for ($i = 0; $i < $n; $i++) {
            if ($i == $row) continue;
            $temp = [];
            for ($j = 0; $j < $n; $j++) {
                if ($j == $col) continue;
                $temp[] = $matrix[$i][$j];
            }
            $minor[] = $temp;
        }

        return $minor;
    }
}

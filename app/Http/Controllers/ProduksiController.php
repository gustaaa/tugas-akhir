<?php

namespace App\Http\Controllers;

use App\Models\alternatif;
use App\Models\Bobot;
use App\Models\Bias;
use App\Models\Predict;
use App\Models\TrainingData;
use App\Models\TargetData;
use App\Models\TestingData;
use App\Models\PrediksiData;
use App\Models\AktualData;
use Illuminate\Http\Request;
use Phpml\Math\Matrix;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $matriksA = $this->parameter($request)->getData()['aktualData'];
        $matriksB = $this->denormalisasi($request)->getData()['denormalisasi'];
        $data = alternatif::orderby('id', 'asc')->get();
        $total_c1 = 0;
        $total_c2 = 0;
        $total_c3 = 0;
        $total_c4 = 0;
        $total_c5 = 0;

        foreach ($data as $item) {
            $total_c1 += $item->C1;
            $total_c2 += $item->C2;
            $total_c3 += $item->C3;
            $total_c4 += $item->C4;
            $total_c5 += $item->C5;
        }
        return view('home', compact(
            'total_c1',
            'total_c2',
            'total_c3',
            'total_c4',
            'total_c5',
            'matriksA',
            'matriksB'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.prediksi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'C1' => 'required',
            'C2' => 'required',
            'C3' => 'required',
            'C4' => 'required',
            'C5' => 'required',


        ]);

        $mapel = alternatif::create([

            'C1' => $request->C1,
            'C2' => $request->C2,
            'C3' => $request->C3,
            'C4' => $request->C4,
            'C5' => $request->C5,
        ]);

        return redirect()->route('prediksi.index')->with('success', 'Data berhasil disimpan');
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
        $mapel = alternatif::findorfail($id);
        return view('admin.prediksi.edit', compact('mapel'));
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
        $this->validate($request, [
            'C1' => 'required',
            'C2' => 'required',
            'C3' => 'required',
            'C4' => 'required',
            'C5' => 'required',
        ]);
        $mapel = [
            'C1' => $request->C1,
            'C2' => $request->C2,
            'C3' => $request->C3,
            'C4' => $request->C4,
            'C5' => $request->C5,
        ];

        alternatif::whereId($id)->update($mapel);

        return redirect()->route('prediksi.index')->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function parameter(Request $request)
    {
        // Lakukan normalisasi pada data
        $normalisasiData = $this->normalisasi($request)->getData()['normalisasiData'];
        $normalisasiTarget = $this->normalisasi($request)->getData()['targetData'];
        $aktualData = $this->normalisasi($request)->getData()['aktualData'];
        // Melakukan pembagian data training dan testing
        $totalData = count($normalisasiData);
        $trainingPercentage = $request->input('training_percentage');
        $trainingCount = (int) ($totalData * $trainingPercentage);
        $testingCount = $totalData - $trainingCount;
        // Periksa apakah ada input
        $input = $request->input('input');

        // Jika ada input, lakukan pembuatan data baru dan simpan ke dalam tabel bobot
        if ($input !== null && $input !== false) {
            $columnCount = count(alternatif::first()->getFillable()) - 1;

            // Menghitung bobot input
            $bobotMatrix = [];
            $bobotBias = [];
            for ($i = 0; $i < $input; $i++) {
                $row = [];
                for ($j = 0; $j < $columnCount; $j++) {
                    $row[] = mt_rand(-100, 100) / 100;
                }
                $bobotMatrix[] = $row;
                // Inisialisasi bobot bias untuk setiap input
                $bobotBias[] = mt_rand(-100, 0) / 100;
            }
            // Simpan matriks bobot ke dalam database
            $bobot = new Bobot();
            $bobot->matrix = $bobotMatrix;
            $bobot->save();

            // Simpan bobot bias ke dalam database
            $bias = new Bias();
            $bias->data = $bobotBias;
            $bias->save();
            // Menghitung transpose bobot input
            $transposedMatrix = $this->transposeMatrix($bobotMatrix);
        } else {
            // Jika tidak ada input, ambil data terakhir dari tabel bobot
            $latestBobot = Bobot::latest()->first();
            $latestBias = Bias::latest()->first();

            if ($latestBobot && $latestBias) {
                // Ambil matriks bobot dari data terakhir dalam tabel bobot
                $bobotMatrix = $latestBobot->matrix;
                $bobotBias = $latestBias->data;
                // Menghitung transpose bobot input
                $transposedMatrix = $this->transposeMatrix($bobotMatrix);
            } else {
                // Jika tidak ada data bobot tersimpan, beri nilai default pada kedua matriks
                $bobotMatrix = [];
                $transposedMatrix = [];
                $bobotBias = [];
            }
        }

        if ($trainingPercentage !== null && $trainingPercentage !== false) {
            // Jika ada input, lakukan pembagian data training dan testing
            $trainingCount = (int) ($totalData * $trainingPercentage);
            $testingCount = $totalData - $trainingCount;

            // Pisahkan data menjadi data training dan data testing
            $trainingData = array_slice($normalisasiData, 0, $trainingCount);
            $testingData = array_slice($normalisasiData, $trainingCount);
            $targetData = array_slice($normalisasiTarget, 0, $trainingCount);
            $aktualData = array_slice($aktualData, $trainingCount);

            // Simpan data training dan data testing ke dalam database
            $this->storeTrainingData($trainingData);
            $this->storeTestingData($testingData);
            $this->storeTargetData($targetData);
            $this->storeAktualData($aktualData);
        } else {
            // Jika tidak ada input, ambil data terakhir dari tabel training dan testing
            $latestTraining = TrainingData::latest()->first();
            $latestTesting = TestingData::latest()->first();
            $latestTarget = TargetData::latest()->first();
            $latestAktual = AktualData::latest()->first();

            if ($latestTraining && $latestTesting && $latestTarget && $latestAktual) {
                // Ambil data training dan testing dari database
                $trainingData = json_decode($latestTraining->data, true);
                $testingData = json_decode($latestTesting->data, true);
                $targetData = json_decode($latestTarget->data, true);
                $aktualData = json_decode($latestAktual->data, true);
            } else {
                // Jika tidak ada data training dan testing tersimpan, inisialisasi dengan array kosong
                $trainingData = [];
                $testingData = [];
                $targetData = [];
                $aktualData = [];
            }
        }
        return view('admin.elm.prediksi', compact(
            'bobotMatrix',
            'bobotBias',
            'transposedMatrix',
            'trainingData',
            'testingData',
            'targetData',
            'aktualData',
            'trainingCount',
            'testingCount',
        ));
    }

    public function training(Request $request)
    {
        $bobotMatrix = $this->parameter($request)->getData()['bobotMatrix'];
        $bobotBias = $this->parameter($request)->getData()['bobotBias'];
        $transposedMatrix = $this->parameter($request)->getData()['transposedMatrix'];
        $matriksHasil = $this->perkalian_matriks($request)->getData()['matriksHasil'];
        $datafungsiAktivasi = $this->fungsiAktivasi($request)->getData()['datafungsiAktivasi'];
        $hDagger = $this->moorePenroseInverse($request)->getData()['hDagger'];
        $hasilKali = $this->moorePenroseInverse($request)->getData()['hasilKali'];
        $matriksTranspose = $this->moorePenroseInverse($request)->getData()['matriksTranspose'];
        $inverseMatriks = $this->moorePenroseInverse($request)->getData()['inverseMatriks'];
        $outputWeight = $this->outputWeight($request)->getData()['outputWeight'];
        return view('admin.elm.training', compact(
            'bobotMatrix',
            'bobotBias',
            'transposedMatrix',
            'matriksHasil',
            'datafungsiAktivasi',
            'hDagger',
            'outputWeight',
            'hasilKali',
            'matriksTranspose',
            'inverseMatriks'
        ));
    }
    public function testing(Request $request)
    {
        $bobotMatrix = $this->parameter($request)->getData()['bobotMatrix'];
        $transposedMatrix = $this->parameter($request)->getData()['transposedMatrix'];
        $outputWeight = $this->outputWeight($request)->getData()['outputWeight'];
        $outputLayer = $this->outputLayer($request)->getData()['outputLayer'];
        return view('admin.elm.testing', compact(
            'bobotMatrix',
            'transposedMatrix',
            'outputWeight',
            'outputLayer'
        ));
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
        // Inisialisasi array untuk menyimpan hasil normalisasi
        $targetData = [];

        // Normalisasi setiap fitur dalam setiap data alternatif
        foreach ($data as $item) {
            $targetItem = [
                'C5' => ($item->C5 - $minC5) / ($maxC5 - $minC5),
            ];

            // Menyimpan hasil target
            $targetData[] = $targetItem;
        }
        // Inisialisasi array untuk menyimpan hasil normalisasi
        $aktualData = [];

        // Normalisasi setiap fitur dalam setiap data alternatif
        foreach ($data as $item) {
            $aktualItem = [
                'C5' => ($item->C5),
            ];
            // Menyimpan hasil target
            $aktualData[] = $aktualItem;
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
            'normalizedData',
            'targetData',
            'aktualData'
        ));
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

    private function storeTargetData($targetData)
    {
        // Hapus kunci kolom dari setiap array
        $targetDataWithoutKeys = array_map('array_values', $targetData);

        // Simpan data training ke dalam tabel data_target
        TargetData::create(['data' => json_encode($targetDataWithoutKeys)]);
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
    private function storeAktualData($aktualData)
    {
        // Hapus kunci kolom dari setiap array
        $aktualWithoutKeys = array_map('array_values', $aktualData);

        // Simpan data training ke dalam tabel data_target
        AktualData::create(['data' => json_encode($aktualWithoutKeys)]);
    }

    public function perkalian_matriks(Request $request)
    {
        $matriksA = $this->parameter($request)->getData()['trainingData'];
        $matriksB = $this->parameter($request)->getData()['transposedMatrix'];
        $matriksC = $this->parameter($request)->getData()['bobotBias'];
        // Inisialisasi matriks hasil
        $matriksHasil = [];

        // Perkalian matriks
        if (!empty($matriksA)) {
            for ($i = 0; $i < count($matriksA); $i++) {
                for ($j = 0; $j < count($matriksB[0]); $j++) {
                    $hasil = 0;
                    for ($k = 0; $k < count($matriksA[0]); $k++) {
                        $hasil += $matriksA[$i][$k] * $matriksB[$k][$j];
                    }
                    $hasil += $matriksC[$j];
                    $matriksHasil[$i][$j] = $hasil;
                }
            }
        } else {
            $matriksHasil = [];
        }
        // Output hasil matriks
        return view('admin.elm.perkalianMatriks', compact('matriksHasil'));
    }

    public function fungsiAktivasi(Request $request)
    {
        $data = $this->perkalian_matriks($request)->getData()['matriksHasil'];
        if (!empty($data)) {
            for ($i = 0; $i < sizeof($data); $i++) {
                for ($j = 0; $j < sizeof($data[0]); $j++) {
                    $datafungsiAktivasi[$i][$j] = (1 / (1 + exp(-$data[$i][$j])));
                }
            }
        } else {
            $datafungsiAktivasi = [];
        }
        return view('admin.elm.fungsiAktivasi', compact('datafungsiAktivasi'));
    }

    public function moorePenroseInverse(Request $request)
    {
        $data = $this->fungsiAktivasi($request)->getData()['datafungsiAktivasi'];
        // Membuat matriks transpos
        $matriksTranspose = [];
        if (!empty($data)) {
            // Mendapatkan jumlah baris dan kolom
            $columnCount = count($data[0]);
            $matriksTranspose = $this->transposeMatrix($data);
            // Inisialisasi matriks hasil
            $hasilKali = [];
            for ($i = 0; $i < $columnCount; $i++) {
                for ($j = 0; $j < $columnCount; $j++) {
                    $perkalian = 0;
                    for ($k = 0; $k < count($matriksTranspose); $k++) {
                        $perkalian += $matriksTranspose[$k][$i] * $matriksTranspose[$k][$j];
                    }
                    $hasilKali[$i][$j] = $perkalian; // round to 3 decimal places
                }
            }
            // Calculate the inverse
            $inverseMatriks = [];
            $inverseMatriks = $this->inverseMatrix($hasilKali);
            $hDagger = [];

            // Matrix multiplication
            for ($i = 0; $i < count($inverseMatriks); $i++) {
                for ($j = 0; $j < count($matriksTranspose[0]); $j++) {
                    $sum = 0;
                    for ($k = 0; $k < count($inverseMatriks[0]); $k++) {
                        $sum += $inverseMatriks[$i][$k] * $matriksTranspose[$k][$j];
                    }
                    $hDagger[$i][$j] = $sum;
                }
            }
        } else {
            $matriksTranspose = [];
            $hasilKali = [];
            $inverseMatriks = [];
            $hDagger = [];
        }
        //return response()->json(['det' => $hasilKali]);
        return view('admin.elm.moorePenrose', compact('hDagger', 'inverseMatriks', 'matriksTranspose', 'hasilKali'));
    }

    // Function to calculate the inverse of a matrix
    public function inverseMatrix(array $hasilKali)
    {
        $size = count($hasilKali);

        // Initialize the identity hasilKali
        $identityMatrix = [];
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $identityMatrix[$i][$j] = ($i == $j) ? 1 : 0;
            }
        }

        // Perform Gauss-Jordan elimination
        for ($i = 0; $i < $size; $i++) {
            // Find pivot row
            $pivotRow = $i;
            for ($j = $i + 1; $j < $size; $j++) {
                if (abs($hasilKali[$j][$i]) > abs($hasilKali[$pivotRow][$i])) {
                    $pivotRow = $j;
                }
            }
            // Swap rows if needed
            if ($pivotRow != $i) {
                list($hasilKali[$i], $hasilKali[$pivotRow]) = array($hasilKali[$pivotRow], $hasilKali[$i]);
                list($identityMatrix[$i], $identityMatrix[$pivotRow]) = array($identityMatrix[$pivotRow], $identityMatrix[$i]);
            }
            // Perform row operations to make the diagonal elements 1
            $pivot = $hasilKali[$i][$i];
            for ($j = 0; $j < $size; $j++) {
                $hasilKali[$i][$j] /= $pivot;
                $identityMatrix[$i][$j] /= $pivot;
            }
            // Perform row operations to make the other elements in the column 0
            for ($j = 0; $j < $size; $j++) {
                if ($j != $i) {
                    $factor = $hasilKali[$j][$i];
                    for ($k = 0; $k < $size; $k++) {
                        $hasilKali[$j][$k] -= $factor * $hasilKali[$i][$k];
                        $identityMatrix[$j][$k] -= $factor * $identityMatrix[$i][$k];
                    }
                }
            }
        }
        return $identityMatrix;
    }

    public function outputWeight(Request $request)
    {
        // Get the results from the normalisasi and moorePenroseInverse functions
        $matriksA = $this->moorePenroseInverse($request)->getData()['hDagger'];

        // Matriks B
        $matriksB = $this->parameter($request)->getData()['targetData'];

        // Inisialisasi matriks hasil
        $outputWeight = [];

        // Perkalian matriks
        if (!empty($matriksA)) {
            for ($i = 0; $i < count($matriksA); $i++) {
                for ($j = 0; $j < count($matriksB[0]); $j++) {
                    $hasil = 0;
                    for ($k = 0; $k < count($matriksA[0]); $k++) {
                        $hasil += $matriksA[$i][$k] * $matriksB[$k][$j];
                    }
                    $outputWeight[$i][$j] = $hasil;
                }
            }
        } else {
            $outputWeight = [];
        }
        return view('admin.elm.outputWeight', compact('outputWeight'));
    }
    public function outputLayer(Request $request)
    {

        $matriksA = $this->parameter($request)->getData()['testingData'];

        // Matriks B
        $matriksB = $this->parameter($request)->getData()['transposedMatrix'];

        //Matriks Output Weight
        $matriksO = $this->outputWeight($request)->getData()['outputWeight'];

        $matriksC = $this->parameter($request)->getData()['bobotBias'];
        // Inisialisasi matriks hasil
        $hasilMatriks = [];
        if (!empty($matriksA)) { // Perkalian matriks output hidden layer
            for ($i = 0; $i < count($matriksA); $i++) {
                for ($j = 0; $j < count($matriksB[0]); $j++) {
                    $hasil = 0;
                    for ($k = 0; $k < count($matriksA[0]); $k++) {
                        $hasil += $matriksA[$i][$k] * $matriksB[$k][$j];
                    }
                    $hasil += $matriksC[$j];
                    $hasilMatriks[$i][$j] = $hasil;
                }
            }
            // fungsi aktivasi
            for ($i = 0; $i < sizeof($hasilMatriks); $i++) {
                for ($j = 0; $j < sizeof($hasilMatriks[0]); $j++) {
                    $datafungsiAktivasi[$i][$j] = (1 / (1 + exp(-$hasilMatriks[$i][$j])));
                }
            }
            // Perkalian matriks
            for ($i = 0; $i < count($datafungsiAktivasi); $i++) {
                for ($j = 0; $j < count($matriksO[0]); $j++) {
                    $hasil = 0;
                    for ($k = 0; $k < count($datafungsiAktivasi[0]); $k++) {
                        $hasil += $datafungsiAktivasi[$i][$k] * $matriksO[$k][$j];
                    }
                    $outputLayer[$i][$j] = ($hasil);
                }
            }
        } else {
            $outputLayer = [];
        }

        //return json_encode($outputLayer);
        return view('admin.elm.outputLayer', compact('outputLayer'));
    }

    public function predict(Request $request)
    {
        $data = Predict::orderBy('id', 'asc')->get()->map(function ($item) {
            return [$item->C1, $item->C2, $item->C3, $item->C4];
        });
        // Matriks B
        $matriksB = $this->parameter($request)->getData()['transposedMatrix'];

        //Matriks Output Weight
        $matriksO = $this->outputWeight($request)->getData()['outputWeight'];

        $matriksC = $this->parameter($request)->getData()['bobotBias'];
        // Inisialisasi matriks hasil
        $hasilMatriks = [];
        $datafungsiAktivasi = [];
        $prediksi = [];
        // Perkalian matriks output hidden layer
        if (!empty($data)) { // Periksa apakah data tidak kosong
            for ($i = 0; $i < count($data); $i++) {
                for ($j = 0; $j < count($matriksB[0]); $j++) {
                    $hasil = 0;
                    for ($k = 0; $k < count($data[0]); $k++) {
                        $hasil += $data[$i][$k] * $matriksB[$k][$j];
                    }
                    $hasil += $matriksC[$j];
                    $hasilMatriks[$i][$j] = $hasil;
                }
            }

            // fungsi aktivasi
            for ($i = 0; $i < sizeof($hasilMatriks); $i++) {
                for ($j = 0; $j < sizeof($hasilMatriks[0]); $j++) {
                    $datafungsiAktivasi[$i][$j] = (1 / (1 + exp(-$hasilMatriks[$i][$j])));
                }
            }

            // Perkalian matriks
            for ($i = 0; $i < count($datafungsiAktivasi); $i++) {
                for ($j = 0; $j < count($matriksO[0]); $j++) {
                    $hasil = 0;
                    for ($k = 0; $k < count($datafungsiAktivasi[0]); $k++) {
                        $hasil += $datafungsiAktivasi[$i][$k] * $matriksO[$k][$j];
                    }
                    $prediksi[$i][$j] = $hasil;
                }
            }
        } else {
            // Jika data kosong, atur prediksi menjadi kosong atau tidak ada data
            $prediksi = [];
        }
        return view('admin.elm.predict', compact('data', 'prediksi'));
    }

    public function denormalisasi(Request $request)
    {
        $matriksO = $this->outputLayer($request)->getData()['outputLayer'];
        $aktual = $this->parameter($request)->getData()['aktualData'];
        $minC5 = alternatif::min('C5');
        $maxC5 = alternatif::max('C5');

        if (!empty($matriksO)) {
            for ($i = 0; $i < sizeof($matriksO); $i++) {
                for ($j = 0; $j < sizeof($matriksO[0]); $j++) {
                    $denormalisasi[$i][$j] = (((($matriksO[$i][$j]) * ($maxC5 - $minC5)) + $minC5));
                }
            }
        } else {
            $denormalisasi = [];
        }
        return view('admin.elm.denormalisasi', compact('denormalisasi', 'aktual'));
    }

    public function mape(Request $request)
    {
        $matriksA = $this->parameter($request)->getData()['aktualData'];
        $matriksB = $this->denormalisasi($request)->getData()['denormalisasi'];
        // Menghitung MAPE
        $error_sum = 0;

        if (!empty($matriksA && $matriksB)) {
            for ($i = 0; $i < count($matriksA); $i++) {
                for ($j = 0; $j < count($matriksA[$i]); $j++) {
                    $error_sum += abs(($matriksA[$i][$j] - $matriksB[$i][$j]) / $matriksA[$i][$j]);
                }
            }

            $mape = ($error_sum / count($matriksA)) * 100;
        } else {
            $mape = 0;
            $matriksA = [];
            $matriksB = [];
        }

        return view('admin.elm.mape', compact('mape', 'matriksA', 'matriksB'));
    }
}

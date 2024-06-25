<?php

namespace App\Http\Controllers;

use App\Exports\AlternatifExport;
use App\Imports\DataImport;
use Illuminate\Http\Request;
use App\Models\alternatif;

use Maatwebsite\Excel\Facades\Excel;

class AlternatifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new DataImport, $request->file('file'));

            return redirect()->route('produksi.index')->with('success', 'Data Berhasil di ditambahkan');
        } catch (\Exception $e) {
            $errorMessage = 'Terjadi kesalahan saat mengimpor file.';
            return redirect()->back()->withErrors(['error' => $errorMessage])->withInput()->with('errorAlert', $errorMessage);
        }
    }

    public function clear()
    {
        alternatif::truncate();

        $table = app(alternatif::class)->getTable();
        $statement = "ALTER TABLE $table AUTO_INCREMENT = 1;";
        \DB::statement($statement);

        return response()->json(['message' => 'Data cleared successfully']);
    }

    public function index()
    {
        //

        $mapel = alternatif::orderby('created_at', 'desc')->get();
        $mapel = alternatif::simplePaginate(10);
        return view('admin.produksi.index', compact('mapel'));
    }
    public function export()
    {
        // export data ke excel
        return Excel::download(new AlternatifExport, 'produksi.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.produksi.create');
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

        return redirect()->route('produksi.index')->with('success', 'Data berhasil disimpan');
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
        $mapel = alternatif::findorfail($id);
        return view('admin.produksi.edit', compact('mapel'));
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

        return redirect()->route('produksi.index')->with('success', 'Data Berhasil di Update');
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
        $mapel = alternatif::findorfail($id);
        $mapel->delete();

        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }
}

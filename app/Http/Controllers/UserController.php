<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use PDF;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role == 'Administrator') return $next($request);
            abort(403, 'Anda tidak memiliki cukup hak akses');
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // mengambil data
        $users = DB::table('users')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->select('id', 'name', 'username', 'email', 'role')
            ->paginate(10);
        return view('users.index', compact('users'));
    }
    public function create()
    {
        return view('users.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'gambar' => 'required',
            'role' => 'required',
        ]);
        $image_name = '';
        if ($request->file('gambar')) {
            $image_name = $request->file('gambar')->store('images', 'public');
        }

        $user = new User;
        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->gambar = $image_name;
        $user->role = $request->get('role');

        $user->save();
        return redirect(route('user.index'))->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('users.edit', compact('user'));
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
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        $user = User::find($id);

        if ($request->file('gambar') == '') {
            $user->name = $request->get('name');
            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->role = $request->get('role');
            $user->save();
        } else {
            if ($user->gambar && file_exists(storage_path('app/public/' . $user->gambar))) {
                Storage::delete(['public/' . $user->gambar]);
            }
            $image_name = $request->file('gambar')->store('images', 'public');
            $user->gambar = $image_name;
            $user->name = $request->get('name');
            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->role = $request->get('role');
            $user->save();
        }

        return redirect()->route('user.index')->with('success', 'User Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('user.index')->with('success', 'User Berhasil Dihapus');
    }


    public function export()
    {
        // export data ke excel
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import(Request $request)
    {
        // import excel ke data tables
        // $users = Excel::toCollection(new UsersImport, $request->import_file);
        // foreach ($users[0] as $user) {
        //     User::where('id', $user[0])->update([
        //         'name' => $user[1],
        //         'email' => $user[2],
        //         'password' => $user[3],
        //         'role' => $user[4]
        //     ]);
        // }
        // return redirect()->route('user.index');
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));

            return redirect()->route('user.index')->with('success', 'Data Berhasil di ditambahkan');
        } catch (\Exception $e) {
            $errorMessage = 'Terjadi kesalahan saat mengimpor file.';
            return redirect()->back()->withErrors(['error' => $errorMessage])->withInput()->with('errorAlert', $errorMessage);
        }
    }
    public function EditPassword($id)
    {
        $user = User::find($id);
        return view('User.password-edit', compact('user'));
    }

    public function UpdatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = Auth::user();
        $user = User::find($id);
        $user->password = Hash::make($request->password);
        $user->save();

        Alert::success('Success', 'Password successfully changed!');
        return redirect()->route('user.edit', $user->id);
    }
    public function laporan()
    {
        $user = User::all();
        $pdf = PDF::loadview('users.laporan', compact('user'));
        return $pdf->stream();
    }
}

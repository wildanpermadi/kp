<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AdminRequest;
use App\Models\PermintaanLegalisir;
use App\Http\Requests\AlumniRequest;
use Illuminate\Support\Facades\Hash;

class PagesController extends Controller
{
    public function getIndex()
    {
        if(auth()->user()->role == 'admin') {
            $alumni = DB::table('alumni')->count();
            $permintaan_legalisir = DB::table('permintaan_legalisir')->count();
            $permintaan_legalisir_terbaru = PermintaanLegalisir::where('status', '=', 'proses')->latest()->take(5)->get();
            $riwayat_legalisir = DB::table('legalisir')->count();
            $pelegalisir = DB::table('pelegalisir')->count();

            return view('index', compact('alumni', 'permintaan_legalisir', 'permintaan_legalisir_terbaru', 'riwayat_legalisir', 'pelegalisir'));
        }

        $permintaan_legalisir = PermintaanLegalisir::where('alumni_id', '=', auth()->user()->alumni->id)->get();

        return view('index_alumni', compact('permintaan_legalisir'));
    }

    public function getProfile()
    {
        if(auth()->user()->role == 'admin') {
            return view('profile');
        }

        return view('profile_alumni');
    }

    public function doChangePassword(UserRequest $request)
    {
        if(Hash::check($request->old_password, auth()->user()->password)) {
            auth()->user()->update([
                'password' => bcrypt($request->password)
            ]);

            return redirect()->back()->with('success', 'Data berhasil disimpan');
        }

        return redirect()->back()->with('old-password-error', 'Password lama tidak sama');
    }

    public function updateProfile(AlumniRequest $request_alumni, UserRequest $request_user, AdminRequest $request_admin)
    {
        $foto_name = auth()->user()->foto;

        if($request_user->foto) {
            $path = auth()->user()->role == 'alumni' ? public_path('images/alumni') : public_path('images/admin');

            $foto = $request_user->foto;
            $foto_name = uniqid().'.'.$foto->getClientOriginalExtension();
            
            $foto->move($path, $foto_name);

            if(auth()->user()->foto != 'default.png') {
                if(file_exists($path.'/'.auth()->user()->foto)) {
                    unlink($path.'/'.auth()->user()->foto);
                }
            }
        }

        auth()->user()->update([
            'foto' => $foto_name,
            'username' => $request_user->username
        ]);

        if(auth()->user()->role == 'alumni') {
            auth()->user()->alumni->update($request_alumni->except('_token', '_method', 'username', 'foto'));
        } else {
            auth()->user()->admin->update($request_admin->only('nama'));
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}

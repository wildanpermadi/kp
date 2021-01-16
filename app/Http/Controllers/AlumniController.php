<?php

namespace App\Http\Controllers;

use Excel;
use DataTables;
use App\Models\User;
use App\Models\Alumni;
use Illuminate\Http\Request;
use App\Imports\AlumniImport;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AlumniRequest;

class AlumniController extends Controller
{
    public function index()
    {
        return view('admin.alumni.index');
    }

    public function alumniJson()
    {
        $data = Alumni::query();

        return DataTables::of($data)
                            ->orderColumn('nim', function($query, $order) {
                                $query->orderBy('nim', $order);
                            })
                            ->orderColumn('nama', function($query, $order) {
                                $query->orderBy('nama', $order);
                            })
                            ->orderColumn('prodi', function($query, $order) {
                                $query->orderBy('prodi', $order);
                            })
                            ->orderColumn('tanggal', function($query, $order) {
                                $query->orderBy('created_at', $order);
                            })
                            ->filterColumn('nim', function($query, $key) {
                                $query->where('nim', 'like', '%'.$key.'%');
                            })
                            ->filterColumn('nama', function($query, $key) {
                                $query->where('nama', 'like', '%'.$key.'%');
                            })
                            ->filterColumn('prodi', function($query, $key) {
                                $query->where('prodi', 'like', '%'.$key.'%');
                            })
                            ->addColumn('nim', function(Alumni $a) {
                                return $a->nim;
                            })
                            ->addColumn('nama', function(Alumni $a) {
                                return $a->nama;
                            })
                            ->addColumn('prodi', function(Alumni $a) {
                                return $a->prodi;
                            })
                            ->addColumn('aksi', function(Alumni $a) {
                                return '
                                    <a href="'.url('/alumni/'.$a->id).'" class="btn btn-sm btn-info">Detail</a>
                                    <a href="'.url('/alumni/edit/'.$a->id).'" class="btn btn-sm btn-warning">Edit</a>
                                ';
                            })
                            ->addColumn('tanggal', function(Alumni $a) {
                                return $a->created_at;
                            })
                            ->rawColumns(['aksi'])
                            ->toJson();
    }

    public function create()
    {
        return view('admin.alumni.create');
    }

    public function store(AlumniRequest $request)
    {
        DB::transaction(function() use($request) {
            // store to users
            $user = User::create([
                'foto' => 'default.png',
                'username' => $request->nim,
                'password' => bcrypt($request->nim),
                'role' => 'alumni'
            ]);

            // store to alumni
            Alumni::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'nama' => $request->nama,
                'prodi' => $request->prodi
            ]);
        });

        return redirect('/alumni')->with('success', 'Data berhasil disimpan');
    }

    public function show(Alumni $alumni)
    {
        return view('admin.alumni.show', compact('alumni'));
    }

    public function edit(Alumni $alumni)
    {
        return view('admin.alumni.edit', compact('alumni'));
    }

    public function update(AlumniRequest $request, Alumni $alumni)
    {
        DB::transaction(function() use($request, $alumni) {
            // update to users
            $alumni->user->update([
                'username' => $request->nim,
                'password' => bcrypt($request->nim),
            ]);

            // update to alumni
            $alumni->update([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'prodi' => $request->prodi
            ]);
        });

        return redirect('/alumni')->with('success', 'Data berhasil disimpan');
    }

    public function destroy(Alumni $alumni)
    {
        if($alumni->permintaanLegalisir()->exists() && $alumni->permintaanLegalisir->status == 'Proses') {
            return response()->json([
                'message' => 'Alumni memiliki permintaan legalisir'
            ], 400);
        }

        DB::transaction(function() use($alumni) {
            $foto = $alumni->user->foto;

            if($foto != 'default.png') {
                if(file_exists(public_path('images/alumni/'.$foto))) {
                    unlink(public_path('images/alumni/'.$foto));
                }
            }

            // delete legalisir
            if($alumni->has('permintaanLegalisir.legalisir')->exists()) {
                if(file_exists(public_path('ijazah/legalisir/'.$alumni->permintaanLegalisir->legalisir->file_ijazah_legalisir))) {
                    unlink(public_path('ijazah/legalisir/'.$alumni->permintaanLegalisir->legalisir->file_ijazah_legalisir));
                    unlink(public_path('ijazah/qrcode/'.$alumni->permintaanLegalisir->legalisir->qrcode));
                }
                $alumni->permintaanLegalisir->legalisir->delete();
            }
            
            // delete permintaan legalisir
            if($alumni->permintaanLegalisir()->exists()) {
                if(file_exists(public_path('ijazah/raw/'.$alumni->permintaanLegalisir->file_ijazah))) {
                    unlink(public_path('ijazah/raw/'.$alumni->permintaanLegalisir->file_ijazah));
                }
                $alumni->permintaanLegalisir->delete();
            }

            // delete users
            $alumni->user->delete();
    
            // delete alumni
            $alumni->delete();
        });

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ], 200);
    }

    public function doImport(Request $request)
    {
        $this->validate($request, [
            'file_import' => 'mimes:xls,xlsx|max:5120'
        ], [
            'file_import.mimes' => 'Format file salah',
            'file_import.max' => 'Ukuran file terlalu besar'
        ]);
        
        try {
            Excel::import(new AlumniImport, $request->file_import);
        } catch(\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $error_message = '';

            foreach ($failures as $failure) {
                $error_value = (intval($failure->values()[$failure->attribute()]));
                $error_message = $failure->errors()[0];

                $error_message = $error_message.' ('.$error_value.')';
            }

            return redirect()->back()->with('error-excel', $error_message);
        }
        
        return redirect('/alumni')->with('success', 'Data berhasil disimpan');
    }
}

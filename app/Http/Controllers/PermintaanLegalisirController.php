<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use App\Models\PermintaanLegalisir;
use App\Http\Requests\PermintaanLegalisirRequest;

class PermintaanLegalisirController extends Controller
{
    public function index()
    {
        return view('admin.permintaan_legalisir.index');
    }

    public function permintaanLegalisirJson()
    {
        $data = PermintaanLegalisir::where('status', '=', 'proses');

        return DataTables::of($data)
                            ->orderColumn('nim', function($query, $order) {
                                $query->whereHas('alumni', function($query) use($order) {
                                    $query->orderBy('nim', $order);
                                });
                            })
                            ->orderColumn('nama', function($query, $order) {
                                $query->whereHas('alumni', function($query) use($order) {
                                    $query->orderBy('nama', $order);
                                });
                            })
                            ->orderColumn('no_ijazah', function($query, $order) {
                                $query->orderBy('no_ijazah', $order);
                            })
                            ->orderColumn('tanggal', function($query, $order) {
                                $query->orderBy('created_at', $order);
                            })
                            ->filterColumn('nim', function($query, $key) {
                                $query->whereHas('alumni', function($query) use($key) {
                                    $query->where('nim', 'like', '%'.$key.'%');
                                });
                            })
                            ->filterColumn('nama', function($query, $key) {
                                $query->whereHas('alumni', function($query) use($key) {
                                    $query->where('nama', 'like', '%'.$key.'%');
                                });
                            })
                            ->filterColumn('no_ijazah', function($query, $key) {
                                $query->where('no_ijazah', 'like', '%'.$key.'%');
                            })
                            ->addColumn('nim', function(PermintaanLegalisir $p) {
                                return $p->alumni->nim;
                            })
                            ->addColumn('nama', function(PermintaanLegalisir $p) {
                                return '<a href="'.url('/alumni/'.$p->alumni_id).'" target="_blank">'.$p->alumni->nama.'</a>';
                            })
                            ->addColumn('no_ijazah', function(PermintaanLegalisir $p) {
                                return $p->no_ijazah;
                            })
                            ->addColumn('file_ijazah', function(PermintaanLegalisir $p) {
                                return '<a href="'.asset('ijazah/raw/'.$p->file_ijazah).'" target="_blank">Download</a>';
                            })
                            ->addColumn('aksi', function(PermintaanLegalisir $p) {
                                return '
                                    <a href="'.url('/legalisir/create/'.$p->id).'" class="btn btn-sm btn-success">Legalisir</a>
                                    <a href="'.url('/permintaan-legalisir/'.$p->id).'" class="btn btn-sm btn-info">Detail</a>
                                ';
                            })
                            ->addColumn('tanggal', function(PermintaanLegalisir $p) {
                                return $p->created_at;
                            })
                            ->rawColumns(['nama', 'file_ijazah', 'aksi'])
                            ->toJson();
    }

    public function create()
    {
        return view('permintaan_legalisir.create');
    }

    public function store(PermintaanLegalisirRequest $request)
    {
        $file_ijazah = $request->file_ijazah;
        $file_name = uniqid().'.'.$file_ijazah->getClientOriginalExtension();

        $file_ijazah->move(public_path('ijazah/raw'), $file_name);

        PermintaanLegalisir::create([
            'alumni_id' => auth()->user()->alumni->id,
            'file_ijazah' => $file_name,
            'no_ijazah' => $request->no_ijazah,
            'status' => 'proses'
        ]);

        return redirect('/')->with('success', 'Data berhasil disimpan');
    }

    public function show(PermintaanLegalisir $permintaan_legalisir)
    {
        if(($permintaan_legalisir->status == 'Proses' && auth()->user()->role == 'admin') || (auth()->user()->role == 'alumni' && auth()->user()->alumni->permintaanLegalisir()->exists() )){  //&& auth()->user()->alumni->permintaanLegalisir->id == $permintaan_legalisir->id)) {
            return view('permintaan_legalisir.show', compact('permintaan_legalisir'));
        }

        return redirect()->back();
    }

    public function edit(PermintaanLegalisir $permintaan_legalisir)
    {
        if($permintaan_legalisir->status == 'Proses') {
            return view('permintaan_legalisir.edit', compact('permintaan_legalisir'));
        }

        return redirect()->back();
    }

    public function update(PermintaanLegalisirRequest $request, PermintaanLegalisir $permintaan_legalisir)
    {
        if($permintaan_legalisir->status == 'Selesai') {
            return redirect()->back()->with('error', 'Permintaan legalisir sudah selesai!');
        }

        $file_name = $permintaan_legalisir->file_ijazah;

        // update file and permintaan_legalisir
        if($request->file_ijazah) {
            $file_ijazah = $request->file_ijazah;
            $file_name = uniqid().'.'.$file_ijazah->getClientOriginalExtension();

            $file_ijazah->move(public_path('ijazah/raw'), $file_name);

            if(file_exists(public_path('ijazah/raw/'.$permintaan_legalisir->file_ijazah))) {
                unlink(public_path('ijazah/raw/'.$permintaan_legalisir->file_ijazah));
            }
        }

        $permintaan_legalisir->update([
            'no_ijazah' => $request->no_ijazah,
            'file_ijazah' => $file_name
        ]);

        return redirect('/permintaan-legalisir/'.$permintaan_legalisir->id)->with('success', 'Data berhasil disimpan');
    }

    public function destroy(PermintaanLegalisir $permintaan_legalisir)
    {
        if($permintaan_legalisir->status == 'Selesai') {
            return response()->json([
                'message' => 'Permintaan legalisir sudah selesai'
            ], 400);
        }

        if(file_exists(public_path('ijazah/raw/'.$permintaan_legalisir->file_ijazah))) {
            unlink(public_path('ijazah/raw/'.$permintaan_legalisir->file_ijazah));
        }

        $permintaan_legalisir->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}

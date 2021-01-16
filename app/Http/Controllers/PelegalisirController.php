<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Pelegalisir;
use Illuminate\Http\Request;
use App\Http\Requests\PelegalisirRequest;

class PelegalisirController extends Controller
{
    public function index()
    {
        return view('admin.pelegalisir.index');
    }

    public function pelegalisirJson()
    {
        $data = Pelegalisir::query();

        return DataTables::of($data)
                            ->orderColumn('nidn', function($query, $order) {
                                $query->orderBy('nidn', $order);
                            })
                            ->orderColumn('nama', function($query, $order) {
                                $query->orderBy('nama', $order);
                            })
                            ->orderColumn('jabatan', function($query, $order) {
                                $query->orderBy('jabatan', $order);
                            })
                            ->orderColumn('tanggal', function($query, $order) {
                                $query->orderBy('created_at', $order);
                            })
                            ->filterColumn('nidn', function($query, $key) {
                                $query->where('nidn', 'like', '%'.$key.'%');
                            })
                            ->filterColumn('nama', function($query, $key) {
                                $query->where('nama', 'like', '%'.$key.'%');
                            })
                            ->filterColumn('jabatan', function($query, $key) {
                                $query->where('jabatan', 'like', '%'.$key.'%');
                            })
                            ->addColumn('nidn', function(Pelegalisir $p) {
                                return $p->nidn;
                            })
                            ->addColumn('nama', function(Pelegalisir $p) {
                                return $p->nama;
                            })
                            ->addColumn('jabatan', function(Pelegalisir $p) {
                                return $p->jabatan;
                            })
                            ->addColumn('aksi', function(Pelegalisir $p) {
                                return '
                                    <a href="'.url('/pelegalisir/'.$p->id).'" class="btn btn-sm btn-info">Detail</a>
                                    <a href="'.url('/pelegalisir/edit/'.$p->id).'" class="btn btn-sm btn-warning">Edit</a>
                                ';
                            })
                            ->addColumn('tanggal', function(Pelegalisir $p) {
                                return $p->created_at;
                            })
                            ->rawColumns(['aksi'])
                            ->toJson();
    }

    public function create()
    {
        return view('admin.pelegalisir.create');
    }

    public function store(PelegalisirRequest $request)
    {
        Pelegalisir::create($request->all());
        
        return redirect('/pelegalisir')->with('success', 'Data berhasil disimpan');
    }

    public function show(Pelegalisir $pelegalisir)
    {
        return view('admin.pelegalisir.show', compact('pelegalisir'));
    }

    public function edit(Pelegalisir $pelegalisir)
    {
        return view('admin.pelegalisir.edit', compact('pelegalisir'));
    }

    public function update(PelegalisirRequest $request, Pelegalisir $pelegalisir)
    {
        $pelegalisir->update($request->except('_token', '_method'));

        return redirect('/pelegalisir')->with('success', 'Data berhasil disimpan');
    }

    public function destroy(Pelegalisir $pelegalisir)
    {
        // cek apakah pelegalisir mempunyai legalisir ?
        if($pelegalisir->legalisir()->count() > 0) {
            return response()->json([
                'message' => 'Pelegalisir tidak bisa dihapus'
            ], 400);
        }

        $pelegalisir->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}

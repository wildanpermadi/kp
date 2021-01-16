<?php

namespace App\Http\Controllers;

use DataTables;
use PDF;
use Carbon\Carbon;
use App\Mail\SendMail;
use App\Models\Legalisir;
use App\Models\Pelegalisir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PermintaanLegalisir;
use Illuminate\Support\Facades\Mail;
use Spatie\PdfToImage\Pdf as PDFIMG;
use App\Http\Requests\LegalisirRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LegalisirController extends Controller
{
    public function index()
    {
        return view('admin.legalisir.index');
    }

    public function legalisirJson()
    {
        $data = Legalisir::query();

        return DataTables::of($data)
                            ->orderColumn('nim', function($query, $order) {
                                $query->whereHas('permintaanLegalisir.alumni', function($query) use($order) {
                                    $query->orderBy('nim', $order);
                                });
                            })
                            ->orderColumn('nama', function($query, $order) {
                                $query->whereHas('permintaanLegalisir.alumni', function($query) use($order) {
                                    $query->orderBy('nama', $order);
                                });
                            })
                            ->orderColumn('pelegalisir', function($query, $order) {
                                $query->whereHas('pelegalisir', function($query) use($order) {
                                    $query->orderBy('nama', $order);
                                });
                            })
                            ->orderColumn('berlaku_sampai', function($query, $order) {
                                $query->orderBy('berlaku_sampai', $order);
                            })
                            ->orderColumn('tanggal', function($query, $order) {
                                $query->orderBy('created_at', $order);
                            })
                            ->filterColumn('nim', function($query, $key) {
                                $query->whereHas('permintaanLegalisir.alumni', function($query) use($key) {
                                    $query->where('nim', 'like', '%'.$key.'%');
                                });
                            })
                            ->filterColumn('nama', function($query, $key) {
                                $query->whereHas('permintaanLegalisir.alumni', function($query) use($key) {
                                    $query->where('nama', 'like', '%'.$key.'%');
                                });
                            })
                            ->filterColumn('pelegalisir', function($query, $key) {
                                $query->whereHas('pelegalisir', function($query) use($key) {
                                    $query->where('nama', 'like', '%'.$key.'%');
                                });
                            })
                            ->addColumn('nim', function(Legalisir $l) {
                                return $l->permintaanLegalisir->alumni->nim;
                            })
                            ->addColumn('nama', function(Legalisir $l) {
                                return '<a href="'.url('/alumni/'.$l->permintaanLegalisir->alumni_id).'" target="_blank">'.$l->permintaanLegalisir->alumni->nama.'</a>';
                            })
                            ->addColumn('file_ijazah_legalisir', function(Legalisir $l) {
                                return '<a href="'.asset('ijazah/legalisir/'.$l->file_ijazah_legalisir).'" target="_blank">Download</a>';
                            })
                            ->addColumn('pelegalisir', function(Legalisir $l) {
                                return $l->pelegalisir->nama;
                            })
                            ->addColumn('berlaku_sampai', function(Legalisir $l) {
                                return $l->berlaku_sampai->translatedFormat('d F Y');
                            })
                            ->addColumn('aksi', function(Legalisir $l) {
                                return '
                                    <a href="'.url('/legalisir/'.$l->id).'" class="btn btn-sm btn-info">Detail</a>
                                ';
                            })
                            ->addColumn('tanggal', function(Legalisir $l) {
                                return $l->created_at;
                            })
                            ->rawColumns(['nama', 'file_ijazah_legalisir', 'aksi'])
                            ->toJson();
    }

    public function create(PermintaanLegalisir $permintaan_legalisir)
    {
        $berlaku_sampai = Carbon::now()->addYear(2);
        $pelegalisir = Pelegalisir::latest()->get();

        return view('admin.legalisir.create', compact('permintaan_legalisir', 'berlaku_sampai', 'pelegalisir'));
    }

    public function store(LegalisirRequest $request)
    {
        $permintaan_legalisir = PermintaanLegalisir::find($request->permintaan_legalisir_id);
        $dosen = Pelegalisir::find($request->pelegalisir_id);

        // Begin Transaction
        DB::beginTransaction();

        try {
            // insert to legalisir
            $legalisir = Legalisir::create([
                'pelegalisir_id' => $dosen->id,
                'permintaan_legalisir_id' => $permintaan_legalisir->id,
                'berlaku_sampai' => $request->berlaku_sampai
            ]);

            // generate kode_legalisir for qr code
            $berlaku_sampai = $request->berlaku_sampai_formatted;
            $now = Carbon::now()->translatedFormat('d F Y');

            $qrcode_file_name = uniqid().'.png';
            $kode_legalisir = $this->generateKodeLegalisir($legalisir->id, $berlaku_sampai, $now, $dosen->nama, $dosen->nidn);

            QrCode::size(500)->format('png')->generate($kode_legalisir, public_path('ijazah/qrcode/'.$qrcode_file_name));

            // update qrcode to legalisir
            $legalisir->update([
                'qrcode' => $qrcode_file_name
            ]);

            // convert pdf raw menjadi image
            $legalisir_pdf = $this->convertPdf($permintaan_legalisir->file_ijazah, $qrcode_file_name);

            // update to legalisir
            $legalisir->update([
                'file_ijazah_legalisir' => $legalisir_pdf,
                'kode_legalisir' => $kode_legalisir
            ]);

            // update status permintaan legalisir to selesai
            $permintaan_legalisir->update([
                'status' => 'selesai'
            ]);

            // send to email
            $nama = $permintaan_legalisir->alumni->nama;
            $email = $permintaan_legalisir->alumni->email;
            $kirim = Mail::to($email)->send(new SendMail($nama, public_path('ijazah/legalisir/'.$legalisir_pdf)));

            // Commit Transaction
            DB::commit();
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
        }

        return redirect('/legalisir')->with('success', 'Ijazah berhasil dikirim via email');
    }

    public function show(Legalisir $legalisir)
    {
        $pelegalisir = Pelegalisir::latest()->get();

        return view('admin.legalisir.show', compact('legalisir', 'pelegalisir'));
    }

    public function update(LegalisirRequest $request, Legalisir $legalisir)
    {
        DB::beginTransaction();

        try {
            // update pelegalisir dan kode legalisir
            $pelegalisir = Pelegalisir::find($request->pelegalisir_id);
            $kode_legalisir = $this->generateKodeLegalisir($legalisir->id, $legalisir->berlaku_sampai->translatedFormat('d F Y'), $legalisir->created_at->translatedFormat('d F Y'), $pelegalisir->nama, $pelegalisir->nidn);

            // update qrcode
            if(file_exists(public_path('ijazah/qrcode/'.$legalisir->qrcode))) {
                unlink(public_path('ijazah/qrcode/'.$legalisir->qrcode));
            }

            $qrcode = uniqid().'.png';
            QrCode::size(500)->format('png')->generate($kode_legalisir, public_path('ijazah/qrcode/'.$qrcode));

            $legalisir->update([
                'qrcode' => $qrcode,
                'kode_legalisir' => $kode_legalisir
            ]);

            // update ijazah
            if(file_exists(public_path('ijazah/legalisir/'.$legalisir->file_ijazah_legalisir))) {
                unlink(public_path('ijazah/legalisir/'.$legalisir->file_ijazah_legalisir));
            }

            $file_ijazah_legalisir = $this->convertPdf($legalisir->permintaanLegalisir->file_ijazah, $qrcode);

            $legalisir->update([
                'file_ijazah_legalisir' => $file_ijazah_legalisir
            ]);

            // send to email
            $nama = $legalisir->permintaanLegalisir->alumni->nama;
            $email = $legalisir->permintaanLegalisir->alumni->email;
            $kirim = Mail::to($email)->send(new SendMail($nama, public_path('ijazah/legalisir/'.$file_ijazah_legalisir)));

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }


        return redirect()->back()->with('success', 'Ijazah legalisir berhasil diubah');
    }

    private function generateKodeLegalisir($nomor, $berlaku_sampai, $now, $nama_dosen, $nidn_dosen)
    {
        return "Nomor: ".$nomor." \nBerlaku Sampai ".$berlaku_sampai." \nMenyatakan bahwa copy/salinan sesuai dengan asli \nBoyolali, ".$now." \nMengatahui Ketua Jurusan \n".$nama_dosen."\nNIDN. ".$nidn_dosen;
    }

    private function convertPdf($ijazah_raw, $qrcode_file_name)
    {
        $file_ijazah_legalisir_name = uniqid();
        $legalisir_jpg = $file_ijazah_legalisir_name.'.jpg';
        $legalisir_pdf = $file_ijazah_legalisir_name.'.pdf';

        // convert pdf raw ke image
        $pdf = new PDFIMG(public_path('ijazah/raw/'.$ijazah_raw));
        $pdf->saveImage(public_path('ijazah/legalisir/'.$legalisir_jpg));

        // convert image menjadi pdf
        $pdf = PDF::loadview('admin.legalisir.cetak', compact('legalisir_jpg', 'qrcode_file_name'))->setPaper('a4', 'landscape')->save(public_path('ijazah/legalisir/'.$legalisir_pdf));

        // hapus ijazah image
        if(file_exists(public_path('ijazah/legalisir/'.$legalisir_jpg))) {
            unlink(public_path('ijazah/legalisir/'.$legalisir_jpg));
        }

        return $legalisir_pdf;
    }
}

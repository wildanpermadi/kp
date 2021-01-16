@extends('template.app')

@section('title', 'Legalisir')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Legalisir</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/legalisir') }}">Legalisir</a></li>
                            <li class="breadcrumb-item active">Tambah Data</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Detail Permintaan Legalisir</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-unbordered my-3">
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>NIM</b> 
                                        <a>{{ $permintaan_legalisir->alumni->nim }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Nama Alumni</b> 
                                        <a href="{{ url('/alumni/'.$permintaan_legalisir->alumni_id) }}" target="_blank">{{ $permintaan_legalisir->alumni->nama }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Nomor Ijazah</b> 
                                        <a>{{ $permintaan_legalisir->no_ijazah }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>File Ijazah</b> 
                                        <a href="{{ asset('ijazah/raw/'.$permintaan_legalisir->file_ijazah) }}" target="_blank">Download</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Tanggal Input</b> 
                                        <a>{{ $permintaan_legalisir->created_at->format('d/m/Y H:i') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Tambah Data</div>
                            </div>
                            <div class="card-body">
                                <form action="{{ url('/legalisir/store') }}" method="POST">
                                    <input type="hidden" name="berlaku_sampai" value="{{ $berlaku_sampai->format('Y-m-d') }}">
                                    <input type="hidden" name="permintaan_legalisir_id" value="{{ $permintaan_legalisir->id }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="pelegalisir_id">Pelegalisir <span class="text-danger">*</span></label>
                                        <select name="pelegalisir_id" id="pelegalisir_id" class="form-control @error('pelegalisir_id') is-invalid @enderror">
                                            <option value="">Pilih Pelegalisir</option>
                                            @foreach($pelegalisir as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('pelegalisir_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="berlaku_sampai">Berlaku Sampai</label>
                                        <input type="text" id="berlaku_sampai" name="berlaku_sampai_formatted" class="form-control" value="{{ $berlaku_sampai->translatedFormat('d F Y') }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ url('/legalisir') }}" class="btn btn-default btn-sm">Kembali</a>
                                        <button type="submit" class="btn btn-sm btn-success">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/. container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // submit
            $('form').on('submit', function() {
                $(this).find('button[type="submit"]').attr('disabled', true);
            });
        });
    </script>
@endpush
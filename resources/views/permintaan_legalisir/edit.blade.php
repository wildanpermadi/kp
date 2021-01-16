@extends('template.app')

@section('title', 'Permintaan Legalisir')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Permintaan Legalisir</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Permintaan Legalisir</a></li>
                            <li class="breadcrumb-item active">Edit Data</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        @if(Session::has('error'))
                            <div class="alert alert-danger">{{ Session::get('error') }}</div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Edit Data</div>
                            </div>
                            <div class="card-body">
                                <form action="{{ url('/permintaan-legalisir/update/'.$permintaan_legalisir->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="form-group">
                                        <label for="no_ijazah">Nomor Ijazah <span class="text-danger">*</span></label>
                                        <input type="text" name="no_ijazah" id="no_ijazah" class="form-control @error('no_ijazah') is-invalid @enderror" value="{{ $permintaan_legalisir->no_ijazah }}" required>
                                        @error('no_ijazah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="file_ijazah">File Ijazah</label>
                                        <input type="file" name="file_ijazah" id="file_ijazah" class="form-control-file @error('file_ijazah') is-invalid @enderror">
                                        <small class="text-muted">PDF | Maks: 5Mb</small>
                                        @error('file_ijazah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ url('/') }}" class="btn btn-default btn-sm">Kembali</a>
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
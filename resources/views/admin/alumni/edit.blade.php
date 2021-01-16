@extends('template.app')

@section('title', 'Edit Alumni')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Alumni</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/alumni') }}">Alumni</a></li>
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
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Edit Data</div>
                            </div>
                            <div class="card-body">
                                <form action="{{ url('/alumni/update/'.$alumni->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="PATCH">
                                    <div class="form-group">
                                        <label for="nim">NIM <span class="text-danger">*</span></label>
                                        <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ $alumni->nim }}" required>
                                        @error('nim')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" id="nama" class="form-control" value="{{ $alumni->nama }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="prodi">Program Studi <span class="text-danger">*</span></label>
                                        <input type="text" name="prodi" id="prodi" class="form-control" value="{{ $alumni->prodi }}" required>
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ url('/alumni') }}" class="btn btn-default btn-sm">Kembali</a>
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

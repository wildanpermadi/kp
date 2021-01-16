@extends('template.app')

@section('title', 'Profil')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <style>
        .image-profile {
            width: 100px;
            height: 100px;
            background-image: url('{{ asset("images/alumni/".auth()->user()->foto) }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            border-radius: 100%;
            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Profil</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Profil</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if(!auth()->user()->alumni->tgl_lahir || !auth()->user()->alumni->email || !auth()->user()->alumni->sk_kelulusan || !auth()->user()->alumni->tgl_kelulusan)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-info">
                                Segera lengkapi profil anda agar bisa mengajukan permintaan legalisir
                            </div>
                        </div>
                    </div>
                @endif
                <form action="{{ url('/profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Profil Anda</div>
                                </div>
                                <div class="card-body">
                                    <div class="image-profile border"></div>
                                    <div class="form-group">
                                        <label for="foto">Foto</label>
                                        <input type="file" class="form-control-file @error('foto') is-invalid @enderror" name="foto" id="foto">
                                        <small class="text-muted">JPG,PNG | Maks: 2Mb</small>
                                        @error('foto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="nim">NIM <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nim') is-invalid @enderror" name="nim" id="nim" value="{{ auth()->user()->alumni->nim }}" required>
                                        @error('nim')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" id="nama" value="{{ auth()->user()->alumni->nama }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tgl_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" value="{{ auth()->user()->alumni->tgl_lahir ? auth()->user()->alumni->tgl_lahir->format('Y-m-d') : old('tgl_lahir') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ auth()->user()->alumni->email ? auth()->user()->alumni->email : old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="prodi">Program Studi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="prodi" id="prodi" value="{{ auth()->user()->alumni->prodi }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="sk_kelulusan">SK Kelulusan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('sk_kelulusan') is-invalid @enderror" name="sk_kelulusan" id="sk_kelulusan" value="{{ auth()->user()->alumni->sk_kelulusan ? auth()->user()->alumni->sk_kelulusan : old('sk_kelulusan') }}" required>
                                        @error('sk_kelulusan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="tgl_kelulusan">Tanggal Kelulusan <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tgl_kelulusan" id="tgl_kelulusan" value="{{ auth()->user()->alumni->tgl_kelulusan ? auth()->user()->alumni->tgl_kelulusan->format('Y-m-d') : old('tgl_kelulusan') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Pengaturan Akun</div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="username">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username" id="username" value="{{ auth()->user()->username }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <a href="#" data-target="#modalPassword" data-toggle="modal" class="btn btn-sm btn-warning">Ganti Password</a>
                                    <button type="submit" class="btn btn-sm btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!--/. container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Modal Password -->
    <div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('/change-password') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ganti Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="old_password">Password Lama <span class="text-danger">*</span></label>
                            <input type="password" name="old_password" id="old_password" class="form-control {{ Session::has('old-password-error') ? 'is-invalid' : '' }}" required>
                            @if(Session::has('old-password-error'))
                                <div class="invalid-feedback">{{ Session::get('old-password-error') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // validate error password
            @if(Session::has('old-password-error') || $errors->has('password'))
                $('#modalPassword').modal('show');
            @endif

            // swal
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            @if(Session::has('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ Session::get('success') }}"
                });
            @endif
        });
    </script>
@endpush

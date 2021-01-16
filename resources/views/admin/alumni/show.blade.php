@extends('template.app')

@section('title', 'Detail Alumni')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <style>
        .image-profile {
            width: 100px;
            height: 100px;
            background-image: url('{{ asset("images/alumni/".$alumni->user->foto) }}');
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
                        <h1 class="m-0 text-dark">Alumni</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/alumni') }}">Alumni</a></li>
                            <li class="breadcrumb-item active">Detail Data</li>
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
                                <div class="card-title">Identitas</div>
                            </div>
                            <div class="card-body">
                                <div class="image-profile border"></div>
                                <ul class="list-group list-group-unbordered my-3">
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>NIM</b> 
                                        <a>{{ $alumni->nim }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Nama Lengkap</b> 
                                        <a>{{ $alumni->nama }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Tanggal Lahir</b> 
                                        <a>{{ $alumni->tgl_lahir ? $alumni->tgl_lahir->format('d/m/Y') : '-' }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Program Studi</b> 
                                        <a>{{ $alumni->prodi }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>SK Kelulusan</b> 
                                        <a>{{ $alumni->sk_kelulusan ? $alumni->sk_kelulusan : '-' }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Tanggal Kelulusan</b> 
                                        <a>{{ $alumni->tgl_kelulusan ? $alumni->tgl_kelulusan->format('d/m/Y') : '-' }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Informasi Akun</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Username</b> 
                                        <a>{{ $alumni->user->username }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Email</b> 
                                        <a>{{ $alumni->email ? $alumni->email : '-' }}</a>
                                    </li>
                                </ul>
                                <a href="{{ url('/alumni') }}" class="btn btn-sm btn-default">Kembali</a>
                                <a href="#" class="btn btn-sm btn-danger btn-delete">Hapus</a>
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
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // delete
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data akan dihapus secara permanen",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ url('/alumni/destroy/'.$alumni->id) }}",
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location = "{{ url('/alumni') }}";
                                });
                            },
                            error: function(err) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: err.responseJSON.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                })
            });
        });
    </script>
@endpush
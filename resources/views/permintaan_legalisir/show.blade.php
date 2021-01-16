@extends('template.app')

@section('title', 'Detail Permintaan Legalisir')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

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
                            @if(auth()->user()->role == 'alumni')
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Permintaan Legalisir</a></li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ url('/permintaan-legalisir') }}">Permintaan Legalisir</a></li>
                            @endif
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
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Permintaan Legalisir</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-unbordered my-3">
                                    @if(auth()->user()->role == 'admin')
                                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                                            <b>NIM</b> 
                                            <a>{{ $permintaan_legalisir->alumni->nim }}</a>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                                            <b>Nama Alumni</b> 
                                            <a href="{{ url('/alumni/'.$permintaan_legalisir->alumni_id) }}" target="_blank">{{ $permintaan_legalisir->alumni->nama }}</a>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Nomor Ijazah</b> 
                                        <a>{{ $permintaan_legalisir->no_ijazah }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>File Ijazah</b> 
                                        <a href="{{ $permintaan_legalisir->status == 'Selesai' ? asset('ijazah/legalisir/'.$permintaan_legalisir->legalisir->file_ijazah_legalisir) : asset('ijazah/raw/'.$permintaan_legalisir->file_ijazah) }}" target="_blank">Download</a>
                                    </li>
                                    @if(auth()->user()->role == 'alumni')
                                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                                            <b>Status</b> 
                                            <a><span class="badge {{ $permintaan_legalisir->status == 'Selesai' ? 'badge-success' : 'badge-primary' }}">{{ $permintaan_legalisir->status }}</span></a>
                                        </li>
                                    @endif
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Tanggal Input</b> 
                                        <a>{{ $permintaan_legalisir->created_at->format('d/m/Y H:i') }}</a>
                                    </li>
                                </ul>
                                <a href="{{ auth()->user()->role == 'alumni' ? url('/') : url('/permintaan-legalisir') }}" class="btn btn-sm btn-default">Kembali</a>
                                @if(auth()->user()->role == 'alumni' && $permintaan_legalisir->status == 'Proses')
                                    <a href="{{ url('/permintaan-legalisir/edit/'.$permintaan_legalisir->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="#" class="btn btn-sm btn-danger btn-delete">Hapus</a>
                                @elseif(auth()->user()->role == 'admin')
                                    <a href="{{ url('/legalisir/create/'.$permintaan_legalisir->id) }}" class="btn btn-sm btn-success">Legalisir</a>
                                @endif
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
                            url: "{{ url('/permintaan-legalisir/destroy/'.$permintaan_legalisir->id) }}",
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
                                    window.location = "{{ url('/') }}";
                                });
                            },
                            error: function(err) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Perhatian',
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
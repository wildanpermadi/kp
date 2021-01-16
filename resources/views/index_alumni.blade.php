@extends('template.app')

@section('title', 'Dashboard')

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
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Dashboard</li>
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
                    <div class="col-lg-12">
                        @if(!auth()->user()->alumni->tgl_lahir || !auth()->user()->alumni->email || !auth()->user()->alumni->sk_kelulusan || !auth()->user()->alumni->tgl_kelulusan)
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-info"></i> Perhatian!</h5>
                                Segera lengkapi profil anda <a href="{{ url('/profile') }}">disini</a>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-title">Permintaan Legalisir Anda</div>
                                    @if((auth()->user()->alumni->email) )<!--&& !auth()->user()->alumni->permintaanLegalisir()->exists())-->
                                        <a href="{{ url('/permintaan-legalisir/create') }}" class="btn btn-sm btn-primary">Tambah Data</a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nomor Ijazah</th>
                                                <th>File Ijazah</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permintaan_legalisir as $data)
                                            <tr>
                                                <td>{{ $data->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $data->no_ijazah }}</td>
                                                <td>
                                                    @if($data->status == 'Selesai')
                                                        <a href="{{ asset('ijazah/legalisir/'.$data->legalisir->file_ijazah_legalisir) }}" target="_blank">Download</a>
                                                    @else
                                                        <a href="{{ asset('ijazah/raw/'.$data->file_ijazah) }}" target="_blank">Download</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $data->status == 'Selesai' ? 'badge-success' : 'badge-primary' }}">{{ $data->status }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ url('/permintaan-legalisir/'.$data->id) }}" class="btn btn-sm btn-info">Detail</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if(count($permintaan_legalisir) == 0)
                                        <p class="text-muted text-center">Data belum tersedia</p>
                                    @endif
                                </div>
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
        });
    </script>
@endpush

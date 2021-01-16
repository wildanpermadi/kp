@extends('template.app')

@section('title', 'Detail Legalisir')

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
                        <h1 class="m-0 text-dark">Legalisir</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/legalisir') }}">Legalisir</a></li>
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
                                <div class="card-title">Detail Legalisir</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-unbordered my-3">
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>NIM</b> 
                                        <a>{{ $legalisir->permintaanLegalisir->alumni->nim }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Nama Alumni</b> 
                                        <a href="{{ url('/alumni/'.$legalisir->permintaanLegalisir->alumni_id) }}" target="_blank">{{ $legalisir->permintaanLegalisir->alumni->nama }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Nomor Ijazah</b> 
                                        <a>{{ $legalisir->permintaanLegalisir->no_ijazah }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>File Ijazah</b> 
                                        <a href="{{ asset('ijazah/legalisir/'.$legalisir->file_ijazah_legalisir) }}" target="_blank">Download</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Pelegalisir</b> 
                                        <a>{{ $legalisir->pelegalisir->nama }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Berlaku Sampai</b> 
                                        <a>{{ $legalisir->berlaku_sampai->translatedFormat('d F Y') }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between flex-wrap">
                                        <b>Tanggal Input</b> 
                                        <a>{{ $legalisir->created_at->format('d/m/Y H:i') }}</a>
                                    </li>
                                </ul>
                                <a href="{{ url('/legalisir') }}" class="btn btn-sm btn-default">Kembali</a>
                                <a href="#" data-target="#modalPelegalisir" data-toggle="modal" class="btn btn-sm btn-warning">Ganti Pelegalisir</a>
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

    <!-- Modal Pelegalisir -->
    <div class="modal fade" id="modalPelegalisir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('/legalisir/update/'.$legalisir->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ganti Pelegalisir</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="pelegalisir_id">Pelegalisir <span class="text-danger">*</span></label>
                            <select name="pelegalisir_id" id="pelegalisir_id" class="form-control @error('pelegalisir_id') is-invalid @enderror">
                                <option value="">Pilih Pelegalisir</option>
                                @foreach($pelegalisir as $p)
                                    <option {{ $legalisir->pelegalisir_id == $p->id ? 'selected' : '' }} value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            @error('pelegalisir_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

            @error('pelegalisir_id')
                $('#modalPelegalisir').modal('show');
            @enderror

            // submit
            $('#modalPelegalisir form').on('submit', function() {
                $(this).find('button[type="submit"]').attr('disabled', true);
            });
        });
    </script>
@endpush
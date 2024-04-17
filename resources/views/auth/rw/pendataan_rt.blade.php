@extends('layouts.rw')

@section('content')
    {{-- <div class="container container-pengumuman col-12"> --}}
    <!-- Modal -->
    <div class="modal fade" id="tambahRT" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah RT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body justify-content-start text-start">
                    <!-- Form untuk pengajuan pengumuman -->
                    <form action="{{ route('rt.store') }}" method="POST">
                        @csrf
                        <!-- Tambahkan input form sesuai kebutuhan -->

                        <div class="form-group mb-3">
                            <label for="no_rt" class="form-label text-start">Nomor RT</label>
                            <input type="text" class="form-control" id="no_rt" name="no_rt"
                                placeholder="Masukkan Nomor RT" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nik_rt" class="form-label text-start">NIK RT</label>
                            <input type="text" class="form-control" id="nik_rt" name="nik_rt"
                                placeholder="Masukkan NIK RT" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jumlah_keluarga_rt" class="form-label">Jumlah Keluarga RT</label>
                            <input type="text" class="form-control" id="jumlah_keluarga_rt" name="jumlah_keluarga_rt"
                                placeholder="Masukkan Jumlah Keluarga RT" required></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jumlah_penduduk_rt" class="form-label">Jumlah Penduduk RT</label>
                            <input type="text" class="form-control" id="jumlah_penduduk_rt" name="jumlah_penduduk_rt"
                            placeholder="Masukkan Jumlah Penduduk RT" required>
                        </div>

                        <!-- Tambahkan input lainnya sesuai kebutuhan -->
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-tabel p-4 mb-3">
            <h5>
                Pendataan RT
                <button class="btn btn-add float-end" data-bs-toggle="modal" data-bs-target="#tambahRT">Tambah Data</button>
            </h5>
        </div>
        <hr>
        <div class="card-body">
            <div class="table-responsive">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush

@endsection

@push('css')
@endpush
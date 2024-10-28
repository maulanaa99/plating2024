@extends('layout.master')
@section('title')
    Data NG Molding Racking
@endsection

@section('breadcrumb')
    @parent
    <li class="active"> > NG Molding Racking</li>
@endsection

@section('content')
    <div class="card-header">
        <div class="row float-right">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="{{ route('ngracking.tambah') }}" class="btn btn-icon icon-left btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data</a>
            </div>
        </div>
        <form action="{{ route('ngracking') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label for="">Tanggal</label>
                    <input type="date" class="form-control" name="date" id="date" value="{{ $date }}">
                </div>
                <div class="col-md-4">
                    <label for="" class="text-white">Filter</label> <br>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="add-row" class="table table-sm table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Part Name</th>
                        <th>Jenis NG</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($ngracking as $no => $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}
                            </td>
                            <td>{{ $row->part_name }}</td>
                            <td>{{ $row->jenis_ng }}</td>
                            <td align="center">{{ $row->quantity }}</td>
                            <td align="center">
                                <a href="{{ route('ngracking.edit', $row->id) }}" class="btn btn-icon btn-sm btn-warning"><i
                                        class="far fa-edit"></i></a>
                                {{-- <a href="#" data-id="{{ $row->id }}"
                                    class="btn btn-icon btn-sm btn-danger swal-confirm"><i class="far fa-trash-alt"> Hapus
                                    </i>
                                    <form action="{{ route('ngracking.delete', $row->id) }}" method="POST"
                                        id="delete{{ $row->id }}">
                                        @csrf
                                    </form>
                                </a> --}}
                                <button type="button" class="btn btn-danger btn-sm delete-button"
                                    data-id="{{ $row->id }}"> <i class="far fa-trash-alt">
                                    </i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    </div>
@endsection

@push('page-script')
    <script src="{{ asset('assets/modules/sweetalert/sweetalert.min.js') }}"></script>
@endpush

@push('after-script')
    @include('sweetalert::alert')

    <script>
        $(document).ready(function() {
            $("#add-row").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "pageLength": 75,
                "lengthMenu": [
                    [10, 25, 50, 75, -1],
                    [10, 25, 50, 75, "All"]
                ],
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#add-row_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script>
        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault();

            var id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan data yang telah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus saja!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/racking_t/ngracking/delete/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (data.success) {
                                Swal.fire(
                                    'Terhapus!',
                                    'Data Anda telah dihapus.',
                                    'success'
                                ).then((result) => {
                                    window.location.href = '/racking_t/ngracking';
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Part sudah di Unracking.',
                                    'error'
                                )
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            ).then((result) => {
                                window.location.href = '/racking_t/ngracking';
                            });
                        }
                    });
                }
            })
        });
    </script>
@endpush

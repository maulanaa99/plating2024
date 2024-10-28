@extends('layout.master')
@section('title')
    Data Stok di Lane
@endsection

@section('breadcrumb')
    @parent
    <li class="active"> Stok > Stok di Lane</li>
@endsection

@push('page-styles')
    <style>
        .fit-table {
            width: 100%;
            max-width: 600px;
            table-layout: fixed;
            text-align: center;
        }

        .fit-table td,
        .fit-table th {
            vertical-align: middle;
        }
    </style>
@endpush

@section('content')
    <div class="card-header">
        <div class="row float-right">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="{{ route('ngracking.tambah') }}" class="btn btn-icon icon-left btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data</a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="col-md-12">
            <h2>Data Stok CS - FS</h2>
            <div class="table-responsive">
                <table id="add-row" class="table table-sm table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Tanggal Racking</th>
                            <th>Waktu Racking</th>
                            <th>Tanggal Unracking</th>
                            <th>Waktu Unracking</th>
                            <th>Part Name</th>
                            <th>No Bar</th>
                            <th>Katalis</th>
                            <th>Qty Bar</th>
                            <th>Cycle</th>
                            <th>Stok BC</th>
                            <th>Jenis</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($stok_bc as $no => $row)
                            <tr>
                                <td style="width:1px; white-space:nowrap;">{{ $loop->iteration }}</td>
                                <td style="width:1px; white-space:nowrap;"><a
                                        href="{{ route('unracking_t.edit', $row->id) }}">{{ $row->id }}</td>
                                <td style="width:1px; white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($row->tanggal_r)->format('d-m-Y') }}</td>
                                <td style="width:1px; white-space:nowrap;">{{ $row->waktu_in_r }} </td>
                                <td style="width:1px; white-space:nowrap;">
                                    {{ \Carbon\Carbon::parse($row->tanggal_u)->format('d-m-Y') }}</td>
                                <td style="width:1px; white-space:nowrap;">{{ $row->waktu_in_u }} </td>
                                <td style="width:1px; white-space:nowrap;">{{ $row->part_name }}</td>
                                <td style="width:1px; white-space:nowrap;">{{ $row->no_bar }}</td>
                                <td style="width:1px; white-space:nowrap;">{{ $row->katalis }}</td>
                                <td style="width:1px; white-space:nowrap;">{{ $row->qty_bar }}</td>
                                <td style="width:1px; white-space:nowrap;">{{ $row->cycle }}</td>
                                <td style="width:1px; white-space:nowrap;">{{ $row->stok_bc }}</td>
                                <td style="width:1px; white-space:nowrap;">{{ $row->jenis }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" align="center"> <b> Total </b></td>
                            <td> <b> {{ number_format($sum_qty_bar) }} Pcs </b></td>
                            <td> </td>
                            <td> <b> {{ number_format($sum_stok_bc) }} Pcs </b></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
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
                "buttons": ["csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#add-row_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script>
        $(".swal-confirm").click(function(e) {
            id = e.target.dataset.id;
            swal({
                    title: 'Hapus data? ',
                    text: 'Setelah dihapus, data tidak dapat dikembalikan',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $(`#delete${id}`).submit();
                    } else {}
                });
        });
    </script>
@endpush

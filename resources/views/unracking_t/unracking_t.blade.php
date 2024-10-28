@extends('layout.master')
@section('title')
    Data Unracking
@endsection

@section('breadcrumb')
    @parent
    <li class="active">
        > Unracking</li>
@endsection

@section('content')
    <div class="card-header centering">
        <form action="{{ route('unracking_t') }}" method="GET">
            <div class="row input-daterange">
                <div class="col-md-5">
                    <input type="date" class="form-control" name="start_date" id="start_date" value="{{ $start_date }}">
                </div>
                <div class="col-md-1">
                    <center>
                        <font size="5"><b> - </b> </font>
                    </center>
                </div>
                <div class="col-md-5">
                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{ $end_date }}">
                </div>
                <div class="col-md-1">
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
                        <th class="align-middle text-center">#</th>
                        <th class="align-middle text-center">Tgl Racking</th>
                        <th class="align-middle text-center">Waktu Racking</th>
                        <th class="align-middle text-center">Tgl Unracking</th>
                        <th class="align-middle text-center">Waktu Unracking</th>
                        <th class="align-middle text-center">No Bar</th>
                        <th class="align-middle text-center">Part Name</th>
                        <th class="align-middle text-center">Channel</th>
                        <th class="align-middle text-center">Qty Bar</th>
                        <th class="align-middle text-center">Qty Aktual</th>
                        <th class="align-middle text-center">Cycle</th>
                        <th class="align-middle text-center">Jenis</th>
                        <th class="align-middle text-center">PIC</th>
                        <th class="align-middle text-center">Kategori</th>
                        <th class="align-middle text-center">Keterangan</th>
                        <th class="align-middle text-center">Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plating as $no => $unrack)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td align="center">{{ \Carbon\Carbon::parse($unrack->tanggal_r)->format('d-m-Y') }}</td>
                            <td align="center">{{ $unrack->waktu_in_r }}</td>
                            {{-- <td align="center">{{ \Carbon\Carbon::parse($unrack->tanggal_u)->format('d-m-Y') }}</td> --}}
                            {{-- <td align="center">{{ \Carbon\Carbon::parse($unrack->tanggal_u)->format('d-m-Y') }}</td> --}}
                            @if ($unrack->tanggal_u)
                                <td align="center">{{ \Carbon\Carbon::parse($unrack->tanggal_u)->format('d-m-Y') }}</td>
                            @else
                                <td></td>
                            @endif
                            <td align="center">{{ $unrack->waktu_in_u }}</td>
                            <td align="center">{{ $unrack->no_bar }}</td>
                            <td>{{ $unrack->part_name }}</td>
                            <td align="center">{{ $unrack->channel }}</td>
                            <td align="center">{{ $unrack->qty_bar }}</td>
                            <td align="center">{{ $unrack->qty_aktual }}</td>
                            <td align="center">{{ $unrack->cycle }}</td>
                            <td align="center">{{ $unrack->jenis }}</td>
                            <td align="center">{{ $unrack->updated_by }}</td>
                            <td align="center">{{ $unrack->kategori }}</td>
                            <td align="center">{{ $unrack->keterangan }}</td>
                            {{-- <td align="center">{{ $unrack->status }}</td> --}}
                            <td align="center">
                                @if ($unrack->status == '1')
                                    <a href="#" class="btn btn-sm btn-danger"><i
                                            class="fas fa-spinner fa-spin"></i></a>
                                @elseif ($unrack->status == '2')
                                    <a href="#" class="btn btn-sm btn-info"> <i class="fas fa-hourglass-half fa-spin"
                                            style="color: #2b292e;"></i>
                                    </a>
                                @elseif ($unrack->status == '3')
                                    <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                                @endif
                            </td>
                            <td align="center">
                                <a href="{{ route('unracking_t.edit', $unrack->id) }}"
                                    class="btn btn-icon btn-sm btn-warning" target="_blank"><i class="far fa-edit"></i> </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
                "order": [
                    [0, 'desc']
                ],
                "lengthMenu": [
                    [10, 25, 50, 75, -1],
                    [10, 25, 50, 75, "All"]
                ],
                scrollY: "700px",
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                fixedColumns: {
                    left: 2,
                }
                // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#add-row_wrapper .col-md-6:eq(0)');
        });

        setTimeout(function() {
            location.reload();
        }, 600000);
    </script>
@endpush

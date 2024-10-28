@extends('layout.master')
@section('title')
    Stok {{ \Carbon\Carbon::parse($date)->format('D, d M y') }}
@endsection

@section('breadcrumb')
    @parent
    <li class="active"> > Stok</li>
@endsection

@section('content')
    {{-- <marquee direction="left" scrollamount="8" align="center">SELAMAT DATANG DI APLIKASI RACKING <br> UTAMAKAN KESELAMATAN KERJA</marquee> --}}
    <div class="card-header">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <form action="{{ route('stok') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Tanggal</label>
                            <input type="date" class="form-control" name="date" id="date"
                                value="{{ $date }}">
                        </div>
                        <div class="col-md-4">
                            <label for="" class="text-white">Filter</label> <br>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table id="add-row" class="table table-sm table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="align-middle text-center">Part Name</th>
                    <th class="align-middle text-center">Stok BC</th>
                    <th class="align-middle text-center">Total OK</th>
                    <th class="align-middle text-center">Total NG</th>
                    <th class="align-middle text-center">Stok</th>
                    <th class="align-middle text-center">Total Kirim</th>
                    <th class="align-middle text-center">No Kartu</th>
                    <th class="align-middle text-center">Kirim Painting</th>
                    <th class="align-middle text-center">Kirim Assy</th>
                    <th class="align-middle text-center">Kirim PPIC</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($stok as $row)
                    <tr>
                        <td>
                            <center> {{ $loop->iteration }} </center>
                        </td>
                        <td>{{ $row->part_name }}</td>
                        <td align="center">{{ $row->stok_bc }}</td>
                        <td align="center">{{ $row->total_ok }} </td>
                        <td align="center">{{ $row->total_ng }} </td>
                        <td align="center">{{ $row->stok }} </td>
                        <td align="center">{{ $row->total_kirim }}</td>
                        {{-- <td>{{ $row->getTotal() }} </td> --}}
                        <td align="center">{{ $row->no_kartu ?? 0 }} </td>
                        <td align="center">{{ $row->kirim_painting ?? 0 }} </td>
                        <td align="center">{{ $row->kirim_assy ?? 0 }} </td>
                        <td align="center">{{ $row->kirim_ppic ?? 0 }}</td>
                    </tr>
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <center> <b> Total </b> </center>
                    </td>
                    <td align="center"> <b> {{ $sum_stok_bc }}</b></td>
                    <td align="center"> <b> {{ $sum_total_ok }}</b></td>
                    <td align="center"> <b> {{ $sum_total_ng }}</b></td>
                    <td align="center"> <b> {{ $sum_stok }}</b></td>
                    <td align="center"> <b> {{ $sum_total_kirim }} </b> </td>
                    <td align="center"></td>
                    <td align="center"> <b> {{ $sum_kirim_painting }}</b></td>
                    <td align="center"> <b> {{ $sum_kirim_assy }}</b></td>
                    <td align="center"> <b> {{ $sum_kirim_ppic }}</b></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <br>
    </div>
@endsection

@push('page-script')
    <script src="{{ asset('assets/modules/sweetalert/sweetalert.min.js') }}"></script>
@endpush

@push('after-script')
    <script>
        $(".swal-confirm").click(function(e) {
            id = e.target.dataset.id;
            swal({
                    title: 'Hapus data? ',
                    text: 'Setelah dihapus data tidak dapat dikembalikan',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // swal('Poof! Your imaginary file has been deleted!', {
                        // icon: 'success',
                        // });
                        $(`#delete${id}`).submit();
                    } else {
                        // swal('Your imaginary file is safe!');
                    }
                });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#add-row").DataTable({
                "responsive": false,
                "lengthChange": true,
                "autoWidth": false,
                "pageLength": 75,
                "lengthMenu": [
                    [10, 25, 50, 75, -1],
                    [10, 25, 50, 75, "All"]
                ],
                "buttons": ["excel", "pdf", "print"],
                scrollY: "700px",
                scrollX: true,
                scrollCollapse: true,
                paging: false
            }).buttons().container().appendTo('#add-row_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush

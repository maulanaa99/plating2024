@extends('layout.master')
@push('page-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        input[type="number"] {
            /* width: 90%; */
            height: 100%;
            font-size: 30pt;
        }

        .jenis-ng {
            height: 150px;
             !important
        }

        input[type="text"] {
            font-size: 24pt;
            height: 100%;
        }

        input[type="date"] {
            font-size: 24pt;
            height: 100%;
        }

        input[type="time"] {
            font-size: 24pt;
            height: 100%;
        }

        label {
            font-size: 24px;
        }

        .spinner {
            display: none;
        }

        .btn-besar {
            font-size: 24pt;
            height: 6rem;
            width: 200pt;
        }
    </style>
@endpush
@section('title')
    Tambah Data Kensa
@endsection

@section('breadcrumb')
    @parent
    <li class="active"> > Kensa > Input Data Inspeksi</li>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->-
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Data Kensa</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="quickForm" action="{{ route('kensa.simpan') }}" method="POST" class="form-master">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label> No. </label>
                                                <input type="text" class="form-control" value="{{ $hit_data_kensa + 1 }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <input type="hidden" value="<?= url('/') ?>" id="base_path" />
                                            <div class="form-group">
                                                <label>Tanggal</label>
                                                <input type="date" name="tanggal_k"
                                                    value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Jam</label>
                                                <input type="time" name="waktu_k"
                                                    value="{{ Carbon\Carbon::now()->format('H:i:s') }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="">Part Number</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="part_number"
                                                        name="no_part">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                                            data-target="#modal-item"> <i
                                                                class="fas fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label> Part Name</label>
                                                <input type="text" name="part_name" id="part_name" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>

                                        {{-- <div id="detail_part"></div> --}}

                                        <div class="row">
                                            <input type="hidden" id="id_masterdata" name="id_masterdata" value=""
                                                class="typeahead form-control" placeholder="Masukkan Nama Part" readonly>

                                            <input type="hidden" name="id_plating" id="id_plating" value="" readonly
                                                class="form-control">


                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>Stok BC</label>
                                                    <input type="text" name="stok_bc" id="stok_bc" value=""
                                                        readonly class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>No. Bar</label>
                                                    <input type="text" name="no_bar" id="no_bar" readonly
                                                        value="{{ old('no_bar') }}" placeholder="Masukkan No. Bar"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <Label> Qty Bar</Label>
                                                <div class="input-group">
                                                    <input type="text" id="qty_bar" name="qty_bar" value=""
                                                        readonly onkeyup="sum();" class="form-control">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Pcs </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Cycle</label>
                                                    <input type="text" name="cycle" id="cycle"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6 mt-2">
                                            <div class="row">
                                                <p class="font-italic"> <b> NG Plating </b> </p>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label> Nikel :</Label>
                                                        <input type="number" id="nikel" name="nikel"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Moyo :</Label>
                                                        <input type="number" id="moyo" name="moyo"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Hanazaki :</Label>
                                                        <input type="number" id="hanazaki" name="hanazaki"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>



                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Butsu :</Label>
                                                        <input type="number" id="butsu" name="butsu"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Fukure :</Label>
                                                        <input type="number" id="fukure" name="fukure"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Kizu :</Label>
                                                        <input type="number" id="kizu" name="kizu"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>



                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Hadare :</Label>
                                                        <input type="number" id="hadare" name="hadare"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Crack :</Label>
                                                        <input type="number" id="crack" name="crack"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Kaburi :</Label>
                                                        <input type="number" id="kaburi" name="kaburi"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>





                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Hage :</Label>
                                                        <input type="number" id="hage" name="hage"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>



                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Henkei :</Label>
                                                        <input type="number" id="henkei" name="henkei"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Shiromoya :</Label>
                                                        <input type="number" id="shiromoya" name="shiromoya"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>



                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Shimi :</Label>
                                                        <input type="number" id="shimi" name="shimi"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Pitto :</Label>
                                                        <input type="number" id="pitto" name="pitto"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Misto :</Label>
                                                        <input type="number" id="misto" name="misto"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Other :</Label>
                                                        <input type="number" id="other" name="other"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-2">
                                            <div class="row">
                                                <p class="font-italic"> <b> NG Molding </b> </p>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Gores :</Label>
                                                        <input type="number" id="gores" name="gores"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Burry :</Label>
                                                        <input type="number" id="burry" name="burry"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Regas :</Label>
                                                        <input type="number" id="regas" name="regas"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Others :</Label>
                                                        <input type="number" id="others" name="others"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Silver :</Label>
                                                        <input type="number" id="silver" name="silver"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label>Hike :</Label>
                                                        <input type="number" id="hike" name="hike"
                                                            min="0" onInput="sum()"
                                                            class="form-control border border-dark">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="hidden" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <Label>Total OK</Label>
                                                        <div class="input-group">
                                                            <input type="text" id="total_ok" name="total_ok" required
                                                                class="form-control border border-dark">
                                                            <div class="input-group-prepend border border-dark">
                                                                <span class="input-group-text">Pcs </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 ">
                                                    <div class="row">
                                                        <Label>Total NG</Label>
                                                        <div class="input-group">
                                                            <input type="text" id="hasil" name="total_ng" required
                                                                readonly class="form-control border border-dark">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text border border-dark">Pcs
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mt-2">
                                                    <div class="row">
                                                        <Label>%Total OK</Label>
                                                        <div class="input-group">
                                                            <input type="text" id="persenok" name="p_total_ok"
                                                                readonly class="form-control border border-dark">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text border border-dark"> %
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mt-2">
                                                    <div class="row">
                                                        <Label>%Total NG</Label>
                                                        <div class="input-group">
                                                            <input type="text" id="persenng" name="p_total_ng"
                                                                readonly class="form-control border border-dark">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text border border-dark"> %
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mt-2">
                                                    <div class="row">
                                                        <Label>Keterangan</Label>
                                                        <div class="input-group">
                                                            <input type="text" name="keterangan" id="keterangan"
                                                                class="form-control border border-dark">
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="date_time">
                                            </div>
                                        </div>



                                        <br>


                                    </div>

                                </div>
                                <div class="container mt-3 mb-3 text-center">
                                    <button class="btn btn-primary button-prevent btn-besar" type="submit">
                                        <!-- spinner-border adalah component bawaan bootstrap untuk menampilakn roda berputar  -->
                                        <div class="spinner"><i role="status"
                                                class="spinner-border spinner-border-sm"></i> Simpan </div>
                                        <div class="hide-text"> <i class="fa fa-save"></i> Simpan</div>
                                    </button>
                                    {{-- <button class="btn btn-primary mr-1" type="submit"><i class="fa fa-save"></i>
                                        Submit</button> --}}
                                    <button class="btn btn-danger mr-1 btn-besar" type="reset"> <i
                                            class="fa fa-trash-restore"></i> Reset</button>
                                    <a href="{{ route('kensa') }}"
                                        class="btn btn-icon icon-left btn-warning btn-besar text-center">
                                        <i class="fas fa-arrow-left"></i> Kembali </a>

                                </div>

                                <div class="modal" id="modal-item" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Pilih Part Before Check!</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body table-responsive">
                                                <table class="table table-sm border" id="add-row">
                                                    <thead>
                                                        <tr>
                                                            <th class="align-middle text-center">ID</th>
                                                            <th class="align-middle text-center">Tanggal</th>
                                                            <th class="align-middle text-center">No. Bar</th>
                                                            <th class="align-middle text-center">Part Name</th>
                                                            <th class="align-middle text-center">Cycle</th>
                                                            <th class="align-middle text-center">Qty Bar</th>
                                                            <th class="align-middle text-center">Stok BC</th>
                                                            <th class="align-middle text-center">Keterangan</th>
                                                            <th class="align-middle text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($platings as $plating)
                                                            <tr>
                                                                <td align="center">{{ $plating->id }}</td>
                                                                <td align="center">{{ $plating->tanggal_u }}</td>
                                                                <td align="center">{{ $plating->no_bar }}</td>
                                                                <td align="center">{{ $plating->part_name }}</td>
                                                                <td align="center">{{ $plating->cycle }}</td>
                                                                <td align="center">{{ $plating->qty_aktual }}</td>
                                                                <td align="center">{{ $plating->stok_bc }}</td>
                                                                <td align="center">{{ $plating->keterangan }}</td>
                                                                <td align="center"> <a class="btn btn-xs btn-primary"
                                                                        id="select" data-id="{{ $plating->id }}"
                                                                        data-id_masterdata="{{ $plating->id_masterdata }}"
                                                                        data-part_number="{{ $plating->no_part }}"
                                                                        data-tanggal="{{ $plating->tanggal_u }}"
                                                                        data-no_bar="{{ $plating->no_bar }}"
                                                                        data-part_name="{{ $plating->part_name }}"
                                                                        data-cycle="{{ $plating->cycle }}"
                                                                        data-stok_bc="{{ $plating->stok_bc }}"
                                                                        data-keterangan="{{ $plating->keterangan }}"
                                                                        data-qty_bar="{{ $plating->qty_aktual }}">
                                                                        <i class="fa fa-check"></i> Select </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div>
@endsection

@push('page-script')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script> --}}
@endpush

@push('after-script')
    @include('sweetalert::alert')

    <script>
        $(document).ready(function() {
            $(document).on('click', '#select', function() {
                var id = $(this).data('id');
                var id_masterdata = $(this).data('id_masterdata');
                var part_number = $(this).data('part_number');
                var no_bar = $(this).data('no_bar');
                var part_name = $(this).data('part_name');
                var qty_bar = $(this).data('qty_bar');
                var cycle = $(this).data('cycle');
                var stok_bc = $(this).data('stok_bc');
                var keterangan = $(this).data('keterangan');

                $('#id_plating').val(id);
                $('#id_masterdata').val(id_masterdata);
                $('#part_number').val(part_number);
                $('#no_bar').val(no_bar);
                $('#part_name').val(part_name);
                $('#qty_bar').val(qty_bar);
                $('#cycle').val(cycle);
                $('#stok_bc').val(stok_bc);
                $('#keterangan').val(keterangan);
                $('#modal-item').modal('hide');
            });
        });
        $("#add-row").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, 75, -1],
                [10, 25, 50, 75, "All"]
            ],
            "orderable": "true",
            "columnDefs": [{
                "targets": [7],
                "orderable": false,
            }],
        });
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('change', '#id_masterdata', function() {
                var id_masterdata = $(this).val();
                var a = $(this).parent();
                $.ajax({
                    type: 'get',
                    url: '{{ route('cariPart') }}',
                    data: {
                        'id': id_masterdata
                    },
                    success: function(data) {
                        $('#no_part').val(data.no_part);
                        $('#part_name').val(data.part_name);
                        $('#katalis').val(data.katalis);
                        $('#grade_color').val(data.grade_color);
                        $('#channel').val(data.channel);
                        $('#qty_bar').val(data.qty_bar);
                        $('#stok_bc').val(data.stok_bc);
                    },
                    error: function() {

                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#quickForm').on('submit', function() {
                $('.button-prevent').attr('disabled', 'true');
                $('.spinner').show();
                $('.hide-text').hide();
            })
        });
    </script>

    <script>
        function sum() {
            var nikel = document.getElementById('nikel').value;
            var butsu = document.getElementById('butsu').value;
            var hadare = document.getElementById('hadare').value;
            var hage = document.getElementById('hage').value;
            var moyo = document.getElementById('moyo').value;
            var fukure = document.getElementById('fukure').value;
            var crack = document.getElementById('crack').value;
            var henkei = document.getElementById('henkei').value;
            var hanazaki = document.getElementById('hanazaki').value;
            var kizu = document.getElementById('kizu').value;
            var kaburi = document.getElementById('kaburi').value;
            var shiromoya = document.getElementById('shiromoya').value;
            var shimi = document.getElementById('shimi').value;
            var pitto = document.getElementById('pitto').value;
            var misto = document.getElementById('misto').value;
            var other = document.getElementById('other').value;
            var gores = document.getElementById('gores').value;
            var regas = document.getElementById('regas').value;
            var silver = document.getElementById('silver').value;
            var hike = document.getElementById('hike').value;
            var burry = document.getElementById('burry').value;
            var others = document.getElementById('others').value;
            var totalok = document.getElementById('total_ok').value;
            var qtybar = document.getElementById('qty_bar').value;
            var persenok = document.getElementById('persenok').value;
            var persenng = document.getElementById('persenng').value;
            var result = (parseInt(nikel) || 0) + (parseInt(butsu) || 0) + (parseInt(hadare) || 0) + (parseInt(hage) || 0) +
                (parseInt(moyo) || 0) + (parseInt(fukure) || 0) + (parseInt(crack) || 0) + (parseInt(henkei) || 0) + (
                    parseInt(hanazaki) || 0) +
                (parseInt(kizu) || 0) + (parseInt(
                    kaburi) || 0) + (parseInt(shiromoya) || 0) + (parseInt(shimi) || 0) + (parseInt(pitto) || 0) + (
                    parseInt(misto) || 0) +
                (parseInt(other) || 0) + (parseInt(gores) || 0) + (parseInt(regas) || 0) + (parseInt(silver) || 0) + (
                    parseInt(hike) || 0) + (parseInt(
                    burry) || 0) +
                (parseInt(others) || 0);
            var hasil = parseInt(qtybar) - parseInt(result)
            var persenoks = (parseInt(hasil) / parseInt(qtybar)) * 100
            var persenokq = persenoks.toFixed(2);
            var persenngs = (parseInt(result) / parseInt(qtybar)) * 100
            var persenngq = persenngs.toFixed(2);
            if (!isNaN(result)) {
                document.getElementById('hasil').value = result;
                document.getElementById('total_ok').value = hasil;
                document.getElementById('persenok').value = persenokq;
                document.getElementById('persenng').value = persenngq;
            }
        }
    </script>
@endpush

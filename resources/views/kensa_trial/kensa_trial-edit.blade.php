@extends('layout.master')

@section('title')
    Edit Data Kensa Trial
@endsection

@push('page-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        input[type="number"] {
            /* width: 90%; */
            height: 100%;
            font-size: 24pt;
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
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Data Kensa</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="quickForm" action="{{ route('tr.kensa.update', $kensa_trial->id) }}" method="POST"
                        class="form-master">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">

                                            <input type="hidden" value="<?= url('/') ?>" id="base_path" />

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <input type="date" name="tanggal_k"
                                                        @if (old('tanggal_k')) value="{{ old('tanggal_k') }}"
                                                        @else
                                                            value="{{ $kensa_trial->tanggal_k }}" @endif
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Jam</label>
                                                    <input type="time" name="waktu_k"
                                                        @if (old('waktu_k')) value="{{ old('waktu_k') }}"
                                                        @else
                                                            value="{{ $kensa_trial->waktu_k }}" @endif
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Part Name</label>
                                                    <input type="text" name="part_name" id="part_name"
                                                        @if (old('part_name')) value="{{ old('part_name') }}"
                                                    @else
                                                        value="{{ $kensa_trial->part_name }}" @endif
                                                        class="form-control" readonly>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <input type="hidden" id="no_part" name="no_part"
                                                    @if (old('no_part')) value="{{ old('no_part') }}"
                                                @else
                                                    value="{{ $kensa_trial->no_part }}" @endif
                                                    class="form-control"readonly>


                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <Label> Qty Bar</Label>
                                                        <input type="text" id="qty_bar" name="qty_bar" onkeyup="sum();"
                                                            readonly
                                                            @if (old('qty_bar')) value="{{ old('qty_bar') }}"
                                                    @else
                                                        value="{{ $kensa_trial->qty_bar }}" @endif
                                                            class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>No. Bar</label>
                                                        <input type="text" name="no_bar" readonly
                                                            @if (old('no_bar')) value="{{ old('no_bar') }}"
                                                    @else
                                                        value="{{ $kensa_trial->no_bar }}" @endif
                                                            class="form-control" placeholder="Masukkan No. Bar">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Cycle </label>
                                                        <input type="text" name="cycle" readonly
                                                            @if (old('cycle')) value="{{ old('cycle') }}"
                                                        @else
                                                            value="{{ $kensa_trial->cycle }}" @endif
                                                            class="form-control">
                                                    </div>
                                                </div>




                                            </div>

                                            <div class="col-md-6">
                                                <div class="row">
                                                    <p class="font-italic"> <b> NG Plating </b> </p>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Nikel</Label>
                                                            <input type="number" id="nikel" name="nikel"
                                                                onkeyup="sum()"
                                                                @if (old('nikel')) value="{{ old('nikel') }}"
                                                            @else
                                                                value="{{ $kensa_trial->nikel }}" @endif
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Moyo</Label>
                                                            <input type="number" id="moyo" name="moyo"
                                                                @if (old('moyo')) value="{{ old('moyo') }}"
                                                            @else
                                                                value="{{ $kensa_trial->moyo }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Hanazaki</Label>
                                                            <input type="number" id="hanazaki" name="hanazaki"
                                                                @if (old('hanazaki')) value="{{ old('hanazaki') }}"
                                                            @else
                                                                value="{{ $kensa_trial->hanazaki }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Butsu</Label>
                                                            <input type="number" id="butsu" name="butsu"
                                                                onkeyup="sum();"
                                                                @if (old('butsu')) value="{{ old('butsu') }}"
                                                            @else
                                                                value="{{ $kensa_trial->butsu }}" @endif
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Fukure</Label>
                                                            <input type="number" id="fukure" name="fukure"
                                                                @if (old('fukure')) value="{{ old('fukure') }}"
                                                            @else
                                                                value="{{ $kensa_trial->fukure }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Kizu</Label>
                                                            <input type="number" id="kizu" name="kizu"
                                                                @if (old('kizu')) value="{{ old('kizu') }}"
                                                            @else
                                                                value="{{ $kensa_trial->kizu }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Hadare</Label>
                                                            <input type="number" id="hadare" name="hadare"
                                                                @if (old('hadare')) value="{{ old('hadare') }}"
                                                            @else
                                                                value="{{ $kensa_trial->hadare }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Crack</Label>
                                                            <input type="number" id="crack" name="crack"
                                                                @if (old('crack')) value="{{ old('crack') }}"
                                                            @else
                                                                value="{{ $kensa_trial->crack }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Kaburi</Label>
                                                            <input type="number" id="kaburi" name="kaburi"
                                                                @if (old('kaburi')) value="{{ old('kaburi') }}"
                                                            @else
                                                                value="{{ $kensa_trial->kaburi }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Hage</Label>
                                                            <input type="number" id="hage" name="hage"
                                                                @if (old('hage')) value="{{ old('hage') }}"
                                                            @else
                                                                value="{{ $kensa_trial->hage }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Henkei</Label>
                                                            <input type="number" id="henkei" name="henkei"
                                                                @if (old('henkei')) value="{{ old('henkei') }}"
                                                            @else
                                                                value="{{ $kensa_trial->henkei }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Shiromoya</Label>
                                                            <input type="number" id="shiromoya" name="shiromoya"
                                                                onchange="sum();" value="{{ 0 }}"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Shimi</Label>
                                                            <input type="number" id="shimi" name="shimi"
                                                                onchange="sum();" value="{{ 0 }}"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Pitto</Label>
                                                            <input type="number" id="pitto" name="pitto"
                                                                onchange="sum();" value="{{ 0 }}"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Misto</Label>
                                                            <input type="number" id="misto" name="misto"
                                                                onchange="sum();" value="{{ 0 }}"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Other</Label>
                                                            <input type="number" id="other" name="other"
                                                                @if (old('other')) value="{{ old('other') }}"
                                                            @else
                                                                value="{{ $kensa_trial->other }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="row">
                                                    <p class="font-italic"> <b> NG Molding </b> </p>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Gores</Label>
                                                            <input type="number" id="gores" name="gores"
                                                                @if (old('gores')) value="{{ old('gores') }}"
                                                            @else
                                                                value="{{ $kensa_trial->gores }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Burry</Label>
                                                            <input type="number" id="burry" name="burry"
                                                                @if (old('burry')) value="{{ old('burry') }}"
                                                            @else
                                                                value="{{ $kensa_trial->burry }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{-- <Label>Burry</Label> --}}
                                                            <input type="hidden" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Regas</Label>
                                                            <input type="number" id="regas" name="regas"
                                                                @if (old('regas')) value="{{ old('regas') }}"
                                                            @else
                                                                value="{{ $kensa_trial->regas }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Others</Label>
                                                            <input type="number" id="others" name="others"
                                                                @if (old('others')) value="{{ old('others') }}"
                                                            @else
                                                                value="{{ $kensa_trial->others }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            {{-- <Label>Burry</Label> --}}
                                                            <input type="hidden" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Silver</Label>
                                                            <input type="number" id="silver" name="silver"
                                                                @if (old('silver')) value="{{ old('silver') }}"
                                                            @else
                                                                value="{{ $kensa_trial->silver }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            {{-- <Label>Burry</Label> --}}
                                                            <input type="hidden" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <Label>Hike</Label>
                                                            <input type="number" id="hike" name="hike"
                                                                @if (old('hike')) value="{{ old('hike') }}"
                                                            @else
                                                                value="{{ $kensa_trial->hike }}" @endif
                                                                onkeyup="sum();" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            {{-- <Label>Burry</Label> --}}
                                                            <input type="hidden" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mt-3">
                                                        <div class="form-group">
                                                            <Label>Total OK</Label>
                                                            <div class="input-group">
                                                                <input type="number" id="total_ok" name="total_ok"
                                                                    @if (old('total_ok')) value="{{ old('total_ok') }}"
                                                                @else
                                                                    value="{{ $kensa_trial->total_ok }}" @endif
                                                                    onkeyup="sum();" class="form-control">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"> PCS </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mt-3">
                                                        <div class="form-group">
                                                            <Label>Total NG</Label>
                                                            <div class="input-group">
                                                                <input type="number" id="hasil" name="total_ng"
                                                                    @if (old('total_ng')) value="{{ old('total_ng') }}"
                                                                @else
                                                                    value="{{ $kensa_trial->total_ng }}" @endif
                                                                    class="form-control">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"> PCS </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <Label>%Total OK</Label>
                                                            <div class="input-group">
                                                                <input type="number" id="persenok" name="p_total_ok"
                                                                    readonly
                                                                    @if (old('p_total_ok')) value="{{ old('p_total_ok') }}"
                                                                @else
                                                                    value="{{ $kensa_trial->p_total_ok }}" @endif
                                                                    onkeyup="sum()" class="form-control">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"> % </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <Label>%Total NG</Label>
                                                            <div class="input-group">
                                                                <input type="number" id="persenng" name="p_total_ng"
                                                                    readonly
                                                                    @if (old('p_total_ng')) value="{{ old('p_total_ng') }}"
                                                                @else
                                                                    value="{{ $kensa_trial->p_total_ng }}" @endif
                                                                    onkeyup="sum()" class="form-control">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"> % </span>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mt-2">
                                                        <div class="row">
                                                            <Label>Keterangan</Label>
                                                            <div class="input-group">
                                                                <input type="text" name="keterangan"
                                                                    @if (old('keterangan')) value="{{ old('keterangan') }}"
                                                                @else
                                                                    value="{{ $kensa_trial->keterangan }}" @endif
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="text-center mt-3">
                                            <button class="btn btn-primary mr-1 btn-besar" type="submit"> <i
                                                    class="fa fa-save"></i> Submit</button>
                                            <button class="btn btn-danger mr-1 btn-besar " type="reset"> <i
                                                    class="fa fa-trash-restore"></i> Reset</button>
                                            <a href="#" class="btn btn-icon icon-left btn-warning btn-besar">
                                                <i class="fas fa-arrow-left"></i> Kembali</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@push('after-script')
    @include('sweetalert::alert')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.masterdata-js').select2();

            $('#id_masterdata').trigger('change');
        });

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
                    console.log(id_masterdata);
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
            var result = parseInt(nikel) + parseInt(butsu) + parseInt(hadare) + parseInt(hage) + parseInt(moyo) +
                parseInt(
                    fukure) + parseInt(crack) + parseInt(henkei) + parseInt(hanazaki) + parseInt(kizu) + parseInt(
                    kaburi) +
                parseInt(other) + parseInt(gores) + parseInt(regas) + parseInt(silver) + parseInt(hike) + parseInt(
                    burry) +
                parseInt(others);
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

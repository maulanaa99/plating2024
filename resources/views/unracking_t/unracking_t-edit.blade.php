@extends('layout.master')
@push('page-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        input[type="number"] {
            font-size: 24pt;
            height: 50pt;
            ;
        }

        input[type="text"] {
            font-size: 15pt;
            height: 50pt;
            ;
        }

        input[type="date"] {
            font-size: 18pt;
            height: 50pt;
            ;
        }

        input[type="time"] {
            font-size: 18pt;
            height: 50pt;
            ;
        }

        input[type="datetime-local"] {
            font-size: 18pt;
            height: 50pt;
            ;
        }

        .cycle-select {
            font-size: 18pt;
            height: 50pt;
            ;
        }

        label {
            font-size: 15pt;
        }

        .blink {
            animation: blinker 3s linear infinite;
        }

        @keyframes blinker {
            50% {
                background: red;
                color: white;
                /* ganti warna sesuai dengan keinginan */
            }
        }
    </style>
@endpush
@section('title')
    Tambah Data Unracking
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Data Produksi Racking</small></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <label>Part Name</label>
                                <input type="text" id="part_name" name="part_name"
                                    @if (old('part_name')) value="{{ old('part_name') }}"
                                                            @else
                                                                value="{{ $plating->part_name }}" @endif
                                    class="typeahead form-control" placeholder="Masukkan Nama Part" readonly>
                            </div>

                            {{-- <div class="col-md-12 mt-1">
                                <span> <b> Image </b></span>
                                <img style="max-width:100%;
                                    max-height:100%x;"
                                    src="{{ !empty($masterdata->image) ? url('upload/part_images/' . $masterdata->image) : url('upload/no_images2.png') }}">
                            </div> --}}

                            <div class="col-md-6">
                                <label for="">Tgl Racking</label>
                                <input type="date" name="tanggal_r"
                                    @if (old('tanggal')) value="{{ old('tanggal_r') }}"
                                                @else
                                                    value="{{ $plating->tanggal_r }}" @endif
                                    class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="">Waktu Racking</label>
                                <input type="time" name="waktu_in_r"
                                    @if (old('waktu_in')) value="{{ old('waktu_in_r') }}"
                                                @else
                                                    value="{{ $plating->waktu_in_r }}" @endif
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="">Schedule Arrived</label>
                                <input type="datetime-local" value="{{ $estimated3 }}" class="form-control" readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="">Channel</label>
                                <input type="number" id="channel" name="channel"
                                    @if (old('channel')) value="{{ old('channel') }}"
                                                    @else
                                                        value="{{ $plating->channel }}" @endif
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="">Katalis</label>
                                <input type="text" id="katalis" name="katalis"
                                    @if (old('katalis')) value="{{ old('katalis') }}"
                                                    @else
                                                        value="{{ $plating->katalis }}" @endif
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-6">
                                <label>Tgl Lot Molding</label>
                                <input type="date" name="tgl_lot_prod_mldg"
                                    @if (old('tgl_lot_prod_mldg')) value="{{ old('tgl_lot_prod_mldg') }}"
                                            @else
                                                value="{{ $plating->tgl_lot_prod_mldg }}" @endif
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-6">
                                <label>Chrome</label>
                                <input type="text" name="grade_color"
                                    @if (old('grade_color')) value="{{ old('grade_color') }}"
                                            @else
                                                value="{{ $plating->grade_color }}" @endif
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-6">
                                <label>Jenis</label>
                                <input type="text" name="jenis"
                                    @if (old('jenis')) value="{{ old('jenis') }}"
                                            @else
                                                value="{{ $plating->jenis }}" @endif
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-6">
                                <label>Kategori</label>
                                @if ($plating->kategori == 'PST')
                                    <input type="text" name="" id="" class="form-control blink"
                                        value="{{ $plating->kategori }} (TRIAL!!)">
                                @else
                                    <input type="text" name="kategori"
                                        @if (old('kategori')) value="{{ old('kategori') }}"
                                        @else
                                            value="{{ $plating->kategori }}" @endif
                                        class="form-control" readonly>
                                @endif
                            </div>

                            @if ($plating->keterangan != null)
                                <div class="col-md-6">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan"
                                        @if (old('keterangan')) value="{{ old('keterangan') }}"
                                            @else
                                                value="{{ $plating->keterangan }}" @endif
                                        class="form-control blink" readonly>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan"
                                        @if (old('keterangan')) value="{{ old('keterangan') }}"
                                                @else
                                                    value="{{ $plating->keterangan }}" @endif
                                        class="form-control" readonly>
                                </div>
                            @endif


                            @if ($plating->status == '1')
                                <div class="col-md-6">
                                    <label>Status</label>
                                    <div class="input-group">
                                        <input type="text" id="status" name="status" readonly
                                            @if (old('status')) value="{{ old('status') }}"
                                    @else
                                        value="on Process" @endif
                                            class="form-control">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-danger"><i class="fas fa-spinner fa-spin"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($plating->status == '2')
                                <div class="col-md-6">
                                    <label for="">Status</label>
                                    <div class="input-group">
                                        <input type="text" id="status" name="status" readonly
                                            @if (old('status')) value="{{ old('status') }}"
                                    @else
                                        value="Before Check" @endif
                                            class="form-control">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-info"><i
                                                    class="fas fa-hourglass-half fa-spin"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($plating->status == '3')
                                <div class="col-md-6">
                                    <label>Status</label>
                                    <div class="input-group">
                                        <input type="text" id="status" name="status" readonly
                                            @if (old('status')) value="{{ old('status') }}"
                                    @else
                                        value="Sudah di Cek" @endif
                                            class="form-control">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success"><i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif




                            <div class="text-center mt-3">
                                <a href="{{ URL::to('racking_t/edit/' . $plating->id) }}" class="btn btn-primary mt-2">
                                    <i class="fa fa-edit"> Edit Racking</i>
                                </a>
                            </div>



                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- jquery validation -->
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Data Produksi Unracking</small></h3>
                    </div>

                    <form id="quickForm" action="{{ route('unracking_t.update', $plating->id) }}" method="POST"
                        class="form-master" enctype="multipart/form-data">
                        <input type="hidden" name="next">
                        @csrf
                        @method('patch')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>ID</label>
                                    <input type="text" class="form-control" value="{{ $plating->id }}" readonly
                                        style="font-weight: bold; font-size: 20pt">
                                </div>
                                <div class="col-md-8">
                                    <label for="">Part Name</label>
                                    <input type="text" id="part_name" name="part_name"
                                        @if (old('part_name')) value="{{ old('part_name') }}"
                                                    @else
                                                        value="{{ $plating->part_name }}" @endif
                                        class="typeahead form-control" placeholder="Masukkan Nama Part" readonly>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <label>Image</label> <br>
                                    <img style="max-width:100%;
                                    max-height:100%;"
                                        src="{{ !empty($masterdata->image) ? url('upload/part_images/' . $masterdata->image) : url('upload/no_images2.png') }}">
                                </div>


                                <div class="col-md-6">
                                    <label>Tanggal Unracking</label>
                                    <input type="date" name="tanggal_u" class="form-control"
                                        @if ($plating->tanggal_u == null) value="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
                                    @elseif($plating->tanggal_u != null)
                                    value="{{ $plating->tanggal_u }}" @endif>
                                </div>

                                <div class="col-sm-6">
                                    <label>Waktu In Unracking</label>
                                    <input type="time" name="waktu_in_u" class="form-control"
                                        @if ($plating->waktu_in_u == null) value="{{ Carbon\Carbon::now()->format('H:i:s') }}"
                                        @elseif($plating->waktu_in_u != null)
                                        value="{{ $plating->waktu_in_u }}" @endif>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No. Bar</label>
                                        <input type="number" name="no_bar"
                                            @if (old('no_bar')) value="{{ old('no_bar') }}"
                                                @else
                                                    value="{{ $plating->no_bar }}" @endif
                                            class="form-control bg-yellow" placeholder="Masukkan No. Bar">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <!-- select -->
                                    <div class="form-group">
                                        <label>Cycle</label>
                                        <select name="cycle" class="form-control cycle-select">
                                            <option value="">----Pilih Cycle----</option>
                                            <option value="C1"
                                                {{ old('cycle', $plating->cycle) == 'C1' ? 'selected' : '' }}>
                                                C1</option>
                                            <option value="C2"
                                                {{ old('cycle', $plating->cycle) == 'C2' ? 'selected' : '' }}>
                                                C2</option>
                                            <option value="CS"
                                                {{ old('cycle', $plating->cycle) == 'CS' ? 'selected' : '' }}>
                                                CS</option>
                                            <option value="FS"
                                                {{ old('cycle', $plating->cycle) == 'FS' ? 'selected' : '' }}>
                                                FS</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="">Qty</label>
                                    <div class="input-group">
                                        <input type="number" id="qty_bar" name="qty_bar"
                                            @if (old('qty_bar')) value="{{ old('qty_bar') }}"
                                        @else
                                            value="{{ $plating->qty_bar }}" @endif
                                            class="form-control bg-green" class="form-control">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> Pcs
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="">Qty Aktual</label>
                                    <div class="input-group">
                                        <input type="number" id="qty_aktual" name="qty_aktual" autofocus
                                            @if (old('qty_aktual')) value="{{ old('qty_aktual') }}"
                                        @elseif ($plating->qty_bar == $plating->qty_aktual || $plating->qty_aktual == '')
                                        value="{{ $plating->qty_bar }}"
                                        @elseif ($plating->qty_aktual != $plating->qty_bar)
                                            value="{{ $plating->qty_aktual }}" @endif
                                            class="form-control">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> Pcs
                                            </span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ URL::to('unracking_t/edit/' . $previous) }}"
                                    class="btn btn-outline-secondary"> <i class="fas fa-arrow-left"></i>
                                    Previous</a>
                                <button class="btn btn-primary" type="submit"> <i class="fas fa-save"></i>
                                    Submit</button>
                                <button class="btn btn-danger" type="reset"> <i class="fas fa-trash-restore"></i>
                                    Reset</button>
                                <a href="{{ URL::to('unracking_t/edit/' . $next) }}" class="btn btn-outline-success">
                                    Next
                                    <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('page-script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    @endpush

    @push('after-script')
        @include('sweetalert::alert')
        <script>
            $(document).on('click', '.btn-next-submit', function(e) {
                e.preventDefault();
                $('input[name="next"]').val($(this).data('next'));
                $('form').submit();
            })
        </script>
    @endpush

<?php

namespace App\Http\Controllers;

use App\Models\MasterData;
use App\Models\Ng_Racking;
use App\Models\Pinbosh_Tertinggal;
use App\Models\Plating;
use App\Models\racking_t;
use App\Models\Rencana_Produksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use RealRashid\SweetAlert\Facades\Alert;

class RackingController_T extends Controller
{

    // =================================================== RACKING =========================================================================

    //tampil data
    public function index(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $racking = DB::table('plating')
            ->leftJoin('masterdata', function ($join) {
                $join->on('masterdata.id', '=', 'plating.id_masterdata');
            })
            ->select('plating.id', 'plating.id_masterdata', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.no_bar', 'masterdata.no_part', 'masterdata.part_name', 'plating.katalis', 'plating.channel', 'plating.grade_color', 'plating.qty_bar', 'plating.cycle', 'plating.tgl_lot_prod_mldg', 'plating.created_by', 'plating.kategori', 'plating.keterangan', 'masterdata.jenis')
            ->orderBy('tanggal_r', 'asc')
            ->orderBy('waktu_in_r', 'asc')
            ->where('tanggal_r', '=', $date)
            ->get();

        $masterdata = MasterData::all();

        $start_produksi = racking_t::where('tanggal_r', '=', $date)->min('waktu_in_r');
        $cycle_stop = racking_t::where('tanggal_r', '=', $date)->max('waktu_in_r');
        // dd($cycle_stop);

        return view('racking_t.racking_t', compact('racking', 'masterdata', 'date'));
    }

    public function getNewDateRacking(Request $request)
    {
        $date = Carbon::parse($request->tanggal_r)->format('Y-m-d');
        // $id_masterdata['id_masterdata'] = $request->id_masterdata;

        $number_prod = Plating::where('tanggal_r', '=', $date)->get();
        $q = $number_prod->first()->pengirimans()->where('tgl_kanban', '=', $date)->orderBy('id', 'desc')->first();
        $kode = $q ? $q->production_number + 1 : '01';

        return view('kensa.print-kanban-ajax', compact('ajax_barang', 'kode'));
    }

    //tambah data
    public function tambah(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $racking = racking_t::join('masterdata', 'masterdata.id', '=', 'plating.id_masterdata')
            ->select('plating.*', 'masterdata.part_name', 'masterdata.qty_bar')
            ->orderBy('tanggal_r', 'desc')
            ->get();

        // $number_prod = Plating::where('tanggal_r', '=', $date)->count();
        // $kode = $number_prod ? $number_prod->production_number + 1 : '01';
        // dd($kode);
        // $q = $number_prod->first()->where('tanggal_r', '=', $date)->orderBy('id', 'desc')->first();

        // $latestData = racking_t::latest()->first();
        // dd($latestData);
        // $newDataTimestamp = Carbon::parse($latestData->waktu_in_r)->addMinutes(4)->addSeconds(random_int(0, 59))->format('H:i:s');

        // $kode = date('Ymd') . str_pad(racking_t::getNextNumber(), 3, '0', STR_PAD_LEFT);

        $masterdata = MasterData::all();

        $hit_data_racking = racking_t::where('tanggal_r', '=', $date)->count();
        $cycle = 75;
        

        $masterdata = MasterData::all();
        return view('racking_t.racking_t-tambah', compact(
            'racking',
            'masterdata',
            'hit_data_racking',
            'cycle',
                        'masterdata',
        ));
    }

    //simpan data
    public function simpan(Request $request)
    {
        if ($request->kategori == 'PST') {

            $racking = new racking_t();

            $racking->id_masterdata = $request->id_masterdata;
            $racking->tanggal_r = $request->tanggal_r;
            $racking->waktu_in_r = $request->waktu_in_r;
            $racking->no_bar = $request->no_bar;
            $racking->part_name = $request->part_name;
            $racking->no_part = $request->no_part;
            $racking->katalis = $request->katalis;
            $racking->channel = $request->channel;
            $racking->grade_color = $request->grade_color;
            $racking->qty_bar = $request->qty_bar;
            $racking->tgl_lot_prod_mldg = $request->tgl_lot_prod_mldg;
            $racking->cycle = $request->cycle;
            $racking->kategori = $request->kategori;
            $racking->keterangan = $request->keterangan;
            $racking->created_by = Auth::user()->name;
            $racking->created_at = Carbon::now();
            $racking->status = '4';
            $racking->save();

            return redirect()->route('racking_t.tambah', compact('racking'))->with('toast_success', 'Data Trial Berhasil Disimpan!');
        } else {


            $racking = new racking_t();

            $racking->id_masterdata = $request->id_masterdata;
            $racking->tanggal_r = $request->tanggal_r;
            $racking->waktu_in_r = $request->waktu_in_r;
            $racking->no_bar = $request->no_bar;
            $racking->part_name = $request->part_name;
            $racking->no_part = $request->no_part;
            $racking->katalis = $request->katalis;
            $racking->channel = $request->channel;
            $racking->grade_color = $request->grade_color;
            $racking->qty_bar = $request->qty_bar;
            $racking->tgl_lot_prod_mldg = $request->tgl_lot_prod_mldg;
            $racking->cycle = $request->cycle;
            $racking->kategori = $request->kategori;
            $racking->keterangan = $request->keterangan;
            $racking->created_by = Auth::user()->name;
            $racking->created_at = Carbon::now();
            $racking->status = '1';

            $racking->save();

            return redirect()->route('racking_t.tambah', compact('racking'))->with('toast_success', 'Data Berhasil Disimpan!');
        }
    }

    //edit data
    public function edit(Request $request, $id)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');

        $plating = racking_t::findOrFail($id);

        $previous = racking_t::where('id', '<', $plating->id)->max('id');
        $next = racking_t::where('id', '>', $plating->id)->min('id');

        $masterdata = MasterData::all();

        return view('racking_t.racking_t-edit', compact('plating', 'masterdata', 'date'))->with('previous', $previous)->with('next', $next);
    }

    //update data
    public function update(Request $request, $id)
    {
        $plating = racking_t::find($id);
        $masterdata = MasterData::all();

        $previous = racking_t::where('id', '<', $plating->id)->max('id');
        $next = racking_t::where('id', '>', $plating->id)->min('id');

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $hit_data_racking = racking_t::where('tanggal_r', '=', $date)->count();
        if ($plating->qty_aktual > 1) {
            $plating->channel = $request->channel;
            $plating->keterangan = $request->keterangan;
            $plating->save();
            Alert::Warning('Gagal', 'Part Sudah Di Unracking!');
            return redirect()->route('racking_t.edit', compact('plating', 'hit_data_racking', 'date', 'masterdata', 'previous', 'next', 'id'));
        } elseif ($request->kategori == 'PST') {
            $plating->id_masterdata = $request->id_masterdata;
            $plating->tanggal_r = $request->tanggal_r;
            $plating->no_bar = $request->no_bar;
            $plating->part_name = $request->part_name;
            $plating->no_part = $request->no_part;
            $plating->katalis = $request->katalis;
            $plating->channel = $request->channel;
            $plating->grade_color = $request->grade_color;
            $plating->qty_bar = $request->qty_bar;
            $plating->waktu_in_r = $request->waktu_in_r;
            $plating->tgl_lot_prod_mldg = $request->tgl_lot_prod_mldg;
            $plating->cycle = $request->cycle;
            $plating->kategori = $request->kategori;
            $plating->keterangan = $request->keterangan;
            $plating->status = '4';
            $plating->save();
            Alert::Success('Success', 'Part Trial Berhasil di Update!');
        } else {
            $plating->id_masterdata = $request->id_masterdata;
            $plating->tanggal_r = $request->tanggal_r;
            $plating->no_bar = $request->no_bar;
            $plating->part_name = $request->part_name;
            $plating->no_part = $request->no_part;
            $plating->katalis = $request->katalis;
            $plating->channel = $request->channel;
            $plating->grade_color = $request->grade_color;
            $plating->qty_bar = $request->qty_bar;
            $plating->waktu_in_r = $request->waktu_in_r;
            $plating->tgl_lot_prod_mldg = $request->tgl_lot_prod_mldg;
            $plating->cycle = $request->cycle;
            $plating->kategori = $request->kategori;
            $plating->keterangan = $request->keterangan;
            $plating->save();
            Alert::Success('Berhasil', 'Data Berhasil Di Update!');
        }

        return View::make('racking_t.racking_t-edit', compact('plating', 'masterdata', 'date', 'hit_data_racking'))->with('previous', $previous)->with('next', $next)->with('message', 'Data berhasil di update');
    }

    public function ajaxRacking(Request $request)
    {
        $id_masterdata['id_masterdata'] = $request->id_masterdata;
        $ajax_racking = MasterData::where('id', $id_masterdata)->get();

        return view('racking_t.racking_t-ajax', compact('ajax_racking'));
    }

    public function delete($id)
    {
        $plating = racking_t::find($id);
        $unracking = racking_t::where('id_masterdata', '=', $plating->id_masterdata)->where('id', '=', $plating->id)->first();

        if ($unracking->qty_aktual == '') {
            $masterdata = MasterData::find($plating->id_masterdata);
            $masterdata->stok_bc = $masterdata->stok_bc - $plating->qty_aktual;
            $masterdata->save();
            $plating->delete();
            // return redirect()->route('racking_t')->with('success', 'Data Berhasil Dihapus!');
            return response()->json([
                'success' => true
            ]);
        } else
            return response()->json([
                'success' => false
            ]);
        // return redirect()->route('racking_t')->with('errors', 'Data Gagal Dihapus!');
    }

    public function utama(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $start_produksi = $start_produksi = racking_t::where('tanggal_r', '=', $date)->min('waktu_in_r');
        $cycle_stop = racking_t::where('tanggal_r', '=', $date)->max('waktu_in_r');
        $jml_bar = racking_t::where('tanggal_r', '=', $date)->count();
        $ngracking_today = Ng_Racking::where('tanggal', '=', $date)->sum('quantity');
        $sum_pinbosh_tertinggal = Pinbosh_Tertinggal::where('tanggal', '=', $date)->sum('jumlah');
        $count_rencana_produksi = Rencana_Produksi::where('tanggal', '=', $date)->count();

        return view('racking_t.racking_t-menu_utama', compact('date', 'start_produksi', 'cycle_stop', 'jml_bar', 'ngracking_today', 'count_rencana_produksi', 'sum_pinbosh_tertinggal'));
    }

    // ===================================================NG RACKING =========================================================================

    public function ngracking(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        // dd($date);
        $ngracking = Ng_Racking::where('tanggal', '=', $date)->get();
        // dd($ngracking);
        return view('racking_t.ngracking', compact('ngracking', 'date'));
    }

    public function tambahngracking()
    {
        $ngracking = Ng_Racking::join('masterdata', 'masterdata.id', '=', 'ng_racking.id_masterdata')
            ->select('ng_racking.*', 'masterdata.part_name')
            ->get();

        $masterdata = MasterData::all();

        $latestData = Ng_Racking::latest()->first();
        // dd($latestData);
        $newDataTimestamp = Carbon::parse($latestData->tanggal)->format('Y-m-d');

        return view('racking_t.ngracking-tambah', compact('masterdata', 'ngracking', 'newDataTimestamp'));
    }

    public function simpanngracking(Request $request)
    {
        $ngracking = new Ng_Racking();
        $ngracking->tanggal = $request->tanggal . ' ' .  $request->jam;
        // dd($ngracking->tanggal);
        $validatedData = $request->validate([
            'tanggal' => 'required',
            'id_masterdata' => 'required',
            'part_name' => 'required',
            'jenis_ng' => 'required',
            'quantity' => 'required',
        ], [
            'tanggal.required' => 'Tanggal Harus Diisi!',
            'id_masterdata.required' => 'Id Masterdata Harus Diisi!',
            'part_name.required' => 'Part Name Harus Diisi!',
            'jenis_ng.required' => 'Jenis NG Harus Diisi!',
            'quantity.required' => 'Quantity Harus Diisi!',

        ]);
        $ngracking = Ng_Racking::create([
            'tanggal' => $request->tanggal,
            'id_masterdata' => $request->id_masterdata,
            'part_name' => $request->part_name,
            'jenis_ng' => $request->jenis_ng,
            'quantity' => $request->quantity,

        ]);
        return redirect()->route('ngracking.tambah', compact('ngracking'))->with('toast_success', 'Data Berhasil Disimpan!');
    }

    public function editngracking($id)
    {
        $ngracking = Ng_Racking::findOrFail($id);
        $masterdata = MasterData::all();
        return view('racking_t.ngracking-edit', compact('masterdata', 'ngracking'));
    }

    public function updatengracking(Request $request)
    {
        Ng_Racking::where('id', $request->id)->update([
            'tanggal' => $request->tanggal,
            'id_masterdata' => $request->id_masterdata,
            'part_name' => $request->part_name,
            'jenis_ng' => $request->jenis_ng,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('ngracking')->with('message', 'Data berhasil di update');
    }

    public function deletengracking($id)
    {
        $ng_racking = Ng_Racking::findOrFail($id);
        $ng_racking->delete();
        return response()->json([
            'success' => true
        ]);
        // return redirect()->route('ngracking')->with('success', 'Data Berhasil Dihapus');
    }


    // ===================================================PINBOSH TERTINGGAL =========================================================================

    public function pinbosh()
    {
        $pinbosh = Pinbosh_Tertinggal::all();

        return view('racking_t.pinbosh', compact('pinbosh'));
    }
    public function pinboshTambah()
    {
        $masterdata = MasterData::all();
        return view('racking_t.pinbosh-tambah', compact('masterdata'));
    }


    public function pinboshSimpan(Request $request)
    {
        $pinbosh = Pinbosh_Tertinggal::create([
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'id_masterdata' => $request->id_masterdata,
            'part_name' => $request->part_name,
            'jumlah' => $request->jumlah,
        ]);
        return redirect()->route('pinbosh', compact('pinbosh'))->with('success', 'Data Berhasil Disimpan!');
    }

    public function pinboshEdit($id)
    {
        $pinbosh = Pinbosh_Tertinggal::findOrFail($id);
        $masterdata = MasterData::all();
        return view('pinbosh.edit', compact('masterdata', 'pinbosh'));
    }

    //untuk scan barcode
    public function getData(Request $request)
    {
        $no_part = $request->input('no_part');
        $data = MasterData::where('no_part', $no_part)->first();
        return response()->json(['data' => $data]);
    }

    //uji coba
    public function getCountRack()
    {
        $data = DB::table('plating')
            ->select(DB::raw('YEAR(tanggal_r) as Tahun, MONTH(tanggal_r) as Bulan, DAY(tanggal_r) as Tanggal, COUNT(part_name) as jumlah_produksi'))
            ->groupBy('Tahun', 'Bulan', 'Tanggal')
            ->get();

        return response()->json($data);
    }
}

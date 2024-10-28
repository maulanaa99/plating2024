<?php

namespace App\Http\Controllers;

use App\Models\kensa;
use App\Models\KensaTrial;
use App\Models\MasterData;
use App\Models\Plating;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class KensaTrialController extends Controller
{
    public function index(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $kensa_trial = KensaTrial::join('masterdata', 'masterdata.id', '=', 'kensa_tr.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa_tr.id_plating')
            ->select('kensa_tr.*', 'masterdata.stok_bc', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.status')
            // ->orderBy('tanggal_k', 'desc')->orderBy('waktu_k', 'desc')
            ->orderBy('kensa_tr.id', 'asc')
            ->where('tanggal_k', '=', $date)
            ->get();

        $kensa = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'masterdata.stok_bc', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u')
            // ->orderBy('tanggal_k', 'desc')->orderBy('waktu_k', 'desc')
            ->orderBy('kensa.id', 'asc')
            ->where('tanggal_k', '=', $date)
            ->get();



        $masterdata = MasterData::all();

        $sum_qty_bar = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('qty_bar');
        $sum_nikel = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('nikel');
        $sum_butsu = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('butsu');
        $sum_hadare = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('hadare');
        $sum_hage = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('hage');
        $sum_moyo = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('moyo');
        $sum_fukure = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('fukure');
        $sum_crack = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('crack');
        $sum_henkei = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('henkei');
        $sum_hanazaki = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('hanazaki');
        $sum_kizu = DB::table('kensa_tr')->get()->where('tanggal_k', '=', $date)->sum('kizu');
        $sum_kaburi = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('kaburi');
        $sum_shiromoya = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('shiromoya');
        $sum_shimi = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('shimi');
        $sum_pitto = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('pitto');
        $sum_misto = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('misto');
        $sum_other = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('other');
        $sum_gores = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('gores');
        $sum_regas = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('regas');
        $sum_silver = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('silver');
        $sum_hike = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('hike');
        $sum_burry = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('burry');
        $sum_others = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('others');
        $sum_total_ok = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('total_ok');
        $sum_total_ng = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->sum('total_ng');
        $avg_p_total_ok = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->average('p_total_ok');
        $avg_p_total_ng = DB::table('kensa_tr')->where('tanggal_k', '=', $date)->get()->average('p_total_ng');

        return view('kensa_trial.kensa_trial', compact(
            'date',
            'kensa_trial',
            'sum_qty_bar',
            'sum_nikel',
            'sum_butsu',
            'sum_hadare',
            'sum_hage',
            'sum_moyo',
            'sum_fukure',
            'sum_crack',
            'sum_henkei',
            'sum_hanazaki',
            'sum_kizu',
            'sum_kaburi',
            'sum_shiromoya',
            'sum_shimi',
            'sum_pitto',
            'sum_misto',
            'sum_other',
            'sum_gores',
            'sum_regas',
            'sum_silver',
            'sum_hike',
            'sum_burry',
            'sum_others',
            'sum_total_ok',
            'sum_total_ng',
            'avg_p_total_ok',
            'avg_p_total_ng',
        ));
    }

    public function tambah(Request $request)
    {
        $kensa_trial = KensaTrial::join('masterdata', 'masterdata.id', '=', 'kensa_tr.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa_tr.id_plating')
            ->select('kensa_tr.*', 'masterdata.stok_bc', 'plating.id', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.status', 'plating.keterangan')
            ->orderBy('tanggal_k', 'desc')
            ->get();

        $platings = Plating::join('masterdata', 'masterdata.id', '=', 'plating.id_masterdata')
            ->select('plating.*', 'masterdata.part_name', 'masterdata.stok_bc')
            ->where('status', '=', '5')
            ->get();

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $hit_data_kensa_trial = KensaTrial::where('tanggal_k', '=', $date)->count();

        $masterdata = MasterData::all();

        return view('kensa_trial.kensa_trial-tambah', compact('kensa_trial', 'masterdata', 'platings', 'date', 'hit_data_kensa_trial'));
    }

    public function simpan(Request $request)
    {
        KensaTrial::create([
            'tanggal_k' => $request->tanggal_k,
            'waktu_k' => $request->waktu_k,
            'id_masterdata' => $request->id_masterdata,
            'id_plating' => $request->id_plating,
            'no_part' => $request->no_part,
            'part_name' => $request->part_name,
            'no_bar' => $request->no_bar,
            'qty_bar' => $request->qty_bar,
            'cycle' => $request->cycle,
            'nikel' => $request->nikel,
            'butsu' => $request->butsu,
            'hadare' => $request->hadare,
            'hage' => $request->hage,
            'moyo' => $request->moyo,
            'fukure' => $request->fukure,
            'crack' => $request->crack,
            'henkei' => $request->henkei,
            'hanazaki' => $request->hanazaki,
            'kizu' => $request->kizu,
            'kaburi' => $request->kaburi,
            'shiromoya' => $request->shiromoya,
            'shimi' => $request->shimi,
            'pitto' => $request->pitto,
            'misto' => $request->misto,
            'other' => $request->other,
            'gores' => $request->gores,
            'regas' => $request->regas,
            'silver' => $request->silver,
            'hike' => $request->hike,
            'burry' => $request->burry,
            'others' => $request->others,
            'total_ok' => $request->total_ok,
            'total_ng' => $request->total_ng,
            'p_total_ok' => $request->p_total_ok,
            'p_total_ng' => $request->p_total_ng,
            'keterangan' => $request->keterangan,
            'created_by' => Auth::user()->name,
            'created_at' => Carbon::now(),
        ]);

        $plating = Plating::find($request->id_plating);
        $plating->status = '6';
        $plating->save();

        return redirect()->route('tr.kensa.tambah')->with('success', 'Data berhasil disimpan');
    }

    public function edit(Request $request, $id)
    {
        // $kensa = kensa::where('id', '=', $id)->first();

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $kensa_trial = KensaTrial::find($id);
        $masterdata = MasterData::all();

        return view('kensa_trial.kensa_trial-edit', compact('kensa_trial', 'masterdata', 'date'));
    }

    public function update(Request $request, $id)
    {
        $kensa_trial = KensaTrial::find($id);

        $kensa_trial->id_masterdata;
        $kensa_trial->id_plating;
        $kensa_trial->tanggal_k = $request->tanggal_k;
        $kensa_trial->waktu_k = $request->waktu_k;
        $kensa_trial->no_part = $request->no_part;
        $kensa_trial->part_name = $request->part_name;
        $kensa_trial->no_bar = $request->no_bar;
        $kensa_trial->qty_bar = $request->qty_bar;
        $kensa_trial->cycle = $request->cycle;
        $kensa_trial->nikel = $request->nikel;
        $kensa_trial->butsu = $request->butsu;
        $kensa_trial->hadare = $request->hadare;
        $kensa_trial->hage = $request->hage;
        $kensa_trial->moyo = $request->moyo;
        $kensa_trial->fukure = $request->fukure;
        $kensa_trial->crack = $request->crack;
        $kensa_trial->henkei = $request->henkei;
        $kensa_trial->hanazaki = $request->hanazaki;
        $kensa_trial->kizu = $request->kizu;
        $kensa_trial->kaburi = $request->kaburi;
        $kensa_trial->shiromoya = $request->shiromoya;
        $kensa_trial->shimi = $request->shimi;
        $kensa_trial->pitto = $request->pitto;
        $kensa_trial->misto = $request->misto;
        $kensa_trial->other = $request->other;
        $kensa_trial->gores = $request->gores;
        $kensa_trial->regas = $request->regas;
        $kensa_trial->silver = $request->silver;
        $kensa_trial->hike = $request->hike;
        $kensa_trial->burry = $request->burry;
        $kensa_trial->others = $request->others;
        $kensa_trial->total_ok = $request->total_ok;
        $kensa_trial->total_ng = $request->total_ng;
        $kensa_trial->p_total_ok = $request->p_total_ok;
        $kensa_trial->p_total_ng = $request->p_total_ng;
        $kensa_trial->keterangan = $request->keterangan;
        $kensa_trial->save();

        Alert::Success('Success!', 'Data Berhasil Di Edit!');
        return redirect()->route('tr.kensa');
    }

    public function delete($id)
    {
        $kensa_trial = KensaTrial::find($id);
        // MASIH PENDING BROTHER! IF STATUS 4? IF STATUS 5?
        // $masterdata = kensa::where('id_masterdata', '=', $kensa->id_masterdata)->first();
        $masterdata = MasterData::find($kensa_trial->id_masterdata);
        $plating = Plating::find($kensa_trial->id_plating);
        $masterdata->stok = $masterdata->stok - $kensa_trial->total_ok;
        $masterdata->stok_bc = $masterdata->stok_bc + $kensa_trial->qty_bar;
        $plating->status = '5';
        $plating->save();
        $masterdata->save();
        $kensa_trial->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function trialApprove($id)
    {
        $kensa_trial = KensaTrial::find($id);
        $kensa_trial->approve_by = Auth::user()->name;
        $kensa_trial->approve_at = Carbon::now();
        $kensa_trial->save();

        $masterdata = MasterData::find($kensa_trial->id_masterdata);
        $masterdata->stok_bc = $masterdata->stok_bc - $kensa_trial->qty_bar;
        // $masterdata->stok_bc -= $kensa_trial->total_ok;
        // $masterdata->stok_bc -= $kensa_trial->total_ng;
        $masterdata->stok += $kensa_trial->total_ok;
        $masterdata->save();

        // $kensa = new kensa();
        // $kensa->id_masterdata = $kensa_trial->id_masterdata;
        // $kensa->id_plating = $kensa_trial->id_plating;
        // $kensa->tanggal_k = $kensa_trial->tanggal_k;
        // $kensa->waktu_k = $kensa_trial->waktu_k;
        // $kensa->no_part = $kensa_trial->no_part;
        // $kensa->part_name = $kensa_trial->part_name;
        // $kensa->no_bar = $kensa_trial->no_bar;
        // $kensa->qty_bar = $kensa_trial->qty_bar;
        // $kensa->cycle = $kensa_trial->cycle;
        // $kensa->nikel = $kensa_trial->nikel;
        // $kensa->butsu = $kensa_trial->butsu;
        // $kensa->hadare = $kensa_trial->hadare;
        // $kensa->hage = $kensa_trial->hage;
        // $kensa->moyo = $kensa_trial->moyo;
        // $kensa->fukure = $kensa_trial->fukure;
        // $kensa->crack = $kensa_trial->crack;
        // $kensa->henkei = $kensa_trial->henkei;
        // $kensa->hanazaki = $kensa_trial->hanazaki;
        // $kensa->kizu = $kensa_trial->kizu;
        // $kensa->kaburi = $kensa_trial->kaburi;
        // $kensa->shiromoya = $kensa_trial->shiromoya;
        // $kensa->shimi = $kensa_trial->shimi;
        // $kensa->pitto = $kensa_trial->pitto;
        // $kensa->misto = $kensa_trial->misto;
        // $kensa->other = $kensa_trial->other;
        // $kensa->gores = $kensa_trial->gores;
        // $kensa->regas = $kensa_trial->regas;
        // $kensa->silver = $kensa_trial->silver;
        // $kensa->hike = $kensa_trial->hike;
        // $kensa->burry = $kensa_trial->burry;
        // $kensa->others = $kensa_trial->others;
        // $kensa->total_ok = $kensa_trial->total_ok;
        // $kensa->total_ng = $kensa_trial->total_ng;
        // $kensa->p_total_ok = $kensa_trial->p_total_ok;
        // $kensa->p_total_ng = $kensa_trial->p_total_ng;
        // $kensa->created_by = Auth::user()->name;
        // $kensa->created_at = Carbon::now()->format('Y-m-d h:i:s');
        // $kensa->save();

        $plating = Plating::find($kensa_trial->id_plating);
        $plating->status = '7';
        $plating->save();

        Alert::Success('Success!', 'Data Trial Berhasil di Approve!!');
        return redirect()->route('tr.kensa');
    }

    public function trialCancell($id)
    {
        $kensa_trial = KensaTrial::find($id);

        $masterdata = MasterData::find($kensa_trial->id_masterdata);
        $masterdata->stok_bc = $masterdata->stok_bc - $kensa_trial->qty_bar;
        $masterdata->save();

        $plating = Plating::find($kensa_trial->id_plating);
        $plating->status = '8';
        $plating->save();

        Alert::info('Success!', 'Data Trial Berhasil di Cancell!!');
        return redirect()->route('tr.kensa');
    }
}

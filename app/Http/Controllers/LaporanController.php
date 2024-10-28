<?php

namespace App\Http\Controllers;

use App\Models\kensa;
use App\Models\KensaTrial;
use App\Models\Plating;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        if ($request->start_date || $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
            $plating = Plating::whereBetween('tanggal_r', [$start_date, $end_date])
                ->orderBy('tanggal_r', 'desc')
                ->orderBy('waktu_in_r', 'desc')
                ->get();
        } else {
            $plating = Plating::select(
                'id_masterdata',
                'no_part',
                'part_name',
                'katalis',
                'channel',
                'grade_color',
                'qty_bar',
                'cycle',
                'tanggal_r',
                'no_bar',
                'waktu_in_r',
                'tgl_lot_prod_mldg',
                'tanggal_u',
                'waktu_in_u',
                'qty_aktual'
            )->whereBetween('tanggal_r', [$start_date, $end_date]);
        }
        return view('laporan.laporan-plating', compact('plating', 'start_date', 'end_date'));
    }
    public function getData()
    {
        $plating = Plating::latest()->get();
        return DataTables::of($plating)
            ->addIndexColumn()
            ->make(true);
    }

    public function kensa(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        // dd($start_date);
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        if ($request->start_date || $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
            $kensa = kensa::whereBetween('created_at', [$start_date, $end_date])
                ->get();
        } else {
            $kensa = kensa::select(
                'id_masterdata',
                'tanggal_k',
                'waktu_k',
                'no_part',
                'part_name',
                'no_bar',
                'qty_bar',
                'cycle',
                'nikel',
                'butsu',
                'hadare',
                'hage',
                'moyo',
                'fukure',
                'crack',
                'henkei',
                'hanazaki',
                'kizu',
                'kaburi',
                'shiromoya',
                'shimi',
                'pitto',
                'other',
                'gores',
                'regas',
                'silver',
                'hike',
                'burry',
                'others',
                'total_ok',
                'total_ng',
                'p_total_ok',
                'p_total_ng'
            )->whereBetween('tanggal_k', [$start_date, $end_date]);
        }
        return view('laporan.laporan-kensa', compact('kensa', 'start_date', 'end_date'));
    }

    public function all(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        if ($request->start_date || $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
            $alls = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
                ->join('plating', 'plating.id', '=', 'kensa.id_plating')
                ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis')
                ->whereBetween('tanggal_k', [$start_date, $end_date])
                ->orderBy('tanggal_k', 'asc')
                ->orderBy('waktu_k', 'asc')
                ->get();
        } else {
            $alls = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
                ->join('plating', 'plating.id', '=', 'kensa.id_plating')
                ->select('plating.*', 'kensa.*')
                ->whereBetween('tanggal_k', [$start_date, $end_date]);
        }
        return view('laporan.laporan-all', compact('alls', 'start_date', 'end_date'));
    }

    public function trial(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');

        if ($request->start_date || $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
            $trials = KensaTrial::join('masterdata', 'masterdata.id', '=', 'kensa_tr.id_masterdata')
                ->join('plating', 'plating.id', '=', 'kensa_tr.id_plating')
                ->select('plating.*', 'kensa_tr.*')
                ->whereBetween('tanggal_k', [$start_date, $end_date])
                ->orderBy('kensa_tr.id', 'asc')
                ->get();
        } else {
            $trials = KensaTrial::join('masterdata', 'masterdata.id', '=', 'kensa_tr.id_masterdata')
                ->join('plating', 'plating.id', '=', 'kensa_tr.id_plating')
                ->select('plating.*', 'kensa_tr.*')
                ->whereBetween('tanggal_k', [$start_date, $end_date]);
        }
        return view('laporan.laporan-trial', compact('trials', 'start_date', 'end_date'));
    }

    public function allAdmin(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
        $end_date = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
        if ($request->start_date || $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');
            $alls = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
                ->join('plating', 'plating.id', '=', 'kensa.id_plating')
                ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->orderBy('tanggal_u', 'asc')
                ->orderBy('waktu_in_u', 'asc')
                ->get();
        } else {
            $alls = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
                ->join('plating', 'plating.id', '=', 'kensa.id_plating')
                ->select('plating.*', 'kensa.*')
                ->whereBetween('kensa.date_time', [$start_date, $end_date]);
        }

        $sum_qty_bar = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->sum('plating.qty_aktual');

        $sum_total_ok = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->sum('kensa.total_ok');

        $sum_total_ng = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->sum('kensa.total_ng');

        $total_ok = $sum_total_ok != 0 && $sum_qty_bar != 0 ? (($sum_total_ok / $sum_qty_bar) * 100) : 0;
        $total_ng = $sum_total_ng != 0 && $sum_qty_bar != 0 ? (($sum_total_ng / $sum_qty_bar) * 100) : 0;

        $kensa_today = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->count();

        $cooper_ok = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->where('plating.cycle', '=', 'CS')
            ->sum('kensa.total_ok');

        $cooper_qty_bar = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->where('plating.cycle', '=', 'CS')
            ->sum('kensa.qty_bar');

        $cooper_p = $cooper_ok != 0 && $cooper_qty_bar != 0 ? (($cooper_ok / $cooper_qty_bar) * 100) : 0;

        $final_ok = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->where('plating.cycle', '=', 'FS')
            ->sum('kensa.total_ok');

        $final_qty_bar = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->where('plating.cycle', '=', 'FS')
            ->sum('kensa.qty_bar');

        $final_p = $final_ok != 0 && $final_qty_bar != 0 ? (($final_ok / $final_qty_bar) * 100) : 0;

        $c1_ok = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->where('plating.cycle', '=', 'C1')
            ->sum('kensa.total_ok');

        $c1_qty_bar = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->where('plating.cycle', '=', 'C1')
            ->sum('kensa.qty_bar');

        $c1_p = $c1_ok != 0 && $c1_qty_bar != 0 ? (($c1_ok / $c1_qty_bar) * 100) : 0;

        $c2_ok = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->where('plating.cycle', '=', 'C2')
            ->sum('kensa.total_ok');

        $c2_qty_bar = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.channel', 'plating.grade_color', 'plating.katalis', 'masterdata.jenis', 'plating.date_time')
            ->whereBetween('plating.date_time', [$start_date, $end_date])
            ->where('plating.cycle', '=', 'C2')
            ->sum('kensa.qty_bar');

        $c2_p = $c2_ok != 0 && $c2_qty_bar != 0 ? (($c2_ok / $c2_qty_bar) * 100) : 0;

        return view('admin.laporan_admin.laporan_all', compact('alls', 'start_date', 'end_date', 'sum_qty_bar', 'sum_total_ok', 'sum_total_ng', 'total_ok', 'total_ng', 'kensa_today', 'cooper_p', 'final_p', 'c1_p', 'c2_p'));
    }
}

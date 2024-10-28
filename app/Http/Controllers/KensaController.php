<?php

namespace App\Http\Controllers;

use App\Models\kensa;
use App\Models\MasterData;
use App\Models\Pengiriman;
use App\Models\Plating;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Monolog\Handler\IFTTTHandler;
use RealRashid\SweetAlert\Facades\Alert;

class KensaController extends Controller
{
    //tampil data
    public function index(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');

        // $kensa = kensa::joins()->orderBy('kensa.id', 'asc')->where('tanggal_k', '=', $date)->get();

        $kensa = kensa::query();
        if ($request->filled('reguler') && $request->filled('emblem')) {
            // jika keduanya di-check, tampilkan semua produk
            $kensa = kensa::joins()->orderBy('kensa.id', 'asc')->where('tanggal_k', '=', $date)->get();
        } elseif ($request->filled('reguler')) {
            // jika hanya yang part reguler yang di-check, tampilkan produk yang part reguler
            $kensa = kensa::joins()->where('masterdata.jenis', '=', 'reguler')->where('tanggal_k', '=', $date)->orderBy('kensa.id', 'asc')->get();
        } elseif ($request->filled('emblem')) {
            // jika hanya yang emblem yang di-check, tampilkan produk yang emblem
            $kensa = kensa::joins()->where('masterdata.jenis', '=', 'emblem')->where('tanggal_k', '=', $date)->orderBy('kensa.id', 'asc')->get();
        } else {
            $kensa = kensa::joins()->orderBy('kensa.id', 'asc')->where('tanggal_k', '=', $date)->get();
        }


        $sum_qty_bar = kensa::joins()->where('tanggal_k', '=', $date)->sum('plating.qty_aktual');

        $sum_nikel = kensa::joins()->where('tanggal_k', '=', $date)->sum('nikel');
        $sum_butsu = kensa::joins()->where('tanggal_k', '=', $date)->sum('butsu');
        $sum_hadare = kensa::joins()->where('tanggal_k', '=', $date)->sum('hadare');
        $sum_hage = kensa::joins()->where('tanggal_k', '=', $date)->sum('hage');
        $sum_moyo = kensa::joins()->where('tanggal_k', '=', $date)->sum('moyo');
        $sum_fukure = kensa::joins()->where('tanggal_k', '=', $date)->sum('fukure');
        $sum_crack = kensa::joins()->where('tanggal_k', '=', $date)->sum('crack');
        $sum_henkei = kensa::joins()->where('tanggal_k', '=', $date)->sum('henkei');
        $sum_hanazaki = kensa::joins()->where('tanggal_k', '=', $date)->sum('hanazaki');
        $sum_kizu = kensa::joins()->where('tanggal_k', '=', $date)->sum('kizu');
        $sum_kaburi = kensa::joins()->where('tanggal_k', '=', $date)->sum('kaburi');
        $sum_shiromoya = kensa::joins()->where('tanggal_k', '=', $date)->sum('shiromoya');
        $sum_shimi = kensa::joins()->where('tanggal_k', '=', $date)->sum('shimi');
        $sum_pitto = kensa::joins()->where('tanggal_k', '=', $date)->sum('pitto');
        $sum_misto = kensa::joins()->where('tanggal_k', '=', $date)->sum('misto');
        $sum_other = kensa::joins()->where('tanggal_k', '=', $date)->sum('other');
        $sum_gores = kensa::joins()->where('tanggal_k', '=', $date)->sum('gores');
        $sum_regas = kensa::joins()->where('tanggal_k', '=', $date)->sum('regas');
        $sum_silver = kensa::joins()->where('tanggal_k', '=', $date)->sum('silver');
        $sum_hike = kensa::joins()->where('tanggal_k', '=', $date)->sum('hike');
        $sum_burry = kensa::joins()->where('tanggal_k', '=', $date)->sum('burry');
        $sum_others = kensa::joins()->where('tanggal_k', '=', $date)->sum('others');
        $sum_total_ok = kensa::joins()->where('tanggal_k', '=', $date)->sum('total_ok');
        $sum_total_ng = kensa::joins()->where('tanggal_k', '=', $date)->sum('total_ng');
        $avg_p_total_ok = kensa::joins()->where('tanggal_k', '=', $date)->average('p_total_ok');
        $avg_p_total_ng = kensa::joins()->where('tanggal_k', '=', $date)->average('p_total_ng');

        $masterdata = MasterData::all();

        return view('kensa.kensa-index', compact(
            'kensa',
            'masterdata',
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
            'date',
        ));
    }

    public function utama(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');


        $sum_qty_bar = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('plating.qty_aktual');

        $sum_total_ng = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('total_ng');
        $sum_nikel = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('nikel');
        $nikel = $sum_nikel != 0 && $sum_qty_bar != 0 ? (($sum_nikel / $sum_qty_bar) * 100) : 0;
        $sum_butsu = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('butsu');
        $butsu = $sum_butsu != 0 && $sum_qty_bar != 0 ? (($sum_butsu / $sum_qty_bar) * 100) : 0;
        $sum_hadare = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('hadare');
        $hadare = $sum_hadare != 0 && $sum_qty_bar != 0 ? (($sum_hadare / $sum_qty_bar) * 100) : 0;
        $sum_hage = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('hage');
        $hage = $sum_hage != 0 && $sum_qty_bar != 0 ? (($sum_hage / $sum_qty_bar) * 100) : 0;
        $sum_moyo = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('moyo');
        $moyo = $sum_moyo != 0 && $sum_qty_bar != 0 ? (($sum_moyo / $sum_qty_bar) * 100) : 0;
        $sum_fukure = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('fukure');
        $fukure = $sum_fukure != 0 && $sum_qty_bar != 0 ? (($sum_fukure / $sum_qty_bar) * 100) : 0;
        $sum_crack = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('crack');
        $crack = $sum_crack != 0 && $sum_qty_bar != 0 ? (($sum_crack / $sum_qty_bar) * 100) : 0;
        $sum_henkei = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('henkei');
        $henkei = $sum_henkei != 0 && $sum_qty_bar != 0 ? (($sum_henkei / $sum_qty_bar) * 100) : 0;
        $sum_hanazaki = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('hanazaki');
        $hanazaki = $sum_hanazaki != 0 && $sum_qty_bar != 0 ? (($sum_hanazaki / $sum_qty_bar) * 100) : 0;
        $sum_kizu = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('kizu');
        $kizu = $sum_kizu != 0 && $sum_qty_bar != 0 ? (($sum_kizu / $sum_qty_bar) * 100) : 0;
        $sum_kaburi = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('kaburi');
        $kaburi = $sum_kaburi != 0 && $sum_qty_bar != 0 ? (($sum_kaburi / $sum_qty_bar) * 100) : 0;
        $sum_shiromoya = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('shiromoya');
        $shiromoya = $sum_shiromoya != 0 && $sum_qty_bar != 0 ? (($sum_shiromoya / $sum_qty_bar) * 100) : 0;
        $sum_shimi = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('shimi');
        $shimi = $sum_shimi != 0 && $sum_qty_bar != 0 ? (($sum_shimi / $sum_qty_bar) * 100) : 0;
        $sum_pitto = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('pitto');
        $pitto = $sum_pitto != 0 && $sum_qty_bar != 0 ? (($sum_pitto / $sum_qty_bar) * 100) : 0;
        $sum_misto = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('misto');
        $misto = $sum_misto != 0 && $sum_qty_bar != 0 ? (($sum_misto / $sum_qty_bar) * 100) : 0;
        $sum_other = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('other');
        $other = $sum_other != 0 && $sum_qty_bar != 0 ? (($sum_other / $sum_qty_bar) * 100) : 0;
        $sum_gores = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('gores');
        $gores = $sum_gores != 0 && $sum_qty_bar != 0 ? (($sum_gores / $sum_qty_bar) * 100) : 0;
        $sum_regas = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('regas');
        $regas = $sum_regas != 0 && $sum_qty_bar != 0 ? (($sum_regas / $sum_qty_bar) * 100) : 0;
        $sum_silver = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('silver');
        $silver = $sum_silver != 0 && $sum_qty_bar != 0 ? (($sum_silver / $sum_qty_bar) * 100) : 0;
        $sum_hike = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('hike');
        $hike = $sum_hike != 0 && $sum_qty_bar != 0 ? (($sum_hike / $sum_qty_bar) * 100) : 0;
        $sum_burry = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('burry');
        $burry = $sum_burry != 0 && $sum_qty_bar != 0 ? (($sum_burry / $sum_qty_bar) * 100) : 0;
        $sum_others = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('others');
        $others = $sum_others != 0 && $sum_qty_bar != 0 ? (($sum_others / $sum_qty_bar) * 100) : 0;
        $sum_total_ok = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('tanggal_k', '=', $date)->sum('total_ok');

        $total_ok = $sum_total_ok != 0 && $sum_qty_bar != 0 ? (($sum_total_ok / $sum_qty_bar) * 100) : 0;
        $total_ng = $sum_total_ng != 0 && $sum_qty_bar != 0 ? (($sum_total_ng / $sum_qty_bar) * 100) : 0;

        $kensa_today = kensa::where('tanggal_k', '=', $date)->count();

        $cooper_ok = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('plating.cycle', '=', 'CS')->where('tanggal_k', '=', $date)->sum('total_ok');
        $cooper_qty_bar = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('plating.cycle', '=', 'CS')->where('tanggal_k', '=', $date)->sum('plating.qty_aktual');
        $cooper_p = $cooper_ok != 0 && $cooper_qty_bar != 0 ? (($cooper_ok / $cooper_qty_bar) * 100) : 0;

        $final_ok = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('plating.cycle', '=', 'FS')->where('tanggal_k', '=', $date)->sum('total_ok');
        $final_qty_bar = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('plating.cycle', '=', 'FS')->where('tanggal_k', '=', $date)->sum('plating.qty_aktual');
        $final_p = $final_ok != 0 && $final_qty_bar != 0 ? (($final_ok / $final_qty_bar) * 100) : 0;

        $c1_ok = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('plating.cycle', '=', 'C1')->where('tanggal_k', '=', $date)->sum('total_ok');
        $c1_qty_bar = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('plating.cycle', '=', 'C1')->where('tanggal_k', '=', $date)->sum('plating.qty_aktual');
        $c1_p = $c1_ok != 0 && $c1_qty_bar != 0 ? (($c1_ok / $c1_qty_bar) * 100) : 0;

        $c2_ok = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('plating.cycle', '=', 'C2')->where('tanggal_k', '=', $date)->sum('total_ok');
        $c2_qty_bar = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->where('plating.cycle', '=', 'C2')->where('tanggal_k', '=', $date)->sum('plating.qty_aktual');
        $c2_p = $c2_ok != 0 && $c2_qty_bar != 0 ? (($c2_ok / $c2_qty_bar) * 100) : 0;

        $hit_data_kensa = kensa::where('tanggal_k', '=', $date)->count();

        return view('kensa.kensa_menu_utama', compact(
            'nikel',
            'sum_nikel',
            'butsu',
            'sum_butsu',
            'hadare',
            'sum_hadare',
            'hage',
            'sum_hage',
            'moyo',
            'sum_moyo',
            'fukure',
            'sum_fukure',
            'crack',
            'sum_crack',
            'henkei',
            'sum_henkei',
            'hanazaki',
            'sum_hanazaki',
            'kizu',
            'sum_kizu',
            'kaburi',
            'sum_kaburi',
            'shiromoya',
            'sum_shiromoya',
            'shimi',
            'sum_shimi',
            'pitto',
            'sum_pitto',
            'misto',
            'sum_misto',
            'other',
            'sum_other',
            'gores',
            'sum_gores',
            'regas',
            'sum_regas',
            'silver',
            'sum_silver',
            'hike',
            'sum_hike',
            'burry',
            'sum_burry',
            'others',
            'sum_others',
            'total_ok',
            'total_ng',
            'date',
            'sum_qty_bar',
            'kensa_today',
            'c2_p',
            'c1_p',
            'cooper_p',
            'final_p',
            'hit_data_kensa'
        ));
    }

    public function utama2(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d H:i:s');

        if ($request->filled('reguler') && $request->filled('emblem')) {
            // jika keduanya di-check, tampilkan semua produk
            $sum_qty_bar = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('qty_aktual');

            $sum_total_ng = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ng');

            $sum_nikel = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('nikel');

            $nikel = $sum_nikel != 0 && $sum_qty_bar != 0 ? (($sum_nikel / $sum_qty_bar) * 100) : 0;

            $sum_butsu = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('butsu');

            $butsu = $sum_butsu != 0 && $sum_qty_bar != 0 ? (($sum_butsu / $sum_qty_bar) * 100) : 0;

            $sum_hadare = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hadare');

            $hadare = $sum_hadare != 0 && $sum_qty_bar != 0 ? (($sum_hadare / $sum_qty_bar) * 100) : 0;

            $sum_hage = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hage');

            $hage = $sum_hage != 0 && $sum_qty_bar != 0 ? (($sum_hage / $sum_qty_bar) * 100) : 0;

            $sum_moyo = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('moyo');

            $moyo = $sum_moyo != 0 && $sum_qty_bar != 0 ? (($sum_moyo / $sum_qty_bar) * 100) : 0;

            $sum_fukure = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('fukure');

            $fukure = $sum_fukure != 0 && $sum_qty_bar != 0 ? (($sum_fukure / $sum_qty_bar) * 100) : 0;

            $sum_crack = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('crack');

            $crack = $sum_crack != 0 && $sum_qty_bar != 0 ? (($sum_crack / $sum_qty_bar) * 100) : 0;

            $sum_henkei = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('henkei');

            $henkei = $sum_henkei != 0 && $sum_qty_bar != 0 ? (($sum_henkei / $sum_qty_bar) * 100) : 0;

            $sum_hanazaki = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hanazaki');

            $hanazaki = $sum_hanazaki != 0 && $sum_qty_bar != 0 ? (($sum_hanazaki / $sum_qty_bar) * 100) : 0;

            $sum_kizu = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('kizu');

            $kizu = $sum_kizu != 0 && $sum_qty_bar != 0 ? (($sum_kizu / $sum_qty_bar) * 100) : 0;

            $sum_kaburi = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('kaburi');

            $kaburi = $sum_kaburi != 0 && $sum_qty_bar != 0 ? (($sum_kaburi / $sum_qty_bar) * 100) : 0;

            $sum_shiromoya = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('shiromoya');

            $shiromoya = $sum_shiromoya != 0 && $sum_qty_bar != 0 ? (($sum_shiromoya / $sum_qty_bar) * 100) : 0;

            $sum_shimi = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('shimi');

            $shimi = $sum_shimi != 0 && $sum_qty_bar != 0 ? (($sum_shimi / $sum_qty_bar) * 100) : 0;

            $sum_pitto = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('pitto');

            $pitto = $sum_pitto != 0 && $sum_qty_bar != 0 ? (($sum_pitto / $sum_qty_bar) * 100) : 0;

            $sum_misto = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('misto');

            $misto = $sum_misto != 0 && $sum_qty_bar != 0 ? (($sum_misto / $sum_qty_bar) * 100) : 0;

            $sum_other = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('other');

            $other = $sum_other != 0 && $sum_qty_bar != 0 ? (($sum_other / $sum_qty_bar) * 100) : 0;

            $sum_gores = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('gores');

            $gores = $sum_gores != 0 && $sum_qty_bar != 0 ? (($sum_gores / $sum_qty_bar) * 100) : 0;

            $sum_regas = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('regas');

            $regas = $sum_regas != 0 && $sum_qty_bar != 0 ? (($sum_regas / $sum_qty_bar) * 100) : 0;

            $sum_silver = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('silver');

            $silver = $sum_silver != 0 && $sum_qty_bar != 0 ? (($sum_silver / $sum_qty_bar) * 100) : 0;

            $sum_hike = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hike');
            $hike = $sum_hike != 0 && $sum_qty_bar != 0 ? (($sum_hike / $sum_qty_bar) * 100) : 0;

            $sum_burry = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('burry');

            $burry = $sum_burry != 0 && $sum_qty_bar != 0 ? (($sum_burry / $sum_qty_bar) * 100) : 0;

            $sum_others = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('others');

            $others = $sum_others != 0 && $sum_qty_bar != 0 ? (($sum_others / $sum_qty_bar) * 100) : 0;

            $sum_total_ok = kensa::joins()

                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');

            $total_ok = $sum_total_ok != 0 && $sum_qty_bar != 0 ? (($sum_total_ok / $sum_qty_bar) * 100) : 0;
            $total_ng = $sum_total_ng != 0 && $sum_qty_bar != 0 ? (($sum_total_ng / $sum_qty_bar) * 100) : 0;

            $kensa_today = kensa::joins()->whereBetween('plating.date_time', [$start_date, $end_date])
                ->count();

            $cooper_ok = kensa::joins()->where('plating.cycle', '=', 'CS')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $cooper_qty_bar = kensa::joins()->where('plating.cycle', '=', 'CS')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $cooper_p = $cooper_ok != 0 && $cooper_qty_bar != 0 ? (($cooper_ok / $cooper_qty_bar) * 100) : 0;

            $final_ok = kensa::joins()->where('plating.cycle', '=', 'FS')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');

            $final_qty_bar = kensa::joins()->where('plating.cycle', '=', 'FS')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $final_p = $final_ok != 0 && $final_qty_bar != 0 ? (($final_ok / $final_qty_bar) * 100) : 0;

            $c1_ok = kensa::joins()->where('plating.cycle', '=', 'C1')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $c1_qty_bar = kensa::joins()->where('plating.cycle', '=', 'C1')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $c1_p = $c1_ok != 0 && $c1_qty_bar != 0 ? (($c1_ok / $c1_qty_bar) * 100) : 0;

            $c2_ok = kensa::joins()->where('plating.cycle', '=', 'C2')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $c2_qty_bar = kensa::joins()->where('plating.cycle', '=', 'C2')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $c2_p = $c2_ok != 0 && $c2_qty_bar != 0 ? (($c2_ok / $c2_qty_bar) * 100) : 0;

            $hit_data_kensa = kensa::joins()
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->count();
        } elseif ($request->filled('reguler')) {
            // jika hanya yang part reguler yang di-check, tampilkan produk yang part reguler
            $sum_qty_bar = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('qty_aktual');

            $sum_total_ng = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ng');

            $sum_nikel = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('nikel');

            $nikel = $sum_nikel != 0 && $sum_qty_bar != 0 ? (($sum_nikel / $sum_qty_bar) * 100) : 0;

            $sum_butsu = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('butsu');

            $butsu = $sum_butsu != 0 && $sum_qty_bar != 0 ? (($sum_butsu / $sum_qty_bar) * 100) : 0;

            $sum_hadare = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hadare');

            $hadare = $sum_hadare != 0 && $sum_qty_bar != 0 ? (($sum_hadare / $sum_qty_bar) * 100) : 0;

            $sum_hage = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hage');

            $hage = $sum_hage != 0 && $sum_qty_bar != 0 ? (($sum_hage / $sum_qty_bar) * 100) : 0;

            $sum_moyo = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('moyo');

            $moyo = $sum_moyo != 0 && $sum_qty_bar != 0 ? (($sum_moyo / $sum_qty_bar) * 100) : 0;

            $sum_fukure = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('fukure');

            $fukure = $sum_fukure != 0 && $sum_qty_bar != 0 ? (($sum_fukure / $sum_qty_bar) * 100) : 0;

            $sum_crack = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('crack');

            $crack = $sum_crack != 0 && $sum_qty_bar != 0 ? (($sum_crack / $sum_qty_bar) * 100) : 0;

            $sum_henkei = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('henkei');

            $henkei = $sum_henkei != 0 && $sum_qty_bar != 0 ? (($sum_henkei / $sum_qty_bar) * 100) : 0;

            $sum_hanazaki = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hanazaki');

            $hanazaki = $sum_hanazaki != 0 && $sum_qty_bar != 0 ? (($sum_hanazaki / $sum_qty_bar) * 100) : 0;

            $sum_kizu = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('kizu');

            $kizu = $sum_kizu != 0 && $sum_qty_bar != 0 ? (($sum_kizu / $sum_qty_bar) * 100) : 0;

            $sum_kaburi = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('kaburi');

            $kaburi = $sum_kaburi != 0 && $sum_qty_bar != 0 ? (($sum_kaburi / $sum_qty_bar) * 100) : 0;

            $sum_shiromoya = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('shiromoya');

            $shiromoya = $sum_shiromoya != 0 && $sum_qty_bar != 0 ? (($sum_shiromoya / $sum_qty_bar) * 100) : 0;

            $sum_shimi = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('shimi');

            $shimi = $sum_shimi != 0 && $sum_qty_bar != 0 ? (($sum_shimi / $sum_qty_bar) * 100) : 0;

            $sum_pitto = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('pitto');

            $pitto = $sum_pitto != 0 && $sum_qty_bar != 0 ? (($sum_pitto / $sum_qty_bar) * 100) : 0;

            $sum_misto = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('misto');

            $misto = $sum_misto != 0 && $sum_qty_bar != 0 ? (($sum_misto / $sum_qty_bar) * 100) : 0;

            $sum_other = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('other');

            $other = $sum_other != 0 && $sum_qty_bar != 0 ? (($sum_other / $sum_qty_bar) * 100) : 0;

            $sum_gores = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('gores');

            $gores = $sum_gores != 0 && $sum_qty_bar != 0 ? (($sum_gores / $sum_qty_bar) * 100) : 0;

            $sum_regas = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('regas');

            $regas = $sum_regas != 0 && $sum_qty_bar != 0 ? (($sum_regas / $sum_qty_bar) * 100) : 0;

            $sum_silver = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('silver');

            $silver = $sum_silver != 0 && $sum_qty_bar != 0 ? (($sum_silver / $sum_qty_bar) * 100) : 0;

            $sum_hike = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hike');
            $hike = $sum_hike != 0 && $sum_qty_bar != 0 ? (($sum_hike / $sum_qty_bar) * 100) : 0;

            $sum_burry = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('burry');

            $burry = $sum_burry != 0 && $sum_qty_bar != 0 ? (($sum_burry / $sum_qty_bar) * 100) : 0;

            $sum_others = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('others');

            $others = $sum_others != 0 && $sum_qty_bar != 0 ? (($sum_others / $sum_qty_bar) * 100) : 0;

            $sum_total_ok = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');

            $total_ok = $sum_total_ok != 0 && $sum_qty_bar != 0 ? (($sum_total_ok / $sum_qty_bar) * 100) : 0;
            $total_ng = $sum_total_ng != 0 && $sum_qty_bar != 0 ? (($sum_total_ng / $sum_qty_bar) * 100) : 0;

            $kensa_today = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->count();

            $cooper_ok = kensa::joins()->where('plating.cycle', '=', 'CS')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $cooper_qty_bar = kensa::joins()->where('plating.cycle', '=', 'CS')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $cooper_p = $cooper_ok != 0 && $cooper_qty_bar != 0 ? (($cooper_ok / $cooper_qty_bar) * 100) : 0;

            $final_ok = kensa::joins()->where('plating.cycle', '=', 'FS')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');

            $final_qty_bar = kensa::joins()->where('plating.cycle', '=', 'FS')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $final_p = $final_ok != 0 && $final_qty_bar != 0 ? (($final_ok / $final_qty_bar) * 100) : 0;

            $c1_ok = kensa::joins()->where('plating.cycle', '=', 'C1')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $c1_qty_bar = kensa::joins()->where('plating.cycle', '=', 'C1')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $c1_p = $c1_ok != 0 && $c1_qty_bar != 0 ? (($c1_ok / $c1_qty_bar) * 100) : 0;

            $c2_ok = kensa::joins()->where('plating.cycle', '=', 'C2')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $c2_qty_bar = kensa::joins()->where('plating.cycle', '=', 'C2')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $c2_p = $c2_ok != 0 && $c2_qty_bar != 0 ? (($c2_ok / $c2_qty_bar) * 100) : 0;

            $hit_data_kensa = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->count();
        } elseif ($request->filled('emblem')) {
            // jika hanya yang emblem yang di-check, tampilkan produk yang emblem
            $sum_qty_bar = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('qty_aktual');

            $sum_total_ng = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ng');

            $sum_nikel = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('nikel');

            $nikel = $sum_nikel != 0 && $sum_qty_bar != 0 ? (($sum_nikel / $sum_qty_bar) * 100) : 0;

            $sum_butsu = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('butsu');

            $butsu = $sum_butsu != 0 && $sum_qty_bar != 0 ? (($sum_butsu / $sum_qty_bar) * 100) : 0;

            $sum_hadare = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hadare');

            $hadare = $sum_hadare != 0 && $sum_qty_bar != 0 ? (($sum_hadare / $sum_qty_bar) * 100) : 0;

            $sum_hage = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hage');

            $hage = $sum_hage != 0 && $sum_qty_bar != 0 ? (($sum_hage / $sum_qty_bar) * 100) : 0;

            $sum_moyo = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('moyo');

            $moyo = $sum_moyo != 0 && $sum_qty_bar != 0 ? (($sum_moyo / $sum_qty_bar) * 100) : 0;

            $sum_fukure = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('fukure');

            $fukure = $sum_fukure != 0 && $sum_qty_bar != 0 ? (($sum_fukure / $sum_qty_bar) * 100) : 0;

            $sum_crack = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('crack');

            $crack = $sum_crack != 0 && $sum_qty_bar != 0 ? (($sum_crack / $sum_qty_bar) * 100) : 0;

            $sum_henkei = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('henkei');

            $henkei = $sum_henkei != 0 && $sum_qty_bar != 0 ? (($sum_henkei / $sum_qty_bar) * 100) : 0;

            $sum_hanazaki = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hanazaki');

            $hanazaki = $sum_hanazaki != 0 && $sum_qty_bar != 0 ? (($sum_hanazaki / $sum_qty_bar) * 100) : 0;

            $sum_kizu = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('kizu');

            $kizu = $sum_kizu != 0 && $sum_qty_bar != 0 ? (($sum_kizu / $sum_qty_bar) * 100) : 0;

            $sum_kaburi = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('kaburi');

            $kaburi = $sum_kaburi != 0 && $sum_qty_bar != 0 ? (($sum_kaburi / $sum_qty_bar) * 100) : 0;

            $sum_shiromoya = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('shiromoya');

            $shiromoya = $sum_shiromoya != 0 && $sum_qty_bar != 0 ? (($sum_shiromoya / $sum_qty_bar) * 100) : 0;

            $sum_shimi = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('shimi');

            $shimi = $sum_shimi != 0 && $sum_qty_bar != 0 ? (($sum_shimi / $sum_qty_bar) * 100) : 0;

            $sum_pitto = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('pitto');

            $pitto = $sum_pitto != 0 && $sum_qty_bar != 0 ? (($sum_pitto / $sum_qty_bar) * 100) : 0;

            $sum_misto = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('misto');

            $misto = $sum_misto != 0 && $sum_qty_bar != 0 ? (($sum_misto / $sum_qty_bar) * 100) : 0;

            $sum_other = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('other');

            $other = $sum_other != 0 && $sum_qty_bar != 0 ? (($sum_other / $sum_qty_bar) * 100) : 0;

            $sum_gores = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('gores');

            $gores = $sum_gores != 0 && $sum_qty_bar != 0 ? (($sum_gores / $sum_qty_bar) * 100) : 0;

            $sum_regas = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('regas');

            $regas = $sum_regas != 0 && $sum_qty_bar != 0 ? (($sum_regas / $sum_qty_bar) * 100) : 0;

            $sum_silver = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('silver');

            $silver = $sum_silver != 0 && $sum_qty_bar != 0 ? (($sum_silver / $sum_qty_bar) * 100) : 0;

            $sum_hike = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hike');
            $hike = $sum_hike != 0 && $sum_qty_bar != 0 ? (($sum_hike / $sum_qty_bar) * 100) : 0;

            $sum_burry = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('burry');

            $burry = $sum_burry != 0 && $sum_qty_bar != 0 ? (($sum_burry / $sum_qty_bar) * 100) : 0;

            $sum_others = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('others');

            $others = $sum_others != 0 && $sum_qty_bar != 0 ? (($sum_others / $sum_qty_bar) * 100) : 0;

            $sum_total_ok = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');

            $total_ok = $sum_total_ok != 0 && $sum_qty_bar != 0 ? (($sum_total_ok / $sum_qty_bar) * 100) : 0;
            $total_ng = $sum_total_ng != 0 && $sum_qty_bar != 0 ? (($sum_total_ng / $sum_qty_bar) * 100) : 0;

            $kensa_today = kensa::joins()->where('masterdata.jenis', '=', 'Emblem')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->count();

            $cooper_ok = kensa::joins()->where('plating.cycle', '=', 'CS')->where('masterdata.jenis', '=', 'Emblem')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $cooper_qty_bar = kensa::joins()->where('plating.cycle', '=', 'CS')->where('masterdata.jenis', '=', 'Emblem')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $cooper_p = $cooper_ok != 0 && $cooper_qty_bar != 0 ? (($cooper_ok / $cooper_qty_bar) * 100) : 0;

            $final_ok = kensa::joins()->where('plating.cycle', '=', 'FS')->where('masterdata.jenis', '=', 'Emblem')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');

            $final_qty_bar = kensa::joins()->where('plating.cycle', '=', 'FS')->where('masterdata.jenis', '=', 'Emblem')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $final_p = $final_ok != 0 && $final_qty_bar != 0 ? (($final_ok / $final_qty_bar) * 100) : 0;

            $c1_ok = kensa::joins()->where('plating.cycle', '=', 'C1')->where('masterdata.jenis', '=', 'Emblem')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $c1_qty_bar = kensa::joins()->where('plating.cycle', '=', 'C1')->where('masterdata.jenis', '=', 'Emblem')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $c1_p = $c1_ok != 0 && $c1_qty_bar != 0 ? (($c1_ok / $c1_qty_bar) * 100) : 0;

            $c2_ok = kensa::joins()->where('plating.cycle', '=', 'C2')->where('masterdata.jenis', '=', 'Emblem')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $c2_qty_bar = kensa::joins()->where('plating.cycle', '=', 'C2')->where('masterdata.jenis', '=', 'Emblem')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $c2_p = $c2_ok != 0 && $c2_qty_bar != 0 ? (($c2_ok / $c2_qty_bar) * 100) : 0;

            $hit_data_kensa = kensa::joins()
                ->where('masterdata.jenis', '=', 'Emblem')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->count();
        } else {
            $sum_qty_bar = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('qty_aktual');

            $sum_total_ng = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ng');

            $sum_nikel = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('nikel');

            $nikel = $sum_nikel != 0 && $sum_qty_bar != 0 ? (($sum_nikel / $sum_qty_bar) * 100) : 0;

            $sum_butsu = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('butsu');

            $butsu = $sum_butsu != 0 && $sum_qty_bar != 0 ? (($sum_butsu / $sum_qty_bar) * 100) : 0;

            $sum_hadare = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hadare');

            $hadare = $sum_hadare != 0 && $sum_qty_bar != 0 ? (($sum_hadare / $sum_qty_bar) * 100) : 0;

            $sum_hage = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hage');

            $hage = $sum_hage != 0 && $sum_qty_bar != 0 ? (($sum_hage / $sum_qty_bar) * 100) : 0;

            $sum_moyo = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('moyo');

            $moyo = $sum_moyo != 0 && $sum_qty_bar != 0 ? (($sum_moyo / $sum_qty_bar) * 100) : 0;

            $sum_fukure = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('fukure');

            $fukure = $sum_fukure != 0 && $sum_qty_bar != 0 ? (($sum_fukure / $sum_qty_bar) * 100) : 0;

            $sum_crack = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('crack');

            $crack = $sum_crack != 0 && $sum_qty_bar != 0 ? (($sum_crack / $sum_qty_bar) * 100) : 0;

            $sum_henkei = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('henkei');

            $henkei = $sum_henkei != 0 && $sum_qty_bar != 0 ? (($sum_henkei / $sum_qty_bar) * 100) : 0;

            $sum_hanazaki = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hanazaki');

            $hanazaki = $sum_hanazaki != 0 && $sum_qty_bar != 0 ? (($sum_hanazaki / $sum_qty_bar) * 100) : 0;

            $sum_kizu = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('kizu');

            $kizu = $sum_kizu != 0 && $sum_qty_bar != 0 ? (($sum_kizu / $sum_qty_bar) * 100) : 0;

            $sum_kaburi = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('kaburi');

            $kaburi = $sum_kaburi != 0 && $sum_qty_bar != 0 ? (($sum_kaburi / $sum_qty_bar) * 100) : 0;

            $sum_shiromoya = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('shiromoya');

            $shiromoya = $sum_shiromoya != 0 && $sum_qty_bar != 0 ? (($sum_shiromoya / $sum_qty_bar) * 100) : 0;

            $sum_shimi = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('shimi');

            $shimi = $sum_shimi != 0 && $sum_qty_bar != 0 ? (($sum_shimi / $sum_qty_bar) * 100) : 0;

            $sum_pitto = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('pitto');

            $pitto = $sum_pitto != 0 && $sum_qty_bar != 0 ? (($sum_pitto / $sum_qty_bar) * 100) : 0;

            $sum_misto = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('misto');

            $misto = $sum_misto != 0 && $sum_qty_bar != 0 ? (($sum_misto / $sum_qty_bar) * 100) : 0;

            $sum_other = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('other');

            $other = $sum_other != 0 && $sum_qty_bar != 0 ? (($sum_other / $sum_qty_bar) * 100) : 0;

            $sum_gores = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('gores');

            $gores = $sum_gores != 0 && $sum_qty_bar != 0 ? (($sum_gores / $sum_qty_bar) * 100) : 0;

            $sum_regas = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('regas');

            $regas = $sum_regas != 0 && $sum_qty_bar != 0 ? (($sum_regas / $sum_qty_bar) * 100) : 0;

            $sum_silver = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('silver');

            $silver = $sum_silver != 0 && $sum_qty_bar != 0 ? (($sum_silver / $sum_qty_bar) * 100) : 0;

            $sum_hike = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('hike');
            $hike = $sum_hike != 0 && $sum_qty_bar != 0 ? (($sum_hike / $sum_qty_bar) * 100) : 0;

            $sum_burry = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('burry');

            $burry = $sum_burry != 0 && $sum_qty_bar != 0 ? (($sum_burry / $sum_qty_bar) * 100) : 0;

            $sum_others = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('others');

            $others = $sum_others != 0 && $sum_qty_bar != 0 ? (($sum_others / $sum_qty_bar) * 100) : 0;

            $sum_total_ok = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');

            $total_ok = $sum_total_ok != 0 && $sum_qty_bar != 0 ? (($sum_total_ok / $sum_qty_bar) * 100) : 0;
            $total_ng = $sum_total_ng != 0 && $sum_qty_bar != 0 ? (($sum_total_ng / $sum_qty_bar) * 100) : 0;

            $kensa_today = kensa::joins()->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->count();

            $cooper_ok = kensa::joins()->where('plating.cycle', '=', 'CS')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $cooper_qty_bar = kensa::joins()->where('plating.cycle', '=', 'CS')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $cooper_p = $cooper_ok != 0 && $cooper_qty_bar != 0 ? (($cooper_ok / $cooper_qty_bar) * 100) : 0;

            $final_ok = kensa::joins()->where('plating.cycle', '=', 'FS')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');

            $final_qty_bar = kensa::joins()->where('plating.cycle', '=', 'FS')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $final_p = $final_ok != 0 && $final_qty_bar != 0 ? (($final_ok / $final_qty_bar) * 100) : 0;

            $c1_ok = kensa::joins()->where('plating.cycle', '=', 'C1')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $c1_qty_bar = kensa::joins()->where('plating.cycle', '=', 'C1')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $c1_p = $c1_ok != 0 && $c1_qty_bar != 0 ? (($c1_ok / $c1_qty_bar) * 100) : 0;

            $c2_ok = kensa::joins()->where('plating.cycle', '=', 'C2')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('total_ok');
            $c2_qty_bar = kensa::joins()->where('plating.cycle', '=', 'C2')->where('masterdata.jenis', '=', 'Reguler')->whereBetween('plating.date_time', [$start_date, $end_date])
                ->sum('plating.qty_aktual');
            $c2_p = $c2_ok != 0 && $c2_qty_bar != 0 ? (($c2_ok / $c2_qty_bar) * 100) : 0;

            $hit_data_kensa = kensa::joins()
                ->where('masterdata.jenis', '=', 'Reguler')
                ->whereBetween('plating.date_time', [$start_date, $end_date])
                ->count();
        }

        return view('kensa.kensa_menu_utama2', compact(
            'nikel',
            'sum_nikel',
            'butsu',
            'sum_butsu',
            'hadare',
            'sum_hadare',
            'hage',
            'sum_hage',
            'moyo',
            'sum_moyo',
            'fukure',
            'sum_fukure',
            'crack',
            'sum_crack',
            'henkei',
            'sum_henkei',
            'hanazaki',
            'sum_hanazaki',
            'kizu',
            'sum_kizu',
            'kaburi',
            'sum_kaburi',
            'shiromoya',
            'sum_shiromoya',
            'shimi',
            'sum_shimi',
            'pitto',
            'sum_pitto',
            'misto',
            'sum_misto',
            'other',
            'sum_other',
            'gores',
            'sum_gores',
            'regas',
            'sum_regas',
            'silver',
            'sum_silver',
            'hike',
            'sum_hike',
            'burry',
            'sum_burry',
            'others',
            'sum_others',
            'total_ok',
            'total_ng',
            'sum_qty_bar',
            'kensa_today',
            'c2_p',
            'c1_p',
            'cooper_p',
            'final_p',
            'hit_data_kensa',
            'start_date',
            'end_date'
        ));
    }

    //tambah data
    public function tambah(Request $request)
    {
        $kensa = kensa::join('masterdata', 'masterdata.id', '=', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', '=', 'kensa.id_plating')
            ->select('kensa.*', 'masterdata.stok_bc', 'plating.id', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.waktu_in_u', 'plating.tanggal_r', 'plating.keterangan')
            ->orderBy('tanggal_k', 'desc')
            ->get();

        $platings = Plating::join('masterdata', 'masterdata.id', '=', 'plating.id_masterdata')
            ->select('plating.*', 'masterdata.part_name', 'masterdata.stok_bc')
            ->where('status', '=', '2')
            ->get();


        $date = Carbon::parse($request->date)->format('Y-m-d');
        $hit_data_kensa = kensa::where('tanggal_k', '=', $date)->count();

        $masterdata = MasterData::all();
        return view('kensa.kensa-tambah', compact('kensa', 'masterdata', 'platings', 'hit_data_kensa', 'date'));
    }

    //simpan data
    public function simpan(Request $request)
    {
        $masterdata = MasterData::find($request->id_masterdata);
        if ($masterdata->stok_bc < $request->qty_bar) {
            Alert::error('Gagal', 'Stok Kurang!!');
            return redirect()->route('kensa.tambah');
        }

        $kensa = new kensa();
        $kensa->tanggal_k = $request->tanggal_k;
        $kensa->waktu_k = $request->waktu_k;
        $kensa->id_masterdata = $request->id_masterdata;
        $kensa->id_plating = $request->id_plating;
        $kensa->no_part = $request->no_part;
        $kensa->part_name = $request->part_name;
        $kensa->no_bar = $request->no_bar;
        $kensa->qty_bar = $request->qty_bar;
        $kensa->cycle = $request->cycle;
        $kensa->nikel = $request->nikel ? $request->nikel : 0;
        $kensa->butsu = $request->butsu ? $request->butsu : 0;
        $kensa->hadare = $request->hadare ? $request->hadare : 0;
        $kensa->hage = $request->hage ? $request->hage : 0;
        $kensa->moyo = $request->moyo ? $request->moyo : 0;
        $kensa->fukure = $request->fukure ? $request->fukure : 0;
        $kensa->crack = $request->crack ? $request->crack : 0;
        $kensa->henkei = $request->henkei ? $request->henkei : 0;
        $kensa->hanazaki = $request->hanazaki ? $request->hanazaki : 0;
        $kensa->kizu = $request->kizu ? $request->kizu : 0;
        $kensa->kaburi = $request->kaburi ? $request->kaburi : 0;
        $kensa->shiromoya = $request->shiromoya ? $request->shiromoya : 0;
        $kensa->shimi = $request->shimi ? $request->shimi : 0;
        $kensa->pitto = $request->pitto ? $request->pitto : 0;
        $kensa->misto = $request->misto ? $request->misto : 0;
        $kensa->other = $request->other ? $request->other : 0;
        $kensa->gores = $request->gores ? $request->gores : 0;
        $kensa->regas = $request->regas ? $request->regas : 0;
        $kensa->silver = $request->silver ? $request->silver : 0;
        $kensa->hike = $request->hike ? $request->hike : 0;
        $kensa->burry = $request->burry ? $request->burry : 0;
        $kensa->others = $request->others ? $request->others : 0;
        $kensa->total_ok = $request->total_ok;
        $kensa->total_ng = $request->total_ng;
        $kensa->p_total_ok = $request->p_total_ok;
        $kensa->p_total_ng = $request->p_total_ng;
        $kensa->keterangan = $request->keterangan;
        $kensa->created_by = Auth::user()->name;
        $kensa->created_at = Carbon::now();
        $kensa->date_time = $request->tanggal_k . ' ' . $request->waktu_k;
        $kensa->save();

        $masterdata->stok_bc -= $request->total_ok;
        $masterdata->stok_bc -= $request->total_ng;
        $masterdata->stok += $request->total_ok;
        $masterdata->save();

        $plating = Plating::find($request->id_plating);
        $plating->status = '3';
        $plating->save();

        return redirect()->route('kensa.tambah')->with('success', 'Data berhasil disimpan');
    }

    //edit data
    public function edit(Request $request, $id)
    {
        // $kensa = kensa::where('id', '=', $id)->first();
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $kensa = kensa::find($id);
        $plating = Plating::find($kensa->id_plating);
        $masterdata = MasterData::all();
        $jml_bar = kensa::where('tanggal_k', '=', $date)->count();
        return view('kensa.kensa-edit', compact('kensa', 'masterdata', 'date', 'jml_bar'));
    }

    //hapus data
    public function delete($id)
    {
        $kensa = kensa::find($id);

        // $masterdata = kensa::where('id_masterdata', '=', $kensa->id_masterdata)->first();
        $masterdata = MasterData::find($kensa->id_masterdata);
        $plating = Plating::find($kensa->id_plating);
        if ($plating->kategori == 'PST') {
            $plating->status = '6';
            $plating->save();
            $kensa->delete();
        } else {
            $masterdata->stok = $masterdata->stok - $kensa->total_ok;
            $masterdata->stok_bc = $masterdata->stok_bc + $kensa->qty_bar;
            $plating->status = '2';
            $plating->save();
            $masterdata->save();
            $kensa->delete();
        }

        return response()->json([
            'success' => true
        ]);
    }

    //update data
    public function update(Request $request, $id)
    {
        $kensa = kensa::find($id);
        $total_ok_prev = $kensa->total_ok;
        $total_ok_new = $kensa->total_ok = 0;

        $kensa->id_masterdata;
        $kensa->id_plating;
        $kensa->tanggal_k = $request->tanggal_k;
        $kensa->waktu_k = $request->waktu_k;
        $kensa->no_part = $request->no_part;
        $kensa->part_name = $request->part_name;
        $kensa->no_bar = $request->no_bar;
        $kensa->qty_bar = $request->qty_bar;
        $kensa->cycle = $request->cycle;
        $kensa->nikel = $request->nikel ? $request->nikel : 0;
        $kensa->butsu = $request->butsu ? $request->butsu : 0;
        $kensa->hadare = $request->hadare ? $request->hadare : 0;
        $kensa->hage = $request->hage ? $request->hage : 0;
        $kensa->moyo = $request->moyo ? $request->moyo : 0;
        $kensa->fukure = $request->fukure ? $request->fukure : 0;
        $kensa->crack = $request->crack ? $request->crack : 0;
        $kensa->henkei = $request->henkei ? $request->henkei : 0;
        $kensa->hanazaki = $request->hanazaki ? $request->hanazaki : 0;
        $kensa->kizu = $request->kizu ? $request->kizu : 0;
        $kensa->kaburi = $request->kaburi ? $request->kaburi : 0;
        $kensa->shiromoya = $request->shiromoya ? $request->shiromoya : 0;
        $kensa->shimi = $request->shimi ? $request->shimi : 0;
        $kensa->pitto = $request->pitto ? $request->pitto : 0;
        $kensa->misto = $request->misto ? $request->misto : 0;
        $kensa->other = $request->other ? $request->other : 0;
        $kensa->gores = $request->gores ? $request->gores : 0;
        $kensa->regas = $request->regas ? $request->regas : 0;
        $kensa->silver = $request->silver ? $request->silver : 0;
        $kensa->hike = $request->hike ? $request->hike : 0;
        $kensa->burry = $request->burry ? $request->burry : 0;
        $kensa->others = $request->others ? $request->others : 0;
        $kensa->total_ok = $request->total_ok;
        $kensa->total_ng = $request->total_ng;
        $kensa->p_total_ok = $request->p_total_ok;
        $kensa->p_total_ng = $request->p_total_ng;
        $kensa->keterangan = $request->keterangan;
        $kensa->updated_by = Auth::user()->name;
        $kensa->save();

        $masterdata = MasterData::find($kensa->id_masterdata);
        $masterdata->stok = ($masterdata->stok - $total_ok_prev) + (int)$request->total_ok;
        $masterdata->save();

        // return redirect()->route('kensa')->with('success', 'Data berhasil di update');
        Alert::Success('Success!', 'Data Berhasil Di Edit!');
        return redirect()->route('kensa');
    }

    public function printKanban(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $pengiriman = Pengiriman::join('masterdata', 'masterdata.id', '=', 'pengiriman.id_masterdata')
            ->select('pengiriman.*', 'masterdata.part_name', 'masterdata.qty_bar')
            ->get();

        $masterdata = MasterData::all();

        return view('kensa.print-kanban', compact('pengiriman', 'masterdata', 'date'));
    }

    public function ajax(Request $request)
    {
        $id_masterdata['id_masterdata'] = $request->id_masterdata;
        $ajax_barang = MasterData::where('id', $id_masterdata)->get();

        return view('kensa.kensa-ajax', compact('ajax_barang'));
    }

    public function ajaxKanban(Request $request)
    {
        $id_masterdata['id_masterdata'] = $request->id_masterdata;
        $ajax_barang = MasterData::where('id', $id_masterdata)->get();

        $date = Carbon::parse($request->tgl_kanban)->format('Y-m-d');

        $q = $ajax_barang->first()->pengirimans()->where('tgl_kanban', '=', $date)->orderBy('id', 'desc')->first();
        $kode = $q ? $q->no_kartu + 1 : '01';

        return view('kensa.print-kanban-ajax', compact('ajax_barang', 'kode'));
    }

    public function kanbansimpan(Request $request)
    {
        // dd($request->all());
        $masterdata = MasterData::find($request->id_masterdata);
        $pengiriman = pengiriman::all();
        if ($masterdata->stok < $request->kirim_assy) {
            Alert::Warning('Gagal', 'Stok Kurang!');
            return redirect()->route('kensa.printKanban');
        } else if ($masterdata->stok < $request->kirim_painting) {
            Alert::Warning('Gagal', 'Stok Kurang!');
            return redirect()->route('kensa.printKanban');
        } else if ($masterdata->stok < $request->kirim_ppic) {
            Alert::Warning('Gagal', 'Stok Kurang!');
            return redirect()->route('kensa.printKanban');
        } else {
            $pengiriman = new pengiriman();
            $pengiriman->tgl_kanban = $request->tgl_kanban;
            $pengiriman->waktu_kanban = $request->waktu_kanban;
            $pengiriman->id_masterdata = $request->id_masterdata;
            $pengiriman->pic_packing = $request->pic_packing;
            $pengiriman->no_part = $request->no_part;
            $pengiriman->part_name = $request->part_name;
            $pengiriman->model = $request->model;
            $pengiriman->bagian = $request->bagian;
            $pengiriman->no_kartu = $request->no_kartu;
            $pengiriman->next_process = $request->next_process;
            $pengiriman->kirim_painting = $request->kirim_painting;
            $pengiriman->kirim_assy = $request->kirim_assy;
            $pengiriman->kirim_ppic = $request->kirim_ppic;
            $pengiriman->std_qty = $request->std_qty;
            $pengiriman->created_at = Carbon::now();
            $pengiriman->date_time = $request->tgl_kanban . ' ' . $request->waktu_kanban;
            $pengiriman->save();


            // $pengiriman = Pengiriman::create([
            //     'tgl_kanban' => $request->tgl_kanban,
            //     'waktu_kanban' => $request->waktu_kanban,
            //     'id_masterdata' => $request->id_masterdata,
            //     'no_part' => $request->no_part,
            //     'part_name' => $request->part_name,
            //     'model' => $request->model,
            //     'bagian' => $request->bagian,
            //     'no_kartu' => $request->no_kartu,
            //     'next_process' => $request->next_process,
            //     'kirim_painting' => $request->kirim_painting,
            //     'kirim_assy' => $request->kirim_assy,
            //     'kirim_ppic' => $request->kirim_ppic,
            //     'std_qty' => $request->std_qty,
            //     'created_at' => Carbon::now(),
            // ]);

            $masterdata->stok -= $request->kirim_assy;
            $masterdata->stok -= $request->kirim_painting;
            $masterdata->stok -= $request->kirim_ppic;
            $masterdata->no_kartu = $request->no_kartu;
            $masterdata->save();

            return redirect()->route('kensa.cetak_kanban',  ['id' => $pengiriman->id]);

            // return redirect()->route('kensa.printKanban')->with('toast_success', 'Data berhasil disimpan');

        }
    }

    public function cetak_kanban(Request $request, $id)
    {
        $pengiriman = $data['pengiriman'] = Pengiriman::findOrFail($id);

        $filepath = storage_path('app/' . ' ' . Carbon::now()->format('dmYhis') . ' ' . md5($id));

        /**
         * PDF
         */

        $jumlah = $pengiriman->kirim_assy + $pengiriman->kirim_painting + $pengiriman->kirim_ppic;
        $print = ceil($jumlah / $pengiriman->std_qty);
        $sisa = $jumlah;
        $jml_print = $pengiriman->no_kartu + $print - 1;

        foreach (range($pengiriman->no_kartu, $jml_print) as $i) {
            $data['no_kartu'] = $i;
            $data['qty'] = $sisa >= $pengiriman->std_qty ? $pengiriman->std_qty : $sisa;
            $sisa = $jumlah - $pengiriman->std_qty;

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kensa.cetak-kanban', $data)->setPaper([0.0, 0.0, 226.772, 311.811], 'landscape');
            $pdf->save($filepath . '_' . $i . '.pdf');
            $pdf = new \Spatie\PdfToImage\Pdf($filepath . '_' . $i . '.pdf');
            $pdf->setOutputFormat('png')
                ->width(800)
                ->saveImage($filepath . '_' . $i . '.png');

            $sourceImage = new \Imagick($filepath . '_' . $i . '.png');
            $sourceImage->rotateImage(new \ImagickPixel(), 90);
            $sourceImage->writeImage($filepath . '_' . $i . '.png');

            unlink($filepath . '_' . $i . '.pdf');

            ini_set('max_execution_time', 500);

            /**
             * PRINTING
             */
            //$connector = new WindowsPrintConnector("smb://192.168.20.93/epsonpos");
            //https://github.com/mike42/escpos-php/issues/65
            $connector = new WindowsPrintConnector("TM-T82II-Kensa");
            $printer = new Printer($connector);

            try {
                $tux = EscposImage::load($filepath . '_' . $i . '.png', false);
                $printer->graphics($tux);
                $printer->cut();
            } catch (Exception $e) {
                dd($e->getMessage());
            } finally {
                $printer->close();
            }
        }
        $pengiriman->no_kartu = $jml_print;
        $pengiriman->save();
        return redirect()->route('kensa.printKanban')->with('toast_success', 'Data Berhasil Di Print');
    }

    public function pengiriman(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $pengiriman = Pengiriman::join('masterdata', 'masterdata.id', '=', 'pengiriman.id_masterdata')
            ->select('pengiriman.*', 'masterdata.part_name', 'masterdata.qty_bar')
            ->where('tgl_kanban', '=', $date)
            ->orderBy('tgl_kanban', 'desc')->orderBy('waktu_kanban', 'desc')
            ->get();

        $masterdata = MasterData::all();
        return view('kensa.pengiriman-index', compact('pengiriman', 'masterdata', 'date'));
    }

    public function pengirimanDelete($id)
    {
        $pengiriman = pengiriman::find($id);
        $masterdata = MasterData::find($pengiriman->id_masterdata);
        $masterdata->stok = $masterdata->stok + ($pengiriman->kirim_assy + $pengiriman->kirim_painting + $pengiriman->kirim_ppic);
        $masterdata->save();
        $pengiriman->delete();

        return response()->json([
            'success' => true
        ]);
    }
}

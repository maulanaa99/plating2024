<?php

namespace App\Http\Controllers;

use App\Exports\UnrackingExport;
use App\Models\MasterData;
use App\Models\Plating;
use App\Models\unracking_t;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use RealRashid\SweetAlert\Facades\Alert;

class UnrackingController_T extends Controller
{
    //tampil data
    public function index(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');

        $plating = Plating::join('masterdata', 'masterdata.id', '=', 'plating.id_masterdata')
            ->select('plating.id', 'plating.id_masterdata', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.no_bar', 'masterdata.no_part', 'masterdata.part_name', 'plating.katalis', 'plating.channel', 'plating.grade_color', 'plating.qty_bar', 'plating.cycle', 'plating.tgl_lot_prod_mldg', 'plating.created_by', 'plating.kategori', 'plating.keterangan', 'masterdata.jenis', 'plating.tanggal_u', 'plating.waktu_in_u', 'plating.qty_aktual', 'plating.updated_by', 'plating.status')
            ->whereBetween('tanggal_r', [$start_date, $end_date])
            ->orderBy('tanggal_r', 'asc')
            ->orderBy('waktu_in_r', 'asc')
            ->get();


        // $plating = Plating::whereBetween('tanggal_r', [$start_date, $end_date])
        //     ->orderBy('tanggal_r', 'desc')
        //     ->orderBy('waktu_in_r', 'desc')
        //     ->get();
        return view('unracking_t.unracking_t', compact('plating', 'start_date', 'end_date'));
    }

    //edit data
    public function edit(Request $request, $id)
    {
        $plating = Plating::join('masterdata', 'masterdata.id', '=', 'plating.id_masterdata')
            ->select('plating.id', 'plating.id_masterdata', 'plating.tanggal_r', 'plating.waktu_in_r', 'plating.no_bar', 'masterdata.no_part', 'masterdata.part_name', 'plating.katalis', 'plating.channel', 'plating.grade_color', 'plating.qty_bar', 'plating.cycle', 'plating.tgl_lot_prod_mldg', 'plating.created_by', 'plating.kategori', 'plating.keterangan', 'masterdata.jenis', 'plating.tanggal_u', 'plating.qty_aktual', 'plating.waktu_in_u', 'plating.status')
            ->find($id);
        // $plating = unracking_t::find($id);
        // dd($plating);

        $estimated = Carbon::parse($plating->tanggal_r)->format('Y-m-d');
        $estimated2 = Carbon::parse($plating->waktu_in_r)->format('H:i:s');
        $estimated3 = $estimated . ' ' . $estimated2;

        $estimated3 = Carbon::parse($estimated3);
        $estimated3->addHours(4)->addMinutes(22);

        $estimated3 = $estimated3->format('Y-m-d H:i:s');
        // dd($estimated3);

        $masterdata = MasterData::find($plating->id_masterdata);

        $previous = unracking_t::where('id', '<', $plating->id)->max('id');
        $next = unracking_t::where('id', '>', $plating->id)->min('id');

        return view('unracking_t.unracking_t-edit', compact('previous', 'next', 'plating', 'masterdata', 'id', 'estimated3'));
    }

    //update data
    public function update(Request $request, $id)
    {
        $plating = Plating::find($id);
        $masterdata = MasterData::find($plating->id_masterdata);

        $qty_aktual_prev = $plating->qty_aktual;
        $previous = unracking_t::where('id', '<', $plating->id)->max('id');
        $next = unracking_t::where('id', '>', $plating->id)->min('id');

        $estimated = Carbon::parse($plating->tanggal_r)->format('Y-m-d');
        $estimated2 = Carbon::parse($plating->waktu_in_r)->format('H:i:s');
        $estimated3 = $estimated . ' ' . $estimated2;

        $estimated3 = Carbon::parse($estimated3);
        $estimated3->addHours(4)->addMinutes(22);

        $estimated3 = $estimated3->format('Y-m-d H:i:s');



        if ($request->qty_aktual > $request->qty_bar) {
            Alert::Warning('Gagal', 'Qty Aktual Salah!!');
            return redirect()->route('unracking_t.edit', compact('plating', 'masterdata', 'id'));
        } elseif ($plating->kategori == 'PST' && $request->qty_aktual == 0) {
            $plating->tanggal_u = null;
            $plating->waktu_in_u = null;
            $plating->date_time = null;
            $plating->qty_aktual = null;
            $plating->cycle = $request->cycle;
            $plating->updated_by = null;
            $plating->status = '1';
            $plating->save();
            $masterdata->stok_bc = $masterdata->stok_bc - $qty_aktual_prev + $request->qty_aktual;
            $masterdata->save();
        } elseif ($plating->kategori == 'PST') {
            $plating->tanggal_u = $request->tanggal_u;
            $plating->waktu_in_u = $request->waktu_in_u;
            $plating->date_time = $request->tanggal_u . ' ' . $request->waktu_in_u;
            $plating->no_bar = $request->no_bar;
            $plating->qty_bar = $request->qty_bar;
            $plating->qty_aktual = $request->qty_aktual;
            $plating->cycle = $request->cycle;
            $plating->updated_by = Auth::user()->name;
            $plating->updated_at = Carbon::now();
            $plating->status = '5';
            $plating->save();
            $masterdata->stok_bc = $masterdata->stok_bc - $qty_aktual_prev + $request->qty_aktual;
            $masterdata->save();
        } elseif ($request->qty_aktual == 0) {
            $plating->tanggal_u = null;
            $plating->waktu_in_u = null;
            $plating->date_time = null;
            $plating->qty_aktual = null;
            $plating->cycle = $request->cycle;
            $plating->updated_by = null;
            $plating->status = '1';
            $plating->save();
            $masterdata->stok_bc = $masterdata->stok_bc - $qty_aktual_prev + $request->qty_aktual;
            $masterdata->save();
        } elseif ($plating->status == '3' || $plating->status == '6' || $plating->status == '7') {
            $plating->cycle = $request->cycle;
            $plating->tanggal_u = $request->tanggal_u;
            $plating->waktu_in_u = $request->waktu_in_u;
            $plating->date_time = $request->tanggal_u . ' ' . $request->waktu_in_u;
            $plating->save();
            Alert::info('Success', 'Cycle Berhasil diubah!!');
            return redirect()->route('unracking_t.edit', compact('plating', 'masterdata', 'id'));
        } elseif ($plating->qty_aktual != '') {
            $plating->tanggal_u = $request->tanggal_u;
            $plating->waktu_in_u = $request->waktu_in_u;
            $plating->date_time = $request->tanggal_u . ' ' . $request->waktu_in_u;
            $plating->no_bar = $request->no_bar;
            $plating->qty_bar = $request->qty_bar;
            $plating->qty_aktual = $request->qty_aktual;
            $plating->cycle = $request->cycle;
            $plating->updated_by = Auth::user()->name;
            $plating->updated_at = Carbon::now();
            // $plating->status = '1';
            $plating->save();
            // dd($request->no_bar);
            $masterdata->stok_bc = $masterdata->stok_bc - $qty_aktual_prev + $request->qty_aktual;
            $masterdata->save();
        } else {
            $plating->tanggal_u = $request->tanggal_u;
            $plating->waktu_in_u = $request->waktu_in_u;
            $plating->date_time = $request->tanggal_u . ' ' . $request->waktu_in_u;
            $plating->no_bar = $request->no_bar;
            $plating->qty_bar = $request->qty_bar;
            $plating->qty_aktual = $request->qty_aktual;
            $plating->cycle = $request->cycle;
            $plating->updated_by = Auth::user()->name;
            $plating->updated_at = Carbon::now();
            $plating->status = '2';
            $plating->save();

            $masterdata->stok_bc = $masterdata->stok_bc - $qty_aktual_prev + $request->qty_aktual;
            $masterdata->save();
        }

        $to_racking = Plating::where('id', '=', $plating->id)->get();
        if (isset($request->to_racking)) {
            return redirect('racking_t/edit/' . $plating->id);
        }

        if (isset($request->next)) {
            return redirect('/unracking_t/edit/' . $request->next);
        } else {
            $previous = unracking_t::where('id', '<', $plating->id)->max('id');
            $next = unracking_t::where('id', '>', $plating->id)->min('id');

            Alert::success('Success', 'Data Berhasil Disimpan!');
            return View::make('unracking_t.unracking_t-edit', compact('plating', 'masterdata', 'estimated3'))->with('previous', $previous)->with('next', $next);
        }
    }


    public function search(Request $request)
    {
        $keyword = $request->search;
        $plating = unracking_t::where('part_name', 'like', "%" . $keyword . "%")->paginate(124);
        return view('unracking_t.unracking_t', compact('plating'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function searchDater(Request $request)
    {
        if (request()->start_date || request()->end_date) {
            $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
            $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
            $plating = unracking_t::whereBetween('tanggal_u', [$start_date, $end_date])->paginate(75);
        } else {
            $plating = unracking_t::latest()->paginate(75);
        }
        return view('unracking_t.unracking_t', compact('plating'));
    }

    public function exportexcel()
    {
        return Excel::download(new UnrackingExport, 'Unracking.xlsx');
    }

    public function unrackingPrint($id)
    {
        $plating = $data['plating'] = unracking_t::findOrFail($id);
        $filepath = storage_path('app/unracking' . md5($id));

        /**
         * PDF
         */

        $previous_number = 0;
        $print = 0;

        foreach (range($previous_number, $print) as $i) {
            $data['qty'] = $plating->qty_aktual;
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('unracking_t.unracking_t-print', $data)->setPaper([0.0, 0.0, 226.772, 311.811], 'landscape');
            $pdf->save($filepath . '_' . $i . '.pdf');
            $pdf = new \Spatie\PdfToImage\Pdf($filepath . '_' . $i . '.pdf');
            $pdf->setOutputFormat('png')
                ->width(800)
                ->saveImage($filepath . '_' . $i . '.png');

            $sourceImage = new \Imagick($filepath . '_' . $i . '.png');
            $sourceImage->rotateImage(new \ImagickPixel(), 90);
            $sourceImage->writeImage($filepath . '_' . $i . '.png');

            unlink($filepath . '_' . $i . '.pdf');

            /**
             * PRINTING
             */
            $connector = new WindowsPrintConnector("TM-T82II");
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
            $plating->save();
        }
        return redirect()->route('unracking_t')->with('toast_success', 'Data Berhasil Di Print');
    }
}

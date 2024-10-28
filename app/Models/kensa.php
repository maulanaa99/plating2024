<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class kensa extends Model
{
    use HasFactory;

    protected $table = 'kensa';
    protected $fillable = [
        'id_plating', 'tanggal_k', 'waktu_k', 'id_masterdata', 'no_part', 'part_name', 'no_bar', 'qty_bar', 'cycle', 'nikel', 'butsu', 'hadare', 'hage', 'moyo', 'fukure', 'crack',
        'henkei', 'hanazaki', 'kizu', 'kaburi', 'shiromoya', 'shimi', 'pitto', 'misto', 'other', 'gores', 'regas', 'silver', 'hike', 'burry', 'others', 'total_ok', 'total_ng', 'p_total_ok',
        'p_total_ng', 'created_by', 'updated_by', 'created_at', 'updated_at', 'keterangan, date_time'
    ];

    public static function joins()
    {
        $data = DB::table('kensa')
            ->join('masterdata', 'masterdata.id', 'kensa.id_masterdata')
            ->join('plating', 'plating.id', 'kensa.id_plating')
            ->select('kensa.*', 'masterdata.stok_bc', 'plating.part_name', 'plating.no_bar', 'plating.qty_bar', 'plating.cycle', 'plating.qty_aktual', 'plating.tanggal_u', 'plating.waktu_in_u', 'masterdata.jenis', 'plating.date_time');
        return $data;
    }
}

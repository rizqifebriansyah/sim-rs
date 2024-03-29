<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $fillable = [
        "kodebooking",
        "nomorkartu",
        "nik",
        "nohp",
        "kodepoli",
        "norm",
        "tanggalperiksa",
        "kodedokter",
        "jampraktek",
        "jeniskunjungan",
        "nomorreferensi",
        "nomorsuratkontrol",
        "nomorsep",
        "jenispasien",
        "namapoli",
        "pasienbaru",
        "namadokter",
        "nomorantrean",
        "angkaantrean",
        "estimasidilayani",
        "sisakuotajkn",
        "kuotajkn",
        "sisakuotanonjkn",
        "kuotanonjkn",
        "taskid",
        "keterangan",
        "status_api",
        "user",
        "nama",
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'nik', 'nik');
    }
}

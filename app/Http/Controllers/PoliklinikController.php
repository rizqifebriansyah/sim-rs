<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalPoli;
use App\Models\Poliklinik;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    public function index()
    {
        $polis = Poliklinik::get();
        return view('simrs.poli_index', [
            'polis' => $polis
        ]);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $poli = Poliklinik::find($id);
        if ($poli->status == '0') {
            $status = 1;
        } else {
            $status = 0;
        }
        $poli->update([
            'status' => $status,
        ]);
        return redirect()->route('poli.index');
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        //
    }
}

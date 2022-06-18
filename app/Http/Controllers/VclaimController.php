<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\VclaimBPJSController;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class VclaimController extends Controller
{
    public function monitoring_pelayanan_peserta(Request $request)
    {
        $response = null;
        $api = new VclaimBPJSController();
        if ($request->nomorkartu) {
            $response = $api->peserta_nomorkartu($request);
        }
        if ($request->nik) {
            $response = $api->peserta_nik($request);
            if (isset($response->response->peserta->noKartu)) {
                $request["nomorkartu"] = $response->response->peserta->noKartu;
            }
        }
        $monitoring = $api->monitoring_pelayanan_peserta($request);
        return view('vclaim.monitoring_pelayanan_peserta', [
            'request' => $request,
            'response' => $response,
            'monitoring' => $monitoring,
        ]);
    }
    public function delete_sep($noSep, Request $request)
    {
        $api = new VclaimBPJSController();
        $request['noSep'] = $noSep;
        $response = $api->delete_sep($request);
        if ($response->metaData->code == '200') {
            Alert::success('Success', 'Data berhasil dihapus. ' . $response->metaData->message);
        } else {
            Alert::error('Error', 'Data gagal dihapus. ' .  $response->metaData->message);
        }
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Paket;

class PaketController extends Controller
{
    public $user;

    public function __construct()
    {
        // this->$user = JWTAuth::parseToken()->authenticate();
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'harga' => 'required',

        ]);

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $paket = new Paket();
        $paket->jenis = $request->jenis;
        $paket->harga = $request->harga;
        $paket->save();
        $data = Paket::where('id_paket', '=', $paket->id_paket)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Tambah Data Paket',
            'data' => $data
        ]);
    }

    public function getAll()
    {

        $data = Paket::get();

        return response()->json(['data' => $data]);
    }

    public function getdata(request $request, $id)
    {
        $paket = Paket::get()->where('id_paket', $id)->first();
        return Response()->json($paket);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'harga' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $paket = Paket::find($id);
        $paket->update($request->all());
        $paket->harga   = $request->harga;
        $paket->save();
        $data = Paket::where('id_paket', '=', $paket->id_paket)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Edit Member',
            'data' => $data
        ]);
    }

    public function delete($id)
    {
        $hapus = Paket::where('id_paket', $id)->delete();

        if ($hapus) {
            return Response()->json(['status' => 1]);
        } else {
            return Response()->json(['status' => 0]);
        }
    }
}

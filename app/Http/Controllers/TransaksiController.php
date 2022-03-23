<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\Detail_Transaksi;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransaksiController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        // memastikan user yang masuk melalui autentifikasi
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required',

        ]);

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $transaksi = new Transaksi();
        $transaksi->id_member = $request->id_member;
        $transaksi->tgl = Carbon::now(); //gunanya carbon supaya scr otomatis mengambil tanggal
        $transaksi->batas_waktu = Carbon::now()->addDays(3); //dibuat prosesnya selama 3 hari
        // $transaksi->tgl_bayar = $request->tgl_bayar;
        $transaksi->status = 'baru';
        $transaksi->dibayar = 'belum_dibayar';
        $transaksi->id_user = $this->user->id;
        // $transaksi->total_bayar = 0;
        // mengambil user dari data user yang login
        $transaksi->save();
        $data = Transaksi::where('id_transaksi', '=', $transaksi->id_transaksi)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Tambah Data Transaksi',
            'data' => $data
        ]);
    }

    public function getAll()
    {
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
            ->select('transaksi.*', 'member.nama_member')
            ->get();

        return response()->json(['success' => true, 'data' => $data]);
    }

    // menggunakan null untuk tgl_bayar nullable sebagai kterangannya
    // untuk ambil detail dari transaksi tertentu tergantung admin atau kasir memasukkan id user

    public function getById($id)
    {
        $data = Transaksi::where('id_transaksi', '=', $id)->first();
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
            ->select('transaksi.*', 'member.nama_member')
            ->where('transaksi.id_transaksi', '=', $id)
            ->first();
        return response()->json($data);
    }


    public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $transaksi = Transaksi::where('id_transaksi', '=', $id)->first();
        $transaksi->status = $request->status;

        $transaksi->save();

        $data = Transaksi::where('id_transaksi', '=', $transaksi->id_transaksi)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil Ubah Status Transaksi',
            'data' => $data
        ]);
    }

    public function bayar($id)
    {
        $transaksi = Transaksi::where('id_transaksi', '=', $id)->first();
        $total = Detail_Transaksi::where('id_transaksi', $id)->sum('subtotal');

        $transaksi->tgl_bayar = Carbon::now();
        $transaksi->status = "diambil";
        $transaksi->dibayar = "dibayar ";
        $transaksi->total_bayar = $total;

        $transaksi->save();

        $data = Transaksi::where('id_transaksi', '=', $transaksi->id_transaksi)->first();
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran Berhasili',
            'data' => $data
        ]);
    }


    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_outlet' => 'required',
            'tahun' => 'required',
            'bulan' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $id_outlet = $request->id_outlet;

        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
            ->select('transaksi.id_transaksi', 'transaksi.tgl', 'transaksi.tgl_bayar', 'transaksi.total_bayar', 'member.nama_member')
            ->where('user.id_outlet', '=', $id_outlet)
            ->whereYear('tgl', '=', $tahun)
            ->whereMonth('tgl', '=', $bulan)
            ->get();

        return response()->json($data);
    }
}

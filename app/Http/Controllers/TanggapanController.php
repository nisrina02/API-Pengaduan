<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tanggapan;
use JWTAuth;
use Carbon\Carbon;

class TanggapanController extends Controller
{
    public $response;
    public $user;
    public function __construct(){
        $this->response = new ResponseHelper();

        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function getAllTanggapan($limit = NULL, $offset = NULL)
    {
        
            $data["count"] = Tanggapan::count();

            if($limit == NULL && $offset == NULL){
                $data["tanggapan"] = Tanggapan::all();
            } else {
                $data["tanggapan"] = Tanggapan::take($limit)->skip($offset)->get();
            }

        return $this->response->successData($data);
    }

    public function getTanggapan($id_pengaduan)
    {
            $data["tanggapan"] = DB::table('tanggapan')
                                        ->join('pengaduan', 'pengaduan.id_pengaduan', '=', 'tanggapan.id_pengaduan')
                                        ->join('users', 'users.id', '=', 'pengaduan.id_user')
                                        ->select('tanggapan.tgl_tanggapan', 'tanggapan.tanggapan', 'users.nama')
                                        ->where('tanggapan.id_pengaduan', '=', $id_pengaduan)
                                        ->get();

            return $this->response->successData($data);
        
    }

    public function send(Request $request, $id_pengaduan)
    {

		$tanggapan =  Tanggapan::where('id_pengaduan', $id_pengaduan)->first();
        
        //jika belum ada tanggapan brarti insert data baru
        if($tanggapan == NULL){
            $tanggapan = new Tanggapan();
        }
		
		$tanggapan->tgl_tanggapan  = Carbon::now();
		$tanggapan->isi_tanggapan      = $request->isi_tanggapan;
		$tanggapan->id_petugas     = $this->user->id; //ambil id_petugas dari JWT token yang sedang aktif
		$tanggapan->save();

        return $this->response->successResponseData('Data tanggapan berhasil dikirim', $tanggapan);
    }
}

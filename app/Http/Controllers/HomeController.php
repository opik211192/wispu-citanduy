<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //buatkan data pengaduan yang masuk ahri ini saja
        $pengaduans = Pengaduan::whereDate('created_at', Carbon::today())->get();
        $totalPengaduanHariIni = $pengaduans->count();

        //total semaua data pengaduan
        $totalPengaduanSemua = Pengaduan::count();

        return view('home', compact('totalPengaduanHariIni', 'totalPengaduanSemua'));
    }
}

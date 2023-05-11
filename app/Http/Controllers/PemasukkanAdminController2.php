<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananDetails;
use Illuminate\Http\Request;

class PemasukkanAdminController2 extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        //fungsi eloquent menampilkan data menggunakan paginaon

        $pesanan = Pesanan::where('user_id',11)->orderBy('tanggal', 'desc')->paginate(5);
        return view('adminPusat.pesanindex2', compact('pesanan'));
    }
    public function create()
    {
        return view('adminPusat.pesancreate2');
    }
    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'tanggal' => 'required', 'status' => 'required', 'jumlah_harga' => 'required'
        ]);
        //fungsi eloquent untuk menambah data 
        Pesanan::create($request->all());

        //jika data berhasil ditambahkan, akan kembali ke halaman utama 
        return redirect()->route('adminPusatPesan22.index')
            ->with('success', 'Transaksi Berhasil Ditambahkan');
    }
    public function show($id)
    {
        //menampilkan detail data dengan menemukan/berdasarkan ... Menu
        $pesanan = Pesanan::where('id', $id)->first();
        $pesanan_details = PesananDetails::where('pesanan_id', $pesanan->id)->get();

        return view('adminPusat.pesandetail2', compact('pesanan', 'pesanan_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Menu untuk diedit
        $pesanan = Pesanan::find($id);
        return view('adminPusat.pesanedit2', compact('pesanan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //melakukan validasi data
        $request->validate([
            'tanggal' => 'required', 'status' => 'required', 'jumlah_harga' => 'required'
        ]);

        //fungsi eloquent untuk mengupdate data inputan kita 
        Pesanan::find($id)->update($request->all());

        //jika data berhasil diupdate, akan kembali ke halaman utama 
        return redirect()->route('adminPusatPesan22.index')
            ->with('success', 'Transaksi Berhasil Diperbarui');
    }

    public function destroy($id)
    {
        //fungsi eloquent untuk menghapus data 
        Pesanan::find($id)->delete();
        return redirect()->route('adminPusatPesan22.index')
            ->with('success', 'Transaksi Berhasil Dihapus');
    }
    public function cari(Request $request)
    {
        $keyword = $request->cari;
        $pesanan = Pesanan::where('user_id', 11)->where(function ($query) use ($keyword) {
            $query->where('tanggal', 'like', '%' . $keyword . '%')
                    ->orWhere('jumlah_harga', 'like', '%' . $keyword . '%')
                    ->orWhere('status', 'like', '%' . $keyword . '%');
        })->paginate(5);
        $pesanan->appends(['keyword' => $keyword]);
        return view('adminPusat.pesanindex2', compact('pesanan'));
    }
}

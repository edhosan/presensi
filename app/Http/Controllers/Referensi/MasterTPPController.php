<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\TPPKategori;
use Yajra\Datatables\Datatables;

class MasterTPPController extends Controller
{
	private $rules_kategori = [
	    'nm_kategori'  => 'required'
	  ];

	private $rules_jenis_pengeluaran = [
	    'jns_pengeluaran'  => 'required',
	    'kriteria'  => 'required'
	  ];

    public function index()
    {
    	return view('referensi.tpp_kategori');
    }

    public function createKategori()
    {
    	return view('referensi.tpp_kategori_form');
    }

    public function editKategori($id)
    {
    	$kategori = TPPKategori::find($id);

    	return view('referensi.tpp_kategori_form')->withData($kategori);
    }

    public function jenisPengeluaran($kategori_id)
    {
    	$kategori = TPPKategori::find($kategori_id);
    	return view('referensi.tpp_jenis_pengeluaran')->withKategori($kategori);
    }

    public function createJenisPengeluaran($kategori_id)
    {
    	$kategori = TPPKategori::find($kategori_id);
    	return view('referensi.tpp_jenis_pengeluaran_form')->withKategori($kategori);
    }

    public function saveKategori(Request $request)
    {
    	$this->validate($request, $this->rules_kategori);

	    TPPKategori::create([
	      'nm_kategori' => $request->nm_kategori
	    ]);

	    return redirect()->route('tpp.kategori.index')->with('success','Data berhasil disimpan!');
    }

    public function updateKategori(Request $request)
    {
    	$this->validate($request, $this->rules_kategori);
    	$kategori = TPPKategori::find($request->id);
    	$kategori->update([
    		'nm_kategori' => $request->nm_kategori
    	]);

	  	return redirect()->route('tpp.kategori.index')->with('success','Data berhasil disimpan!');
    }  

    public function saveJenisPengeluaran(Request $request)
    {
    	$this->validate($request, $this->rules_jenis_pengeluaran);
   		$kategori = TPPKategori::find($request->kategori_id);
   		$kategori->jenisPengeluaran()->create([
   			"jns_pengeluaran" => $request->jns_pengeluaran,
   			"krteria"	=> $request->kriteria
   		]);

 		return redirect()->route('tpp.jenis_pengeluaran', $request->kategori_id)->with('success','Data berhasil disimpan!');
    }  

    public function apiGetKategori()
    {
   	 	$data = TPPKategori::orderBy('nm_kategori','asc');

	    return Datatables::of($data)
	    ->make(true);
    }

    public function apiDeleteKategori(Request $request)
    {
    	$id = $request->data;
    	$status = TPPKategori::whereIn('id', $id)->delete();

    	return response()->json($status);
    }

    public function apiGetJenisPengeluaran($kategori_id)
    {
    	$kategori = TPPKategori::find($kategori_id);
    	$data = $kategori->jenisPengeluaran()->orderBy('jns_pengeluaran','asc');

    	return Datatables::of($data)
	    	->make(true);
    }
}

<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\TPPKategori;
use Yajra\Datatables\Datatables;
use App\Model\Eselon;
use App\Model\RincianPengeluaran;

class MasterTPPController extends Controller
{
	private $rules_kategori = [
	    'nm_kategori'  => 'required'
	  ];

	private $rules_jenis_pengeluaran = [
	    'jns_pengeluaran'  => 'required',
	    'kriteria'  => 'required'
	  ];

<<<<<<< HEAD
    private $rules_rincian_pengeluaran = [
        'kriteria_id'  => 'required',
        'lokasi_biasa'  => 'numeric',
        'lokasi_terpencil'  => 'numeric',
        'lokasi_sangat_terpencil'  => 'numeric',
        'tahun'  => 'required|numeric'
=======
    private $rincian_pengeluaran = [
        'kriteria_id'  => 'required',
        'tahun'  => 'required',
        'lokasi_biasa' => 'required',
        'lokasi_terpencil' => 'required',
        'lokasi_sangat_terpencil' => 'required'
>>>>>>> 6273a34b43f3376acdcc2edf5767e88eddce0d3b
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

    public function editJenisPengeluaran($kategori_id, $id)
    {
        $kategori = TPPKategori::find($kategori_id);
        $data = $kategori->jenisPengeluaran()->find($id);
        return view('referensi.tpp_jenis_pengeluaran_form')->withKategori($kategori)->withData($data);
    }

    public function rincianPengeluaran($kategori_id, $jns_pengeluaran_id)
    {
        $kategori = TPPKategori::find($kategori_id);
        $jenisPengeluaran = $kategori->jenisPengeluaran()->find($jns_pengeluaran_id);
        return view('referensi.tpp_rincian_pengeluaran')->withKategori($kategori)->withPengeluaran($jenisPengeluaran);
    }

    public function createRincianPengeluaran($kategori_id, $jns_pengeluaran_id)
    {
        $kategori = TPPKategori::find($kategori_id);
        $jenisPengeluaran = $kategori->jenisPengeluaran()->find($jns_pengeluaran_id);
        if($jenisPengeluaran){
            if($jenisPengeluaran->kriteria == 'ESELON'){
                $kriteria = Eselon::orderBy('nama','asc')                
                        ->pluck('nama','id_eselon');
                }
        }

        return view('referensi.tpp_rincian_pengeluaran_form')->withKategori($kategori)->withPengeluaran($jenisPengeluaran)->withKriteria($kriteria);
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

    public function updateJenisPengeluaran(Request $request)
    {
        $this->validate($request, $this->rules_jenis_pengeluaran);
        $kategori = TPPKategori::find($request->kategori_id);
        $jenisPengeluaran = $kategori->jenisPengeluaran()->find($request->id);       
        $jenisPengeluaran->update($request->all());

        return redirect()->route('tpp.jenis_pengeluaran', $request->kategori_id)->with('success','Data berhasil disimpan!');
    }

    public function saveRincianPengeluaran(Request $request)
    {
<<<<<<< HEAD
        $this->validate($request, $this->rules_rincian_pengeluaran);     
        RincianPengeluaran::create($request->all());

        return redirect()->route('tpp.rincian_pengeluaran', [$request->kategori_id, $request->tpp_jenis_pengeluaran_id])->with('success','Data berhasil disimpan!');
=======
        $this->validate($request, $this->rincian_pengeluaran);

        return $request;

>>>>>>> 6273a34b43f3376acdcc2edf5767e88eddce0d3b
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

    public function apiDeleteJenisPengeluaran(Request $request)
    {
        $id = $request->data;
        $kategori = TPPKategori::find($request->kategori_id);
        $status = $kategori->jenisPengeluaran()->whereIn('id',$id)->delete();     

        return response()->json($status);
    }

    public function apiGetJenisPengeluaran($kategori_id)
    {
    	$kategori = TPPKategori::find($kategori_id);
    	$data = $kategori->jenisPengeluaran()->orderBy('jns_pengeluaran','asc');

    	return Datatables::of($data)
	    	->make(true);
    }

    public function apiGetRincianPengeluaran($kategori_id, $jenis_pengeluaran_id)
    {
        $kategori = TPPKategori::find($kategori_id);
        $jenis_pengeluaran = $kategori->jenis_pengeluaran()->find($jenis_pengeluaran_id);
        if($jenis_pengeluaran){
            if($jenis_pengeluaran->kriteria == 'ESELON'){
                $data = RincianPengeluaran::where('tpp_jenis_pengeluaran_id', $jenis_pengeluaran_id)->get();
            }
        }

        return Datatables::of($data)
            ->make(true);
    }
}

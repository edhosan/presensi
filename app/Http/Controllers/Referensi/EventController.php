<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Event;
use Illuminate\Support\Collection;
use App\Library\MasterPresensi;

class EventController extends Controller
{
    public function index()
    {
      return view('referensi.event_list');
    }

    public function create()
    {
      return view('referensi.event_form');
    }

    public function edit($id)
    {
      $event = Event::find($id);

      return view('referensi.event_form')->withData(
        [
          'id'  => $event->id,
          'title' => $event->title,
          'start_date'  => date('d-m-Y', strtotime($event->start_date)),
          'end_date'  => date('d-m-Y', strtotime($event->end_date)),
        ]
      );
    }

    private function validateEvent($data)
    {
      return $this->validate($data, [
                'title' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]);
    }

    public function store(Request $request)
    {
      $this->validateEvent($request);

      $event = Event::create([
        'title' => $request->title,
        'start_date' => date('Y-m-d', strtotime($request->start_date) ),
        'end_date' => date('Y-m-d', strtotime($request->end_date) )
      ]);

      $master_presensi = new MasterPresensi();
      $master_presensi->sinkronisasiEvent($request->start_date, $request->end_date, $event->id);

      return redirect('kalendar_list')->with('success','Data berhasil disimpan!');
    }

    public function update(Request $request)
    {
      $id = $request->id;

      if(!empty($id)){
        $this->validateEvent($request);

        $event = Event::find($id);

        $event->update([
          'title' => $request->title,
          'start_date' => date('Y-m-d', strtotime($request->start_date) ),
          'end_date' => date('Y-m-d', strtotime($request->end_date) )
        ]);

        $master_presensi = new MasterPresensi();
        $master_presensi->sinkronisasiEvent($request->start_date, $request->end_date, $event->id);
      }

      return redirect('kalendar_list')->with('success','Data berhasil diupdate!');
    }

    public function delete($id)
    {
      $event = Event::find($id)->delete();

      $master_presensi = new MasterPresensi();
      $master_presensi->deleteEvent($id);

      return redirect('kalendar_list')->with('success','Data berhasil dihapus!');
    }

    public function apiEvent()
    {
      $data = Event::all();

      $arr = collect();
      foreach ($data as $value) {
        $arr->push([
            'id' => $value->id,
            'title' => $value->title,
            'start' => $value->start_date,
            'end' => $value->end_date,
            'url' => route('kalendar_edit',["id" => $value->id]),
         ]);
      }

      return response()->json($arr);
    }
}

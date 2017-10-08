@extends('layouts.app')
@push('css')
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="main">
    <div class="main-inner">
        <div class="container">
          <div class="row">
              <div class="span12">
                {!! Breadcrumbs::render('kalendar_form') !!}
                <div class="widget">
                  <div class="widget-header">
                    <i class="icon-file"></i>
                    <h3>Kalendar Kerja</h3>
                  </div>

                  <div class="widget-content">
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('kalendar_update'):route('kalendar_create') }}" novalidate="novalidate">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $data['id'] or 0 }}">
                      <fieldset>
                        <div class="control-group {{ $errors->has('title') ? 'error' : '' }}">
                            <label for="title" class="control-label">Judul</label>

                            <div class="controls">
                                <input id="title" type="text" class="span4" name="title" value="{{ $data['title'] or old('title') }}" autofocus>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('start_date') ? 'error' : '' }}">
                            <label for="start_date" class="control-label">Tanggal Mulai</label>

                            <div class="controls">
                              <input id="start_date" type="text" name="start_date" value="{{ $data['start_date'] or old('start_date') }}">

                                @if ($errors->has('start_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('end_date') ? 'error' : '' }}">
                            <label for="end_date" class="control-label">Tanggal Akhir</label>

                            <div class="controls">
                              <input id="end_date" type="text" name="end_date" value="{{ $data['end_date'] or old('end_date') }}">

                                @if ($errors->has('end_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('end_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-actions">
                          @permission(['delete-event','edit-event'])
                            @if(!empty($data))
                              <button type="button" id="delete" class="btn btn-danger">Hapus</button>
                            @endif
                            <button type="submit" class="btn btn-primary">Simpan</button>
                          @endpermission
                          <a href="{{ route('kalendar_list') }} " class="btn">Batal</a>
                        </div>
                      </fieldset>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script type="text/javascript">
$('#start_date').datepicker({ format: 'dd-mm-yyyy', language: 'id' });
$('#end_date').datepicker({ format: 'dd-mm-yyyy', language: 'id' });

$(document).ready(function() {
  $('#delete').click(function() {
    toastr.info("Apakah anda yakin ingin menghapus data ini?<br/><button type='button' id='confirmYes' class='btn btn-danger'>Ya</button> <button type='button' id='confirmNo' class='btn'>Tidak</button>",'Konfirmasi?',
    {
        positionClass: "toast-top-center",
        timeOut: 0,
        tapToDismiss: false,
        closeButton: false,
        allowHtml: true,
        onShown: function (toast) {
            $("#confirmYes").click(function(){
              window.location.href = "{{ route('kalendar_delete', ['id' => isset($data['id'])?$data['id']:'' ]) }}";
              toastr.remove();
            });

            $("#confirmNo").click(function(){
              toastr.remove();
            });
          }
    });
  });
});
</script>
@endpush

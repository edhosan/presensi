@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => 'User & Password Operator OPD!',
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')
        <p>
			Nama     : {{ $data['name'] }} <br>
			OPD	     : {{ $data['nm_unker'] }} <br>
			User Id  : {{ $data['username'] }} <br>
			Password : {{ $password }} <br>
        </p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        	'title' => 'Login',
        	'link' => 'http://presensi.beraukab.go.id'
    ])

@stop
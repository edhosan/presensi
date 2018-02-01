@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => 'User dan Password Operator OPD!',
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')
        <h3><strong>User dan password anda adalah :</strong> </h3>
        <p> 
            User ID  : {{ $data['username'] }} <br>
            Password : {{ $password }}
        </p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        	'title' => 'Menuju halaman Login',
        	'link' => 'http://presensi.beraukab.go.id'
    ])

@stop
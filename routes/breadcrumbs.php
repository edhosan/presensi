<?php
// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('home'));
});

// Home > Daftar User
Breadcrumbs::register('user', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar User', route('user'));
});

// Home > Daftar User > Register
Breadcrumbs::register('register', function($breadcrumbs)
{
    $breadcrumbs->parent('user');
    $breadcrumbs->push('Register', route('register'));
});

// Home > Daftar Tipe User
Breadcrumbs::register('role_list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Tipe User', route('role_list'));
});

// Home > Daftar Tipe User > Role
Breadcrumbs::register('role', function($breadcrumbs)
{
    $breadcrumbs->parent('role_list');
    $breadcrumbs->push('Tipe User', route('role'));
});

// Home > Daftar Hak Akses
Breadcrumbs::register('permission_list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Hak Akses', route('permission_list'));
});

// Home > Daftar Hak Akses > Permission
Breadcrumbs::register('permission', function($breadcrumbs)
{
    $breadcrumbs->parent('permission_list');
    $breadcrumbs->push('Hak Akses', route('permission'));
});

// Home > Daftar Pegawai
Breadcrumbs::register('datainduk_list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Pegawai', route('datainduk_list'));
});

// Home > Daftar Pegawai > Data Induk Pegawai
Breadcrumbs::register('datainduk_form', function($breadcrumbs)
{
    $breadcrumbs->parent('datainduk_list');
    $breadcrumbs->push('Data Induk Pegawai', route('datainduk_form'));
});

// Home > Daftar Kalendar Kerja
Breadcrumbs::register('kalendar_list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Kalendar Kerja', route('kalendar_list'));
});

// Home > Daftar Kalendar Kerja > Kelendar Kerja
Breadcrumbs::register('kalendar_form', function($breadcrumbs)
{
    $breadcrumbs->parent('kalendar_list');
    $breadcrumbs->push('Kalendar Kerja', route('kalendar_create'));
});

// Home > Daftar Jadwal Kerja
Breadcrumbs::register('jadwal_list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Jadwal Kerja', route('jadwal_list'));
});

// Home > Daftar Jadwal Kerja > Jadwal Kerja
Breadcrumbs::register('jadwal_form', function($breadcrumbs)
{
    $breadcrumbs->parent('jadwal_list');
    $breadcrumbs->push('Jadwal Kerja', route('jadwal_create'));
});

// Home > Daftar Jadwal Kerja > Hari Kerja
Breadcrumbs::register('hari', function($breadcrumbs)
{
    $breadcrumbs->parent('jadwal_list');
    $breadcrumbs->push('Hari Kerja', route('hari.create', 1));
});


?>

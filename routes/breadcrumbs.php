<?php
// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('home'));
});

// Home
Breadcrumbs::register('change.password', function($breadcrumbs)
{
    $breadcrumbs->parent('home', route('home'));
    $breadcrumbs->push('Ganti Password', route('change.password'));
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

// Home > Daftar Jadwal Pegawai
Breadcrumbs::register('peg_jadwal.list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Jadwal Pegawai', route('peg_jadwal.list'));
});

// Home > Daftar Jadwal Pegawai / Form jadwal Pegwai
Breadcrumbs::register('peg_jadwal.form', function($breadcrumbs)
{
    $breadcrumbs->parent('peg_jadwal.list');
    $breadcrumbs->push('Form Jadwal Pegawai', route('peg_jadwal.create'));
});

// Home > Daftar Jadwal Pegawai / Detail Jadwal Pegawai
Breadcrumbs::register('peg_jadwal.detail', function($breadcrumbs)
{
    $breadcrumbs->parent('peg_jadwal.list');
    $breadcrumbs->push('Rincian Jadwal Pegawai', route('peg_jadwal.create'));
});

// Home > Daftar Ijin List
Breadcrumbs::register('ref_ijin.list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Keterangan Tidak Hadir', route('ref_ijin.list'));
});

// Home > Form Ijin
Breadcrumbs::register('ref_ijin.form', function($breadcrumbs)
{
    $breadcrumbs->parent('ref_ijin.list');
    $breadcrumbs->push('Form Data Referensi Keterangan Tidak Hadir', route('ref_ijin.form'));
});

// Home > Daftar Ketidakhadiran
Breadcrumbs::register('ketidakhadiran.list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Ketidakhadiran Pegawai', route('ketidakhadiran.list'));
});

// Home > Daftar Ketidakhadiran / Ketidakhadiran Form
Breadcrumbs::register('ketidakhadiran.form', function($breadcrumbs)
{
    $breadcrumbs->parent('ketidakhadiran.list');
    $breadcrumbs->push('Form Ketidakhadiran Pegawai', route('ketidakhadiran.create'));
});

// Home > Sinkronisasi
Breadcrumbs::register('sinkronisasi', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Sinkronisasi Data', route('sinkronisasi.index'));
});

// Home > Riwayat Absensi
Breadcrumbs::register('riwayat', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Riwayat Finger', route('riwayat.index'));
});

// Home > Auth Log
Breadcrumbs::register('authlog.list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Auth Log', route('authlog.list'));
});

// Home > Laporan Kehadiran Bulanan
Breadcrumbs::register('laporan.bulanan', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Cetak Laporan Kehadiran Bulanan', route('laporan.bulanan'));
});

// Home > Laporan Kehadiran Bulanan / View Laporan OPD
Breadcrumbs::register('laporan.bulanan.view', function($breadcrumbs)
{
    $breadcrumbs->parent('laporan.bulanan');
    $breadcrumbs->push('Cetak Laporan Kehadiran Bulanan', route('laporan.bulanan'));
});

// Home > Laporan Harian
Breadcrumbs::register('laporan.harian', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Cetak Laporan Kehadiran Harian', route('laporan.harian'));
});

// Home > Laporan Ketidakhadiran Pegawai
Breadcrumbs::register('laporan.ketidakhadiran', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Cetak Laporan Ketidakhadiran Pegawai', route('laporan.ketidakhadiran'));
});

// Home > Dispensasi
Breadcrumbs::register('dispensasi.list', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Daftar Dispensasi', route('dispensasi.list'));
});

// Home > Dispensasi > Form
Breadcrumbs::register('dispensasi.form', function($breadcrumbs)
{
    $breadcrumbs->parent('dispensasi.list');
    $breadcrumbs->push('Form Dispensasi', route('dispensasi.list'));
});

// Home > Referensi > Widget Pengumuman
Breadcrumbs::register('widget.pengumuman.index', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Widget Pengumuman', route('widget.pengumuman.index'));
});

// Home > Referensi 
Breadcrumbs::register('referensi', function($breadcrumbs)
{
    $breadcrumbs->push('Referensi', route('home'));
});

// Home > Referensi > Standarisasi Biaya TPP
Breadcrumbs::register('referensi.tpp', function($breadcrumbs)
{
    $breadcrumbs->parent('referensi');
    $breadcrumbs->push('Standarisasi Biaya TPP', route('tpp.kategori.index'));
});

// Home > Referensi > Form Standarisasi Biaya TPP
Breadcrumbs::register('referensi.tpp.create', function($breadcrumbs)
{
    $breadcrumbs->parent('referensi.tpp');
    $breadcrumbs->push('Form Kategori TPP', route('tpp.kategori.create'));
});

// Home > Referensi > [Kategori TPP] > Jenis Pengeluaran
Breadcrumbs::register('referensi.tpp.jenis_pengeluaran', function($breadcrumbs, $kategori)
{
    $breadcrumbs->parent('referensi.tpp');
    $breadcrumbs->push($kategori->nm_kategori, route('tpp.jenis_pengeluaran', $kategori->id));
});

// Home > Referensi > [Kategori TPP] > Jenis Pengeluaran
Breadcrumbs::register('referensi.tpp.jenis_pengeluaran.create', function($breadcrumbs, $kategori)
{
    $breadcrumbs->parent('referensi.tpp.jenis_pengeluaran',$kategori);
    $breadcrumbs->push("Form Jenis Pengeluaran", route('tpp.jenis_pengeluaran', $kategori->id));
});





?>

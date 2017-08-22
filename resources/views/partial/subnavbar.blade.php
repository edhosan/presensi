<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container">
      <ul class="mainnav">
        <li class="{{ active('home') }}"><a href="index.html"><i class="icon-dashboard"></i><span>Dashboard</span> </a> </li>
        <li class="dropdown {{ active(['home/*','register','user','role_list','permission_list','kalendar_list','jadwal_list','ref_ijin_list','authlog.list']) }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-list-alt"></i><span>Data Referensi</span> </a>
          <ul class="dropdown-menu">
            <li><a href="{{ route('user') }}">Manajemen User</a></li>
            @role(['super-admin'])
              <li><a href="{{ route('role_list') }}">Tipe User</a></li>
              <li><a href="{{ route('permission_list') }}">Hak Akses</a></li>
              <li><a href="{{ route('authlog.list') }}">Auth Log</a></li>
            @endrole
            <li><a href="{{ route('kalendar_list') }}">Kalendar Kerja</a></li>
            <li><a href="{{ route('jadwal_list') }}">Jadwal Kerja</a></li>
            <li><a href="{{ route('ref_ijin.list') }}">Keterangan Tidak Hadir</a></li>
          </ul>
        </li>
        <li class="dropdown {{ active(['datainduk_list','peg_jadwal.list','ketidakhadiran.list','kalkulasi.form']) }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-book"></i><span>Data Proses</span> </a>
          <ul class="dropdown-menu">
            @role(['super-admin','admin'])
              <li><a href="{{ route('datainduk_list') }}">Data Induk Pegawai</a></li>
              <li><a href="{{ route('peg_jadwal.list') }}">Jadwal Kerja Pegawai</a></li>
              <li><a href="{{ route('ketidakhadiran.list') }}">Ketidakhadiran Pegawai</a></li>
              <li><a href="{{ route('kalkulasi.form') }}">Kalkulasi Kehadiran</a></li>
            @endrole
          </ul>
        </li>
        <li class="dropdown {{ active(['laporan.bulanan']) }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-bar-chart"></i><span>Laporan</span> </a>
          <ul class="dropdown-menu">
            @role(['super-admin','admin'])
              <li><a href="{{ route('laporan.bulanan') }}">Laporan Kehadiran Bulanan</a></li>
              <li><a href="{{ route('laporan.harian') }}">Laporan Kehadiran Harian</a></li>
            @endrole
          </ul>
        </li>
        <li><a href="shortcodes.html"><i class="icon-code"></i><span>Shortcodes</span> </a> </li>
        <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-long-arrow-down"></i><span>Drops</span> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="icons.html">Icons</a></li>
            <li><a href="faq.html">FAQ</a></li>
            <li><a href="pricing.html">Pricing Plans</a></li>
            <li><a href="login.html">Login</a></li>
            <li><a href="signup.html">Signup</a></li>
            <li><a href="error.html">404</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!-- /container -->
  </div>
  <!-- /subnavbar-inner -->
</div>
<!-- /subnavbar -->

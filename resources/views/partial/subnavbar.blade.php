<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container">
      <ul class="mainnav">
        <li class="{{ active('home') }}"><a href="index.html"><i class="icon-dashboard"></i><span>Dashboard</span> </a> </li>
        <li class="dropdown {{ active(['user','role_list','permission_list','authlog.list']) }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-briefcase"></i><span>Administrator</span> </a>
          <ul class="dropdown-menu">
              <li><a href="{{ route('user') }}">Manajemen User</a></li>
              @role(['super-admin'])
                <li><a href="{{ route('role_list') }}">Tipe User</a></li>
                <li><a href="{{ route('permission_list') }}">Hak Akses</a></li>
                <li><a href="{{ route('authlog.list') }}">Auth Log</a></li>
              @endrole
          </ul>
        </li>
        @role(['super-admin'])
          <li class="dropdown {{ active(['home/*','register','kalendar_list','jadwal_list','ref_ijin_list']) }}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-list-alt"></i><span>Data Referensi</span> </a>
            <ul class="dropdown-menu">
              <li><a href="{{ route('kalendar_list') }}">Kalendar Kerja</a></li>
              <li><a href="{{ route('jadwal_list') }}">Jadwal Kerja</a></li>
              <li><a href="{{ route('ref_ijin.list') }}">Keterangan Tidak Hadir</a></li>
            </ul>
          </li>
        @endrole

        <li class="dropdown {{ active(['datainduk_list','peg_jadwal.list']) }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-group"></i><span>Pegawai</span> </a>
          <ul class="dropdown-menu">
            @role(['super-admin','admin'])
              <li><a href="{{ route('datainduk_list') }}">Data Pegawai</a></li>
              <li><a href="{{ route('peg_jadwal.list') }}">Jadwal Kerja Pegawai</a></li>
            @endrole
          </ul>
        </li>

        <li class="dropdown {{ active(['ketidakhadiran.list','dispensasi.list']) }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-envelope"></i><span>Ijin</span> </a>
          <ul class="dropdown-menu">
            @role(['super-admin','admin'])
              <li><a href="{{ route('ketidakhadiran.list') }}">Berhalangan Hadir</a></li>
              <li><a href="{{ route('dispensasi.list') }}">Dispensasi</a></li>
            @endrole
          </ul>
        </li>

        <li class="{{ active('kalkulasi.form') }}"><a href="{{ route('kalkulasi.form') }}"><i class="icon-tasks"></i><span>Perhitungan Kehadiran</span> </a> </li>

        <li class="dropdown {{ active(['laporan.bulanan','laporan.harian','laporan.ketidakhadiran']) }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-bar-chart"></i><span>Laporan</span> </a>
          <ul class="dropdown-menu">
            @role(['super-admin','admin'])
              <li><a href="{{ route('laporan.bulanan') }}">Laporan Kehadiran Bulanan</a></li>
              <li><a href="{{ route('laporan.harian') }}">Laporan Kehadiran Harian</a></li>
              <li><a href="{{ route('laporan.ketidakhadiran') }}">Laporan Ketidakhadiran Pegawai</a></li>
            @endrole
          </ul>
        </li>

        <li class="{{ active('logout') }}"><a href="{{ route('logout') }}"><i class="icon-signout "></i><span>Logout</span> </a> </li>
      </ul>
    </div>
    <!-- /container -->
  </div>
  <!-- /subnavbar-inner -->
</div>
<!-- /subnavbar -->

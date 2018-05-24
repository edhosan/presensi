<table class="table table-condensed table-bordered" width="100%" >
      <thead>
          <tr>
              <th rowspan="2" style="vertical-align: middle;text-align: center;">HARI</th>
              <th colspan="2" style="text-align: center;">JAM KERJA</th>
              <th colspan="2" style="text-align: center;">TOLERANSI</th>
              <th colspan="4" style="text-align: center;">BATAS WAKTU ABSENSI</th>
          </tr>
          <tr>
            <th style="text-align: center;">MASUK</th>
            <th style="text-align: center;">PULANG</th>
            <th style="text-align: center;">TERLAMBAT</th>
            <th style="text-align: center;">PULANG AWAL</th>
            <th style="text-align: center;">MASUK</th>
            <th style="text-align: center;">SIANG I</th>
            <th style="text-align: center;">SIANG II</th>
            <th style="text-align: center;">PULANG</th>
          </tr>
      </thead>
      <tbody>
        @foreach($hari as $item)
        <tr>
          <td>{{ $item['hari'] }}</td>
          <td>{{ $item['jam_masuk'] }}</td>
          <td>{{ $item['jam_pulang'] }}</td>
          <td>{{ $item['toleransi_terlambat'] }}</td>
          <td>{{ $item['toleransi_pulang'] }}</td>
          <td>{{ $item['absensi_masuk'] }}</td>
          <td>{{ $item['absensi_siang_1'] }}</td>
          <td>{{ $item['absensi_siang_2'] }}</td>
          <td>{{ $item['absensi_pulang'] }}</td>
        </tr>
        @endforeach
      </tbody>

</table>              
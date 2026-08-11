<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
  body {
    font-family: 'Courier New', 'Space Mono', monospace;
    background-color: #FFFFFF;
    color: #1C1C1C;
  }
  table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 20px;
  }
  th {
    background-color: #9A4A2E !important;
    color: #FFFFFF !important;
    border: 2px solid #1C1C1C !important;
    padding: 10px 14px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    text-align: left;
  }
  td {
    border: 1.5px solid #1C1C1C !important;
    padding: 8px 12px;
    font-size: 11px;
    font-weight: bold;
    color: #1C1C1C;
  }
  .title-banner {
    background-color: #1C1C1C !important;
    color: #EAE6E0 !important;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    padding: 14px;
    text-align: center;
  }
  .meta-label {
    background-color: #D8D3CA !important;
    font-weight: bold;
    text-transform: uppercase;
    width: 180px;
  }
  .meta-val {
    background-color: #EAE6E0 !important;
    font-weight: bold;
  }
  .row-even {
    background-color: #F5F2EC !important;
  }
  .row-odd {
    background-color: #FFFFFF !important;
  }
</style>
</head>
<body>

  <table>
    <tr>
      <td colspan="5" class="title-banner">
        NAOOLIFT — PROGRAM JADWAL LATIHAN ({{ $monthLabel }})
      </td>
    </tr>
    <tr>
      <td class="meta-label">PENGGUNA / USER:</td>
      <td colspan="4" class="meta-val">{{ $userName }}</td>
    </tr>
    <tr>
      <td class="meta-label">TANGGAL EXPORT:</td>
      <td colspan="4" class="meta-val">{{ date('d/m/Y H:i:s') }}</td>
    </tr>
    <tr>
      <td class="meta-label">PROGRAM BULAN:</td>
      <td colspan="4" class="meta-val" style="color: #9A4A2E;">{{ $monthLabel }}</td>
    </tr>
  </table>

  <table>
    <thead>
      <tr>
        <th style="width: 50px; text-align: center;">NO</th>
        <th style="width: 120px;">HARI</th>
        <th style="width: 250px;">NAMA ROUTINE / SESI LATIHAN</th>
        <th style="width: 250px;">TARGET OTOT / FOKUS</th>
        <th style="width: 120px; text-align: center;">STATUS</th>
      </tr>
    </thead>
    <tbody>
      @forelse($schedules as $index => $sched)
        <tr class="{{ $index % 2 == 0 ? 'row-even' : 'row-odd' }}">
          <td style="text-align: center;">0{{ $index + 1 }}</td>
          <td>{{ $sched->day_name }}</td>
          <td style="font-weight: 900; color: #1C1C1C;">{{ $sched->title }}</td>
          <td>{{ $sched->focus_target ?? '-' }}</td>
          <td style="text-align: center; color: {{ $sched->is_rest ? '#535366' : '#9A4A2E' }};">
            {{ $sched->is_rest ? 'REST DAY' : 'WORKOUT' }}
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" style="text-align: center; padding: 20px; color: #535366;">
            BELUM ADA JADWAL PROGRAM DITAMBAHKAN PADA BULAN INI.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

</body>
</html>

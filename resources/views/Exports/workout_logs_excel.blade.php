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
    background-color: #9A4A2E !important; /* Ember Accent */
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
  .total-row {
    background-color: #1C1C1C !important;
    color: #9A4A2E !important;
    font-size: 13px;
    font-weight: bold;
  }
  .num-cell {
    text-align: right;
  }
</style>
</head>
<body>

  <!-- HEADER BRAND BANNER -->
  <table>
    <tr>
      <td colspan="7" class="title-banner">
        NAOOLIFT — {{ $titleLabel }}
      </td>
    </tr>
    <tr>
      <td class="meta-label">PENGGUNA / USER:</td>
      <td colspan="6" class="meta-val">{{ $userName }}</td>
    </tr>
    <tr>
      <td class="meta-label">TANGGAL EXPORT:</td>
      <td colspan="6" class="meta-val">{{ date('d/m/Y H:i:s') }}</td>
    </tr>
    <tr>
      <td class="meta-label">TOTAL GERAKAN:</td>
      <td colspan="6" class="meta-val">{{ $logs->count() }} LATIHAN DICATAT</td>
    </tr>
    <tr>
      <td class="meta-label">TOTAL VOLUMETRIK:</td>
      <td colspan="6" class="meta-val" style="color: #9A4A2E;">{{ number_format($totalVolume) }} KG</td>
    </tr>
  </table>

  <!-- MAIN DATA TABLE -->
  <table>
    <thead>
      <tr>
        <th style="width: 40px; text-align: center;">NO</th>
        <th style="width: 110px;">TANGGAL</th>
        <th style="width: 180px;">NAMA ROUTINE</th>
        <th style="width: 220px;">GERAKAN LATIHAN</th>
        <th style="width: 60px; text-align: center;">SET</th>
        <th style="width: 60px; text-align: center;">REPS</th>
        <th style="width: 90px; text-align: right;">BEBAN (KG)</th>
        <th style="width: 130px; text-align: right;">VOLUMETRIK (KG)</th>
        <th style="width: 240px;">CATATAN PROGRES</th>
      </tr>
    </thead>
    <tbody>
      @forelse($logs as $index => $log)
        <tr class="{{ $index % 2 == 0 ? 'row-even' : 'row-odd' }}">
          <td style="text-align: center;">0{{ $index + 1 }}</td>
          <td>{{ date('d/m/Y', strtotime($log->log_date)) }}</td>
          <td>{{ $log->routine_title }}</td>
          <td style="font-weight: 900; color: #1C1C1C;">{{ $log->exercise_name }}</td>
          <td style="text-align: center;">{{ $log->sets }}</td>
          <td style="text-align: center;">{{ $log->reps }}</td>
          <td class="num-cell">{{ number_format($log->weight_kg, 1) }} KG</td>
          <td class="num-cell" style="color: #9A4A2E;">{{ number_format($log->sets * $log->reps * $log->weight_kg) }} KG</td>
          <td>{{ $log->notes ?? '-' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="9" style="text-align: center; padding: 20px; color: #535366;">
            BELUM ADA DATA CATATAN LATIHAN DICATAT.
          </td>
        </tr>
      @endforelse
    </tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="6" style="text-align: right; background-color: #1C1C1C; color: #EAE6E0;">
          AKUMULASI TOTAL VOLUMETRIK LATIHAN:
        </td>
        <td colspan="3" style="background-color: #1C1C1C; color: #9A4A2E; text-align: right; font-size: 14px;">
          {{ number_format($totalVolume) }} KG
        </td>
      </tr>
    </tfoot>
  </table>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Iuran Bulanan</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        h2 {
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .summary-left {
            width: auto;
            border-collapse: collapse;
        }

        .summary-left td {
            border: none;
            padding: 2px 0;
        }

        .summary-left td:nth-child(1) {
            width: 110px;
            white-space: nowrap;
        }

        .summary-left td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .summary-left td:nth-child(3) {
            white-space: nowrap;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th {
            background: #e5e7eb;
            font-weight: bold;
            border: 1px solid #9ca3af;
            padding: 7px;
        }

        .report-table td {
            border: 1px solid #9ca3af;
            padding: 7px;
        }

        .report-table tfoot td {
            font-weight: bold;
            background: #f3f4f6;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>
            Laporan Iuran Bulanan
            @if($classroom)
                Kelas {{ $classroom->name }}
            @else
                Semua Kelas
            @endif
        </h2>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <table class="summary-left">
                    <tr>
                        <td>Periode</td>
                        <td>:</td>
                        <td>
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
                        </td>
                    </tr>

                    <tr>
                        <td>Tanggal Cetak</td>
                        <td>:</td>
                        <td>
                            {{ now()->translatedFormat('d F Y') }}
                        </td>
                    </tr>

                    <tr>
                        <td>Total Siswa</td>
                        <td>:</td>
                        <td>
                            {{ $studentsSummary->count() }} siswa
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="26%">Nama Siswa</th>
                <th width="26%">Orang Tua</th>
                <th width="6%">Kelas</th>
                <th width="12%">Total Tagihan</th>
                <th width="12%">Total Dibayar</th>
                <th width="12%">Tunggakan</th>
                <th width="11%">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($studentsSummary as $data)
                <tr>
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $data['student']->name }}
                    </td>

                    <td>
                        {{ $data['student']->parent->name ?? '-' }}
                    </td>

                    <td>
                        {{ $data['student']->classroom->name ?? '-' }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($data['total_tagihan'], 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($data['total_dibayar'], 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($data['sisa_tagihan'], 0, ',', '.') }}
                    </td>

                    <td>
                        {{ $data['status'] }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">
                        Tidak ada data iuran.
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" class="text-right">
                    Total Keseluruhan
                </td>

                <td class="text-right">
                    Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                </td>

                <td class="text-right">
                    Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                </td>

                <td class="text-right">
                    Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                </td>

                <td></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
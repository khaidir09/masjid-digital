<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Masjid</title>
    <style>
        body { font-family: sans-serif; color: #334155; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; }
        .header h1 { margin: 0; text-transform: uppercase; color: #0f172a; font-size: 24px; }
        .header p { margin: 5px 0; font-size: 12px; color: #64748b; }

        /* Summary Box */
        .summary-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .summary-box { padding: 15px; border-radius: 10px; color: #fff; width: 32%; text-align: center; }
        .bg-emerald { background-color: #10b981; }
        .bg-rose { background-color: #f43f5e; }
        .bg-slate { background-color: #0f172a; }
        .summary-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; opacity: 0.8; }
        .summary-val { font-size: 18px; font-weight: bold; }

        /* Main Table */
        table.data { width: 100%; border-collapse: collapse; font-size: 12px; }
        table.data th { background-color: #f8fafc; padding: 12px; text-align: left; text-transform: uppercase; color: #94a3b8; font-size: 10px; letter-spacing: 1px; border-bottom: 1px solid #e2e8f0; }
        table.data td { padding: 12px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }

        /* Badges */
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 9px; text-transform: uppercase; font-weight: bold; }
        .badge-in { background-color: #d1fae5; color: #059669; } /* Emerald-100/600 */
        .badge-out { background-color: #ffe4e6; color: #e11d48; } /* Rose-100/600 */

        .text-emerald { color: #059669; font-weight: bold; }
        .text-rose { color: #e11d48; font-weight: bold; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }

        .footer { margin-top: 50px; text-align: right; font-size: 12px; }
        .ttd-box { display: inline-block; text-align: center; width: 200px; margin-top: 20px; }
        .ttd-line { border-bottom: 1px solid #334155; margin-top: 60px; }
    </style>
</head>
<body>
    @php
        $setting = \App\Models\AppSetting::first();
        $bulanNama = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');
    @endphp

    <div class="header">
        <h1>{{ $setting->nama_masjid ?? 'MASJID DIGITAL' }}</h1>
        <p>{{ $setting->alamat ?? 'Alamat Masjid Belum Diisi' }}</p>
        <p style="margin-top: 10px; font-weight: bold; color: #0f172a;">
            LAPORAN KEUANGAN PERIODE: {{ strtoupper($bulanNama) }} {{ $tahun }}
        </p>
    </div>

    <table class="summary-table">
        <tr>
            <td class="summary-box bg-emerald">
                <div class="summary-label">Pemasukan</div>
                <div class="summary-val">+ Rp {{ number_format($pemasukan, 0, ',', '.') }}</div>
            </td>
            <td style="width: 2%"></td>
            <td class="summary-box bg-rose">
                <div class="summary-label">Pengeluaran</div>
                <div class="summary-val">- Rp {{ number_format($pengeluaran, 0, ',', '.') }}</div>
            </td>
            <td style="width: 2%"></td>
            <td class="summary-box bg-slate">
                <div class="summary-label">Saldo Akhir</div>
                <div class="summary-val">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 35%">Keterangan</th>
                <th style="width: 15%">Kategori</th>
                <th style="width: 20%" class="text-right">Nominal</th>
                <th style="width: 15%">Admin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            <tr>
                <td>
                    <b>{{ $item->tanggal->format('d/m/Y') }}</b><br>
                    <span style="color: #94a3b8; font-size: 10px;">{{ $item->created_at->format('H:i') }}</span>
                </td>
                <td>
                    <b>{{ $item->sumber_atau_tujuan }}</b><br>
                    <span style="color: #64748b; font-size: 10px;">{{ $item->keterangan }}</span>
                </td>
                <td>
                    <span class="badge {{ $item->kategori == 'pemasukan' ? 'badge-in' : 'badge-out' }}">
                        {{ $item->kategori }}
                    </span>
                </td>
                <td class="text-right font-mono {{ $item->kategori == 'pemasukan' ? 'text-emerald' : 'text-rose' }}">
                    {{ $item->kategori == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                </td>
                <td style="font-size: 10px; color: #64748b;">
                    {{ $item->user->name ?? 'System' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px; color: #94a3b8;">
                    TIDAK ADA DATA TRANSAKSI
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y') }}</p>

        <div class="ttd-box">
            <p>Mengetahui,<br>Ketua / Bendahara</p>
            <div class="ttd-line"></div>
        </div>
    </div>
</body>
</html>

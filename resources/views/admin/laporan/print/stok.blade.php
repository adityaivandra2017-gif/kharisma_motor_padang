@php
    use App\Models\Onderdil;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak — Laporan Persediaan Stok</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 12mm;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.45;
            margin: 0;
            padding: 0;
            background: #f1f5f9;
        }

        .print-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
        }

        .print-toolbar p {
            margin: 0;
            font-size: 13px;
            color: #64748b;
        }

        .print-toolbar__actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #9f1239;
            color: #fff;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .print-page {
            max-width: 210mm;
            margin: 0 auto;
            padding: 16px 12px 24px;
            background: #fff;
        }

        .header {
            margin-bottom: 18px;
            font-family: 'Times New Roman', Times, serif;
            color: #0f172a;
        }

        .header__layout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 22px;
            padding: 0 4mm;
        }

        .header__logo {
            flex: 0 0 150px;
            width: 150px;
        }

        .header__logo img {
            display: block;
            width: 100%;
            height: auto;
            max-height: 150px;
            object-fit: contain;
            object-position: center;
        }

        .header__body {
            flex: 0 1 auto;
            max-width: calc(100% - 172px);
            text-align: center;
        }

        .header__brand {
            margin: 0 0 4px;
            font-size: 15pt;
            font-weight: 700;
            letter-spacing: 0.06em;
            line-height: 1.2;
            text-transform: uppercase;
            color: #0f172a;
        }

        .header__address {
            margin: 0;
            font-size: 9pt;
            line-height: 1.35;
            font-weight: 400;
        }

        .header__social-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
            max-width: 22rem;
            margin: 4px auto 0;
            font-size: 8.5pt;
            line-height: 1.4;
        }

        .header__social-item {
            white-space: nowrap;
        }

        .header__social-item a {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 500;
        }

        .header__rule {
            margin-top: 10px;
            border-top: 3px solid #0f172a;
            padding-top: 2px;
            border-bottom: 1px solid #0f172a;
        }

        .report-title {
            margin: 0 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            font-size: 12pt;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #881337;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8.5pt;
        }

        table.data th,
        table.data td {
            border: 1px solid #cbd5e1;
            padding: 5px 4px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }

        table.data th {
            background: #fff1f2;
            color: #881337;
            font-size: 8pt;
            text-transform: uppercase;
        }

        table.data td.text-left { text-align: left; }

        table.data tbody tr:nth-child(even) td { background: #f8fafc; }

        .status-aman { color: #047857; font-weight: bold; }
        .status-menipis { color: #b45309; font-weight: bold; }
        .status-habis { color: #b91c1c; font-weight: bold; }

        .ringkasan {
            margin-top: 14px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            page-break-inside: avoid;
        }

        .ringkasan h4 {
            margin: 0 0 6px;
            font-size: 10pt;
            color: #881337;
            text-transform: uppercase;
        }

        .ringkasan table { width: 100%; border-collapse: collapse; }
        .ringkasan td { padding: 3px 0; font-size: 9.5pt; }
        .ringkasan td:first-child { font-weight: 600; color: #475569; width: 50%; }

        .signature {
            margin-top: 36px;
            padding-top: 8px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }

        .signature__inner {
            display: inline-block;
            text-align: left;
        }

        .signature__place-date {
            margin: 0 0 6px;
            font-size: 10.5pt;
        }

        .signature__signatory {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
        }

        .signature__role {
            margin: 0;
            font-size: 10.5pt;
            align-self: flex-start;
        }

        .signature__space {
            height: 64px;
            width: 100%;
        }

        .signature__name {
            margin: 0;
            font-size: 11pt;
            font-weight: 700;
            text-align: center;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        @media print {
            body { background: #fff; }

            .no-print { display: none !important; }

            .print-page {
                max-width: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-toolbar no-print">
        <p>Pratinjau cetak A4 portrait — gunakan dialog cetak browser untuk menyimpan PDF.</p>
        <div class="print-toolbar__actions">
            <button type="button" class="btn btn-primary" onclick="window.print()">Cetak / Simpan PDF</button>
            <button type="button" class="btn btn-secondary" onclick="window.close()">Tutup</button>
        </div>
    </div>

    <div class="print-page">
        <header class="header">
            <div class="header__layout">
                <div class="header__logo">
                    <img
                        src="{{ asset('images/logo kharisma motor.png') }}"
                        alt="Logo Kharisma Motor"
                    >
                </div>
                <div class="header__body">
                    <h1 class="header__brand">Kharisma Motor</h1>
                    <p class="header__address">
                        Jalan Gajah Mada, Kelurahan Kampung Olo, Kecamatan Nanggalo,<br>
                        Kota Padang, Sumatera Barat.
                    </p>
                    <div class="header__social-row">
                        <span class="header__social-item">
                            Instagram :
                            <a href="https://www.instagram.com/kharisma.motor_pdg">@kharisma.motor_pdg</a>
                        </span>
                        <span class="header__social-item">
                            TikTok :
                            <a href="https://www.tiktok.com/@kharisma_motor_padang">@kharisma_motor_padang</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="header__rule" aria-hidden="true"></div>
        </header>

        <h2 class="report-title">Laporan Persediaan Stok</h2>

        <table class="data">
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:11%;">Kode</th>
                    <th style="width:18%;">Nama</th>
                    <th style="width:11%;">Jenis</th>
                    <th style="width:16%;">Supplier</th>
                    <th style="width:14%;">Harga</th>
                    <th style="width:9%;">Stok</th>
                    <th style="width:10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($onderdils as $index => $onderdil)
                    @php
                        $statusClass = match ($onderdil->status_stok) {
                            Onderdil::STATUS_HABIS => 'status-habis',
                            Onderdil::STATUS_MENIPIS => 'status-menipis',
                            default => 'status-aman',
                        };
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $onderdil->kode_onderdil }}</td>
                        <td class="text-left">{{ $onderdil->nama_onderdil }}</td>
                        <td>{{ $onderdil->jenis }}</td>
                        <td class="text-left">{{ $onderdil->supplier?->nama_supplier ?? '-' }}</td>
                        <td>Rp {{ number_format($onderdil->harga, 0, ',', '.') }}</td>
                        <td>{{ number_format($onderdil->stok, 0, ',', '.') }}</td>
                        <td class="{{ $statusClass }}">{{ $onderdil->status_stok_label }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="ringkasan">
            <h4>Ringkasan</h4>
            <table>
                <tr>
                    <td>Total Onderdil</td>
                    <td>{{ number_format($ringkasan['total_onderdil'], 0, ',', '.') }} item</td>
                </tr>
                <tr>
                    <td>Total Stok Aman</td>
                    <td>{{ number_format($ringkasan['total_aman'], 0, ',', '.') }} item</td>
                </tr>
                <tr>
                    <td>Total Stok Menipis</td>
                    <td>{{ number_format($ringkasan['total_menipis'], 0, ',', '.') }} item</td>
                </tr>
                <tr>
                    <td>Total Stok Habis</td>
                    <td>{{ number_format($ringkasan['total_habis'], 0, ',', '.') }} item</td>
                </tr>
            </table>
        </div>

        <div class="signature">
            <div class="signature__inner">
                <p class="signature__place-date" id="signatureDate">{{ $kotaLaporan }}, …</p>
                <div class="signature__signatory">
                    <p class="signature__role">{{ $jabatanPenandatangan }},</p>
                    <div class="signature__space" aria-hidden="true"></div>
                    <p class="signature__name">{{ $dicetakOleh }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const BULAN_INDONESIA = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        function formatTanggalIndonesia(date) {
            return `${date.getDate()} ${BULAN_INDONESIA[date.getMonth()]} ${date.getFullYear()}`;
        }

        function updateSignatureDate() {
            const element = document.getElementById('signatureDate');
            if (!element) {
                return;
            }

            const now = new Date();
            element.textContent = '{{ $kotaLaporan }}, ' + formatTanggalIndonesia(now);
        }

        window.addEventListener('load', function () {
            updateSignatureDate();

            setTimeout(function () {
                window.print();
            }, 400);
        });

        window.addEventListener('beforeprint', updateSignatureDate);
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Reservasi FutsalHub - {{ $startDate }} s/d {{ $endDate }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0 !important;
                background: white !important;
                width: 100% !important;
            }
            .print-container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .signature-wrapper {
                page-break-inside: avoid !important;
            }
            table {
                width: 100% !important;
                table-layout: auto !important;
            }
            th, td {
                padding-left: 6px !important;
                padding-right: 6px !important;
                word-break: normal !important;
            }
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 text-xs">

    <!-- Top Action Bar (hidden when printing) -->
    <div class="no-print max-w-6xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-emerald-600">
            &larr; Kembali ke Panel Laporan
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer">
                Cetak / Simpan PDF Sekarang
            </button>
        </div>
    </div>

    <!-- Printable Document Sheet -->
    <div class="print-container max-w-6xl mx-auto bg-white p-8 sm:p-10 rounded-3xl shadow-lg border border-slate-200">
        <!-- Letterhead Kop Surat -->
        <div class="flex items-center justify-between pb-5 border-b-2 border-slate-900 gap-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo FutsalHub" class="w-14 h-14 object-contain shrink-0">
                <div>
                    <h1 class="text-xl font-black tracking-tight text-slate-900 uppercase">FUTSALHUB INDOOR ARENA</h1>
                    <p class="text-xs text-slate-600 font-medium">Pusat Reservasi Lapangan Futsal Standar Internasional</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Jl. Pemuda Futsal Arena No. 10 (Arena Indoor Blok A) • Telp: (021) 7890-1234 • Email: info@futsalhub.com</p>
                </div>
            </div>
            <div class="text-right shrink-0">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Sistem Informasi Reservasi</span>
                <span class="text-xs font-black text-slate-900">FutsalHub Arena</span>
            </div>
        </div>

        <!-- Document Title & Meta -->
        <div class="py-5 text-center">
            <h2 class="text-base font-black uppercase tracking-wider text-slate-900">
                LAPORAN REKAPITULASI PENYEWAAN LAPANGAN
            </h2>
            <p class="text-xs text-slate-600 mt-1">
                Periode: <strong class="text-slate-900">{{ date('d F Y', strtotime($startDate)) }}</strong> s/d <strong class="text-slate-900">{{ date('d F Y', strtotime($endDate)) }}</strong>
                @if($selectedField)
                    | Lapangan: <strong class="text-slate-900">{{ $selectedField->name }}</strong>
                @endif
            </p>
        </div>

        <!-- Meta Summary Table -->
        <div class="grid grid-cols-3 gap-4 mb-5 bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-[11px]">
            <div>
                <span class="text-slate-500 block">Total Transaksi:</span>
                <strong class="text-xs sm:text-sm font-bold text-slate-900">{{ $totalTransactions }} Pesanan</strong>
            </div>
            <div>
                <span class="text-slate-500 block">Total Pendapatan Valid:</span>
                <strong class="text-xs sm:text-sm font-black text-emerald-700">Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
            </div>
            <div>
                <span class="text-slate-500 block">Tanggal Dicetak:</span>
                <strong class="text-slate-900 font-bold">
                    {{ now()->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }} WIB
                </strong>
            </div>
        </div>

        <!-- Data Table (100% width with compact text for printing) -->
        <div class="border border-slate-300 rounded-xl overflow-hidden mb-6 w-full">
            <table class="w-full text-left border-collapse" style="font-size: 10px; line-height: 1.35;">
                <thead class="bg-slate-100 font-bold text-slate-700 border-b border-slate-300 uppercase" style="font-size: 9.5px;">
                    <tr>
                        <th class="px-2.5 py-2 text-center w-8">No</th>
                        <th class="px-2.5 py-2 whitespace-nowrap">Kode</th>
                        <th class="px-2.5 py-2">Customer</th>
                        <th class="px-2.5 py-2">Lapangan</th>
                        <th class="px-2.5 py-2 whitespace-nowrap">Tgl Sewa</th>
                        <th class="px-2.5 py-2">Jam Main</th>
                        <th class="px-2.5 py-2 text-center whitespace-nowrap">Metode</th>
                        <th class="px-2.5 py-2 text-center whitespace-nowrap">Status</th>
                        <th class="px-2.5 py-2 text-right whitespace-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($bookings as $idx => $item)
                        <tr>
                            <td class="px-2.5 py-2 text-center text-slate-500">{{ $idx + 1 }}</td>
                            <td class="px-2.5 py-2 font-mono font-bold text-slate-900 whitespace-nowrap">{{ $item->booking_code }}</td>
                            <td class="px-2.5 py-2 font-semibold text-slate-800">{{ $item->customer_name }}</td>
                            <td class="px-2.5 py-2 text-slate-700">{{ $item->field->name }}</td>
                            <td class="px-2.5 py-2 text-slate-800 whitespace-nowrap">{{ $item->booking_date->format('d/m/Y') }}</td>
                            <td class="px-2.5 py-2 text-slate-600">
                                @foreach($item->bookingDetails as $d)
                                    <span class="inline-block whitespace-nowrap">{{ substr($d->timeSlot->start_time, 0, 5) }}</span>
                                @endforeach
                            </td>
                            <td class="px-2.5 py-2 text-center uppercase font-bold text-slate-600 whitespace-nowrap">{{ $item->payment?->payment_method ?? '-' }}</td>
                            <td class="px-2.5 py-2 text-center whitespace-nowrap">
                                <span class="capitalize font-bold {{ $item->status === 'completed' || $item->status === 'confirmed' ? 'text-emerald-700' : 'text-slate-600' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-2.5 py-2 text-right font-bold text-slate-900 whitespace-nowrap">{{ $item->formatted_total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-slate-400 italic">
                                Tidak ada data pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-100 font-bold border-t border-slate-300">
                    <tr>
                        <td colspan="8" class="px-2.5 py-2.5 text-right uppercase text-[9.5px] text-slate-700">Total Pendapatan Terverifikasi:</td>
                        <td class="px-2.5 py-2.5 text-right text-xs font-black text-emerald-800 whitespace-nowrap">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Official Signatures Block (Neatly aligned and unbreakable across pages) -->
        <div class="signature-wrapper mt-8 pt-4 w-full">
            <table class="w-full text-center border-none" style="font-size: 11px;">
                <tr>
                    <td class="w-1/2 align-top text-center pb-20 text-slate-600">
                        Mengetahui,<br>
                        <strong class="text-slate-900">Manajer Operasional FutsalHub</strong>
                    </td>
                    <td class="w-1/2 align-top text-center pb-20 text-slate-600">
                        {{ date('d F Y') }}<br>
                        Dibuat oleh,<br>
                        <strong class="text-slate-900">Administrator Sistem</strong>
                    </td>
                </tr>
                <tr>
                    <td class="w-1/2 text-center">
                        <div class="w-52 mx-auto border-b border-slate-400"></div>
                        <p class="text-[11px] text-slate-700 font-bold mt-1.5">( Alvaro Raka, S.E. )</p>
                    </td>
                    <td class="w-1/2 text-center">
                        <div class="w-52 mx-auto border-b border-slate-400"></div>
                        <p class="text-[11px] text-slate-700 font-bold mt-1.5">( {{ auth()->user()->name ?? 'Petugas Kasir' }} )</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

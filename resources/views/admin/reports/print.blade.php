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
                padding: 0;
                background: white;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm 15mm 15mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-6 sm:p-10 text-xs">

    <!-- Top Action Bar (hidden when printing) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-emerald-600">
            &larr; Kembali ke Panel Laporan
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer">
            Cetak / Simpan PDF Sekarang
        </button>
    </div>

    <!-- Printable Document Sheet -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-lg border border-slate-200 print:border-none print:shadow-none print:p-0">
        <!-- Letterhead Kop Surat -->
        <div class="flex items-center justify-between pb-6 border-b-2 border-slate-900 gap-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo FutsalHub" class="w-16 h-16 object-contain">
                <div>
                    <h1 class="text-xl font-black tracking-tight text-slate-900 uppercase">FutsalHub</h1>
                    <p class="text-xs text-slate-600 font-medium">Pusat Reservasi Lapangan Futsal Standar Internasional</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Jl. Pemuda Olahraga No. 10 • Telp: (021) 7890-1234 • Email: info@futsalhub.com</p>
                </div>
            </div>
        </div>

        <!-- Document Title & Meta -->
        <div class="py-6 text-center">
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
        <div class="grid grid-cols-3 gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-200 text-[11px]">
            <div>
                <span class="text-slate-500 block">Total Transaksi:</span>
                <strong class="text-sm font-bold text-slate-900">{{ $totalTransactions }} Pesanan</strong>
            </div>
            <div>
                <span class="text-slate-500 block">Total Pendapatan Valid:</span>
                <strong class="text-sm font-black text-emerald-700">Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
            </div>
            <div>
                <span class="text-slate-500 block">Tanggal Dicetak:</span>
                <strong class="text-slate-900 font-bold">{{ date('d M Y, H:i') }} WIB</strong>
            </div>
        </div>

        <!-- Data Table -->
        <div class="border border-slate-300 rounded-xl overflow-hidden mb-8">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 font-bold text-slate-700 border-b border-slate-300 uppercase text-[10px]">
                    <tr>
                        <th class="px-3 py-2.5">No</th>
                        <th class="px-3 py-2.5">Kode</th>
                        <th class="px-3 py-2.5">Customer</th>
                        <th class="px-3 py-2.5">Lapangan</th>
                        <th class="px-3 py-2.5">Tgl Sewa</th>
                        <th class="px-3 py-2.5">Jam Main</th>
                        <th class="px-3 py-2.5">Metode</th>
                        <th class="px-3 py-2.5">Status</th>
                        <th class="px-3 py-2.5 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-[11px]">
                    @forelse($bookings as $idx => $item)
                        <tr>
                            <td class="px-3 py-2 text-slate-500">{{ $idx + 1 }}</td>
                            <td class="px-3 py-2 font-mono font-bold text-slate-900">{{ $item->booking_code }}</td>
                            <td class="px-3 py-2 font-semibold text-slate-800">{{ $item->user->name }}</td>
                            <td class="px-3 py-2 text-slate-700">{{ $item->field->name }}</td>
                            <td class="px-3 py-2 text-slate-800">{{ $item->booking_date->format('d/m/y') }}</td>
                            <td class="px-3 py-2 text-slate-600">
                                @foreach($item->bookingDetails as $d)
                                    {{ substr($d->timeSlot->start_time, 0, 5) }} 
                                @endforeach
                            </td>
                            <td class="px-3 py-2 uppercase font-bold text-slate-600">{{ $item->payment?->payment_method ?? '-' }}</td>
                            <td class="px-3 py-2">
                                <span class="capitalize font-bold {{ $item->status === 'completed' || $item->status === 'confirmed' ? 'text-emerald-700' : 'text-slate-600' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-right font-bold text-slate-900">{{ $item->formatted_total }}</td>
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
                        <td colspan="8" class="px-3 py-3 text-right uppercase text-[10px] text-slate-700">Total Pendapatan Terverifikasi:</td>
                        <td class="px-3 py-3 text-right text-xs font-black text-emerald-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Official Signatures Block -->
        <div class="mt-12 pt-6 grid grid-cols-2 gap-8 text-center text-xs">
            <div>
                <p class="text-slate-500 mb-16">Mengetahui,<br><strong class="text-slate-900">Manajer Operasional FutsalHub</strong></p>
                <div class="w-48 mx-auto border-b border-slate-400"></div>
                <p class="text-[11px] text-slate-500 mt-1">( Alvaro Raka, S.E. )</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">{{ date('d F Y') }}<br>Dibuat oleh,<br><strong class="text-slate-900">Administrator Sistem</strong></p>
                <div class="w-48 mx-auto border-b border-slate-400"></div>
                <p class="text-[11px] text-slate-500 mt-1">( {{ auth()->user()->name ?? 'Petugas Kasir' }} )</p>
            </div>
        </div>
    </div>
</body>
</html>


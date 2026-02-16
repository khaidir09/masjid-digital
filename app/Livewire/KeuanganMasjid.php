<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Keuangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.app')]
#[Title('Keuangan Masjid')]
class KeuanganMasjid extends Component
{
    use WithPagination, WithFileUploads;

    // --- FILTER & SEARCH ---
    public $bulan_filter;
    public $tahun_filter;
    public $search = '';

    // --- MODAL STATES ---
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;

    // !!! INI YANG TADI KETINGGALAN !!!
    public $showImageModal = false;
    public $selectedImageUrl;

    public $selectedId;

    // --- FORM INPUTS ---
    public $tanggal;
    public $kategori = 'pemasukan';
    public $sumber_atau_tujuan;
    public $nominal;
    public $keterangan;

    // --- FILE UPLOAD ---
    public $bukti;
    public $bukti_path;
    public $originalSize = 0;
    public $compressedSize = 0;

    public function mount()
    {
        $this->bulan_filter = (int)date('m');
        $this->tahun_filter = (int)date('Y');
        $this->tanggal = date('Y-m-d');
    }

    public function render()
    {
        $bulan = (int) $this->bulan_filter;
        $tahun = (int) $this->tahun_filter;

        // 1. Query Data Tabel
        $query = Keuangan::query()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where(function($q) {
                $q->where('sumber_atau_tujuan', 'like', '%'.$this->search.'%')
                  ->orWhere('keterangan', 'like', '%'.$this->search.'%');
            });

        $transaksi = $query->latest('tanggal')->paginate(10);

        // 2. Hitung Ringkasan (Saldo Akumulatif)
        // Hitung sampai akhir bulan yang dipilih
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $totalPemasukan = Keuangan::where('kategori', 'pemasukan')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $totalPengeluaran = Keuangan::where('kategori', 'pengeluaran')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Hitung Cashflow Bulan Ini Saja
        $pemasukanBulanIni = Keuangan::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaranBulanIni = Keuangan::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('kategori', 'pengeluaran')->sum('nominal');

        // 3. Siapkan Data Grafik
        $chartData = $this->prepareChartData($tahun, $bulan);

        // Kirim event update chart ke JS
        $this->dispatch('update-chart', data: $chartData);

        return view('livewire.keuangan-masjid', [
            'transaksi' => $transaksi,
            'saldoAkhir' => $saldoAkhir,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
        ]);
    }

    // Fungsi Query Grafik Database (Fix biar grafik muncul)
    public function prepareChartData($tahun, $bulan)
    {
        // Ambil data pemasukan harian
        $incomeDaily = Keuangan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('kategori', 'pemasukan')
            ->groupBy('tanggal')
            ->selectRaw('DATE(tanggal) as date, sum(nominal) as total')
            ->pluck('total', 'date')
            ->toArray();

        // Ambil data pengeluaran harian
        $expenseDaily = Keuangan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('kategori', 'pengeluaran')
            ->groupBy('tanggal')
            ->selectRaw('DATE(tanggal) as date, sum(nominal) as total')
            ->pluck('total', 'date')
            ->toArray();

        $daysInMonth = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateKey = Carbon::createFromDate($tahun, $bulan, $d)->format('Y-m-d');
            $labels[] = $d;
            $incomeData[] = $incomeDaily[$dateKey] ?? 0;
            $expenseData[] = $expenseDaily[$dateKey] ?? 0;
        }

        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData
        ];
    }

    // --- IMAGE MODAL LOGIC (FIX ERROR) ---
    public function showImage($url)
    {
        $this->selectedImageUrl = $url;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->selectedImageUrl = null;
    }

    // --- CRUD OPERATIONS ---

    public function create()
    {
        $this->resetInput();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function store()
    {
        // Sanitasi Nominal: Hapus titik sebelum validasi
        $this->nominal = (int) str_replace('.', '', $this->nominal);

        $this->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|in:pemasukan,pengeluaran',
            'sumber_atau_tujuan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'bukti' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($this->bukti) {
            $path = $this->bukti->store('bukti_keuangan', 'public');
        }

        Keuangan::create([
            'tanggal' => $this->tanggal,
            'kategori' => $this->kategori,
            'sumber_atau_tujuan' => $this->sumber_atau_tujuan,
            'nominal' => $this->nominal,
            'keterangan' => $this->keterangan,
            'bukti_path' => $path,
            'user_id' => Auth::id(),
        ]);

        $this->closeModal();
        session()->flash('message', 'Transaksi berhasil dicatat!');
    }

    public function edit($id)
    {
        $k = Keuangan::find($id);
        $this->selectedId = $id;
        $this->tanggal = $k->tanggal->format('Y-m-d');
        $this->kategori = $k->kategori;
        $this->sumber_atau_tujuan = $k->sumber_atau_tujuan;

        // FIX BUG NOMINAL EDIT: Kirim integer murni ke frontend
        $this->nominal = (int) $k->nominal;

        $this->keterangan = $k->keterangan;
        $this->bukti_path = $k->bukti_path;

        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        // Sanitasi Nominal: Hapus titik sebelum update
        $this->nominal = (int) str_replace('.', '', $this->nominal);

        $this->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required',
            'sumber_atau_tujuan' => 'required',
            'nominal' => 'required|numeric',
        ]);

        $k = Keuangan::find($this->selectedId);

        $path = $k->bukti_path;
        if ($this->bukti) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $this->bukti->store('bukti_keuangan', 'public');
        }

        $k->update([
            'tanggal' => $this->tanggal,
            'kategori' => $this->kategori,
            'sumber_atau_tujuan' => $this->sumber_atau_tujuan,
            'nominal' => $this->nominal,
            'keterangan' => $this->keterangan,
            'bukti_path' => $path,
        ]);

        $this->closeModal();
        session()->flash('message', 'Transaksi berhasil diperbarui!');
    }

    public function deleteId($id)
    {
        $this->selectedId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        $k = Keuangan::find($this->selectedId);
        if ($k->bukti_path && Storage::disk('public')->exists($k->bukti_path)) {
            Storage::disk('public')->delete($k->bukti_path);
        }
        $k->delete();

        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Transaksi dihapus!');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->tanggal = date('Y-m-d');
        $this->kategori = 'pemasukan';
        $this->sumber_atau_tujuan = '';
        $this->nominal = '';
        $this->keterangan = '';
        $this->bukti = null;
        $this->bukti_path = null;
        $this->originalSize = 0;
        $this->compressedSize = 0;
    }

    public function exportPdf()
    {
        $bulan = (int) $this->bulan_filter;
        $tahun = (int) $this->tahun_filter;

        // 1. Ambil Data (Sama seperti filter tabel, tapi GET semua tanpa pagination)
        $data = Keuangan::with('user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        // 2. Hitung Ringkasan untuk PDF
        $pemasukan = $data->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaran = $data->where('kategori', 'pengeluaran')->sum('nominal');

        // Saldo akhir sampai bulan ini (akumulatif)
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        $totalMasukAll = Keuangan::where('kategori', 'pemasukan')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $totalKeluarAll = Keuangan::where('kategori', 'pengeluaran')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $saldo = $totalMasukAll - $totalKeluarAll;

        // 3. Generate PDF
        $pdf = Pdf::loadView('pdf.keuangan', [
            'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo' => $saldo
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan-Keuangan-' . $bulan . '-' . $tahun . '.pdf');
    }
}

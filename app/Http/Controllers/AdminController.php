<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\Blacklist;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalVisitor = Visitor::count();

        $visitorHariIni = Visitor::whereDate(
            'created_at',
            today()
        )->count();

        $visitorAktif = Visitor::where(
            'status',
            'Masuk'
        )->count();

        $visitorKeluarHariIni = Visitor::whereDate(
            'waktu_keluar',
            today()
        )->count();


        $visitorTerbaru = Visitor::latest()
            ->take(5)
            ->get();


        return view('admin.dashboard', compact(
            'totalVisitor',
            'visitorHariIni',
            'visitorAktif',
            'visitorKeluarHariIni',
            'visitorTerbaru'
        ));
    }

    public function dashboardData()
    {
        $totalVisitor = Visitor::count();

        $visitorHariIni = Visitor::whereDate('created_at', today())->count();

        $visitorAktif = Visitor::where('status', 'Masuk')->count();

        $visitorKeluarHariIni = Visitor::whereDate('waktu_keluar', today())->count();

        $visitorTerbaru = Visitor::latest()->take(5)->get()->map(function ($visitor) {
            return [
                'nama'       => $visitor->nama,
                'perusahaan' => $visitor->perusahaan,
                'tujuan'     => $visitor->tujuan,
                'check_in'   => $visitor->waktu_masuk
                    ? $visitor->waktu_masuk->format('d M Y H:i')
                    : '-',
                'status'     => $visitor->status,
            ];
        });

        return response()->json([
            'total_visitor'          => $totalVisitor,
            'visitor_hari_ini'       => $visitorHariIni,
            'visitor_aktif'          => $visitorAktif,
            'visitor_keluar_hari_ini' => $visitorKeluarHariIni,
            'visitor_terbaru'        => $visitorTerbaru,
        ]);
    }
        public function visitorActive()
    {
         $visitors = \App\Models\Visitor::where('status', 'Masuk')
        ->orderBy('waktu_masuk', 'desc')
        ->get();

        return view('admin.visitor-active', compact('visitors'));
    }

    public function visitor()
    {
        $visitors = Visitor::latest()
            ->get();

        $blacklistedNoHp = Blacklist::pluck('no_hp')->toArray();

        return view(
            'admin.visitor',
            compact('visitors', 'blacklistedNoHp')
        );
    }

    public function visitorData()
    {
        $visitors = Visitor::latest()->get();

        $blacklistedNoHp = Blacklist::pluck('no_hp')->toArray();

        $data = $visitors->map(function ($visitor) use ($blacklistedNoHp) {
            return [
                'id'           => $visitor->id,
                'nama'         => $visitor->nama,
                'visitor_id'   => $visitor->visitor_id,
                'email'        => $visitor->email,
                'no_hp'        => $visitor->no_hp,
                'perusahaan'   => $visitor->perusahaan,
                'tujuan'       => $visitor->tujuan,
                'lantai'       => $visitor->lantai ? 'Lantai ' . $visitor->lantai : '-',
                'nomor_rak'    => $visitor->nomor_rak ?: '-',
                'check_in'     => $visitor->created_at->format('d M Y H:i'),
                'tanggal'      => $visitor->created_at->format('Y-m-d'),
                'status'       => $visitor->status,
                'blacklisted'  => in_array($visitor->no_hp, $blacklistedNoHp),
                'detail_url'   => route('admin.visitor.detail', $visitor->id),
            ];
        });

        return response()->json([
            'visitors' => $data,
        ]);
    }

    public function visitorDetail(Visitor $visitor)
    {
        $isBlacklisted = Blacklist::where('no_hp', $visitor->no_hp)->exists();

        return view('admin.detail-visitor', compact('visitor', 'isBlacklisted'));
    }

    // ===================== CRUD VISITOR (ADMIN) =====================

    public function edit(Visitor $visitor)
    {
        return view('admin.visitor-edit', compact('visitor'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $request->validate([
            'nama'       => 'required|string|min:3|max:100',
            'perusahaan' => 'required|string|max:150',
            'no_hp'      => 'required|string|min:10|max:15',
            'email'      => 'required|email',
            'tujuan'     => 'required|string',
            'foto'       => 'nullable|image|max:5120',
        ]);

        $data = $request->only([
            'nama', 'perusahaan', 'no_hp', 'email', 'tujuan',
        ]);

        if ($request->hasFile('foto')) {

            if ($visitor->foto && Storage::disk('public')->exists($visitor->foto)) {
                Storage::disk('public')->delete($visitor->foto);
            }

            $data['foto'] = $request->file('foto')->store('visitors', 'public');
        }

        $visitor->update($data);

        return redirect()
            ->route('admin.visitor.detail', $visitor->id)
            ->with('success', 'Data visitor berhasil diperbarui.');
    }

    public function destroy(Visitor $visitor)
    {
        if ($visitor->foto && Storage::disk('public')->exists($visitor->foto)) {
            Storage::disk('public')->delete($visitor->foto);
        }

        $visitor->delete();

        return redirect()
            ->route('admin.visitor')
            ->with('success', 'Data visitor berhasil dihapus.');
    }

    public function checkout(Visitor $visitor)
    {
        if ($visitor->status === 'Masuk') {

            $visitor->update([
                'status'       => 'Keluar',
                'waktu_keluar' => now(),
            ]);
        }

        return redirect()
            ->route('admin.visitor.detail', $visitor->id)
            ->with('success', 'Visitor berhasil check out.');
    }

    // ===================== BLACKLIST =====================

    public function blacklistStore(Request $request, Visitor $visitor)
    {
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        Blacklist::firstOrCreate(
            ['no_hp' => $visitor->no_hp],
            [
                'nama'           => $visitor->nama,
                'email'          => $visitor->email,
                'alasan'         => $request->alasan,
                'blacklisted_by' => auth()->id(),
            ]
        );

        return back()->with('success', $visitor->nama . ' berhasil ditambahkan ke blacklist.');
    }

    public function blacklistRemove(Visitor $visitor)
    {
        $deleted = Blacklist::where('no_hp', $visitor->no_hp)->delete();

        if (!$deleted) {
            return back()->with('error', $visitor->nama . ' tidak ada di daftar blacklist.');
        }

        return back()->with('success', $visitor->nama . ' berhasil dihapus dari blacklist.');
    }

    public function blacklistIndex()
    {
        $blacklists = Blacklist::latest()->paginate(10);

        return view('admin.blacklist', compact('blacklists'));
    }

    public function blacklistDestroy(Blacklist $blacklist)
    {
        $blacklist->delete();

        return back()->with('success', 'Visitor berhasil dihapus dari blacklist.');
    }

    public function profile()
    {
        $user = auth()->user();

        return view('admin.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function profilePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {

            return back()->withErrors([
                'current_password' => 'Password saat ini salah.',
            ]);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function setting()
    {
        $setting = \App\Models\Setting::current();

        return view('admin.setting', compact('setting'));
    }

    public function settingUpdate(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:150',
            'admin_email'  => 'required|email',
            'admin_phone'  => 'required|string|max:20',
        ]);

        $setting = \App\Models\Setting::current();

        $setting->update([
            'company_name'     => $request->company_name,
            'admin_email'      => $request->admin_email,
            'admin_phone'      => $request->admin_phone,
            'maintenance_mode' => $request->boolean('maintenance_mode'),
        ]);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function laporan(Request $request)
    {
        $visitors = $this->filteredLaporanQuery($request)
            ->latest('waktu_masuk')
            ->get();

        $totalVisitor   = Visitor::count();
        $visitorHariIni = Visitor::whereDate('created_at', today())->count();
        $visitorAktif   = Visitor::where('status', 'Masuk')->count();
        $visitorSelesai = Visitor::where('status', 'Keluar')->count();

        return view('admin.laporan', compact(
            'visitors',
            'totalVisitor',
            'visitorHariIni',
            'visitorAktif',
            'visitorSelesai'
        ));
    }

    public function laporanExport(Request $request)
    {
        $visitors = $this->filteredLaporanQuery($request)
            ->latest('waktu_masuk')
            ->get();

        $filename = 'laporan-visitor-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($visitors) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'No', 'Nama', 'ID Visitor', 'Perusahaan', 'No HP', 'Email',
                'Tujuan', 'Check In', 'Check Out', 'Status',
            ]);

            foreach ($visitors as $index => $visitor) {

                fputcsv($file, [
                    $index + 1,
                    $visitor->nama,
                    $visitor->visitor_id ?: '-',
                    $visitor->perusahaan,
                    $visitor->no_hp,
                    $visitor->email,
                    $visitor->tujuan,
                    optional($visitor->waktu_masuk)->format('d M Y H:i'),
                    $visitor->waktu_keluar
                        ? $visitor->waktu_keluar->format('d M Y H:i')
                        : '-',
                    $visitor->status === 'Masuk' ? 'Aktif' : 'Selesai',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function filteredLaporanQuery(Request $request)
    {
        $query = Visitor::query();

        if ($request->filled('start_date')) {
            $query->whereDate('waktu_masuk', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('waktu_masuk', '<=', $request->end_date);
        }

        return $query;
    }
}
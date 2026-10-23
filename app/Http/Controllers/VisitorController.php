<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\Blacklist;
use App\Models\Rak;

class VisitorController extends Controller
{
    /**
     * Halaman pilihan sebelum check-in: Register atau Login.
     */
    public function checkin()
    {
        return view('checkin-select');
    }

    /**
     * Form Register: dipakai visitor yang belum pernah check-in.
     * Wajib isi seluruh data (nama, email, perusahaan, tujuan,
     * lantai, nomor rak, foto selfie).
     */
    public function checkinRegister()
    {
        $raks = Rak::where('status', 'aktif')->orderBy('nomor_rak')->get();

        return view('checkin', compact('raks'));
    }

    /**
     * Form Login: dipakai visitor yang sudah pernah check-in
     * sebelumnya. Isi nomor HP, tujuan kunjungan, lantai, nomor
     * rak, dan foto selfie baru. Data nama, email, dan perusahaan
     * akan diambil otomatis dari data check-in sebelumnya.
     */
    public function checkinLogin()
    {
        return view('checkin-login');
    }

    /**
     * Dipanggil dari checkin.js saat visitor selesai mengisi Nomor HP.
     * Kalau nomor HP itu sudah pernah checkin sebelumnya, kembalikan
     * data kunjungan terakhirnya (tujuan, lantai, nomor rak, foto)
     * supaya form checkin bisa auto-fill.
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string',
        ]);

        $noHp = $this->normalizePhone((string) $request->no_hp);

        $visitor = Visitor::where('no_hp', $noHp)
            ->orderByDesc('waktu_masuk')
            ->first();

        if (!$visitor) {
            return response()->json([
                'success' => false,
                'reason'  => 'phone_not_found',
            ]);
        }

        // Dari halaman Login: ID Visitor wajib cocok dengan nomor HP.
        if ($request->filled('visitor_id')) {
            $visitorId = (string) $request->visitor_id;

            if (!Visitor::where('visitor_id', $visitorId)->exists()) {
                return response()->json([
                    'success' => false,
                    'reason'  => 'visitor_id_not_found',
                ]);
            }

            if ($visitor->visitor_id !== $visitorId) {
                return response()->json([
                    'success' => false,
                    'reason'  => 'mismatch',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama'       => $visitor->nama,
                'email'      => $visitor->email,
                'perusahaan' => $visitor->perusahaan,
                'lantai'     => $visitor->lantai,
                'nomor_rak'  => $visitor->nomor_rak,
                'foto'       => $visitor->foto
                    ? asset('storage/' . $visitor->foto)
                    : null,
            ],
        ]);
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'nama'       => 'required|string|min:3|max:100',
                'perusahaan' => 'required|string|max:150',
                'no_hp'      => 'required|string|min:10|max:15',
                'email'      => 'required|email',
                'tujuan'     => 'required|string',
                'lantai'     => 'required|integer|between:1,3',
                'nomor_rak'  => 'required|integer',
                'foto'       => 'required|image|max:5120',
            ]);

            $noHpNormalized = $this->normalizePhone($request->no_hp);

            // === CEK BLACKLIST ===
            $blacklisted = Blacklist::where('no_hp', $noHpNormalized)
                ->orWhereRaw('LOWER(email) = ?', [strtolower($request->email)])
                ->first();

            if ($blacklisted) {
                return back()
                    ->withErrors(['blacklist' => 'Maaf, Anda tidak dapat melakukan check-in karena masuk daftar blacklist.'])
                    ->withInput();
            }
            // === SELESAI CEK BLACKLIST ===

            $path = $request->file('foto')->store('visitors', 'public');

            // ID Visitor dibuat otomatis oleh sistem (bukan diinput visitor).
            // Kalau nomor HP ini sudah pernah terdaftar, ID lamanya dipakai lagi.
            $visitorId = $this->visitorIdForPhone($noHpNormalized);

            Visitor::create([
                'nama'          => $request->nama,
                'perusahaan'    => $request->perusahaan,
                'no_hp'         => $noHpNormalized,
                'visitor_id'    => $visitorId,
                'email'         => $request->email,
                'tujuan'        => $request->tujuan,
                'lantai'        => $request->lantai,
                'nomor_rak'     => $request->nomor_rak,
                'foto'          => $path,
                'status'        => 'Masuk',
                'waktu_masuk'   => now(),
            ]);

            return redirect()->route('success')->with('visitor_id', $visitorId);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {

            dd($e->getMessage());

        }
    }

    /**
     * Proses check-in lewat menu Login.
     * Visitor mengisi no_hp, tujuan, lantai, nomor_rak, dan
     * foto selfie baru. Data nama, email, dan perusahaan diambil
     * dari riwayat check-in terakhir milik nomor HP tersebut.
     */
    public function loginStore(Request $request)
    {
        try {

            $request->validate([
                'no_hp'      => 'required|string|min:10|max:15',
                'visitor_id' => ['required', 'digits:5'],
                'tujuan'    => 'required|string',
                'lantai'    => 'required|integer|between:1,3',
                'nomor_rak' => 'required|integer',
                'foto'      => 'required|image|max:5120',
            ]);

            $noHpNormalized = $this->normalizePhone($request->no_hp);

            // Ambil data visitor dari riwayat check-in terakhir
            $previous = Visitor::where('no_hp', $noHpNormalized)
                ->orderByDesc('waktu_masuk')
                ->first();

            if (!$previous) {
                return back()
                    ->withErrors(['no_hp' => 'Nomor HP belum terdaftar. Silakan gunakan menu Register untuk check-in pertama kali.'])
                    ->withInput();
            }

            if ($previous->visitor_id !== (string) $request->visitor_id) {
                return back()
                    ->withErrors(['visitor_id' => 'ID Visitor tidak cocok dengan nomor HP.'])
                    ->withInput();
            }

            // === CEK BLACKLIST ===
            $blacklisted = Blacklist::where('no_hp', $noHpNormalized)
                ->orWhereRaw('LOWER(email) = ?', [strtolower($previous->email)])
                ->first();

            if ($blacklisted) {
                return back()
                    ->withErrors(['blacklist' => 'Maaf, Anda tidak dapat melakukan check-in karena masuk daftar blacklist.'])
                    ->withInput();
            }
            // === SELESAI CEK BLACKLIST ===

            $path = $request->file('foto')->store('visitors', 'public');

            Visitor::create([
                'nama'          => $previous->nama,
                'perusahaan'    => $previous->perusahaan,
                'no_hp'         => $noHpNormalized,
                'visitor_id'    => $previous->visitor_id,
                'email'         => $previous->email,
                'tujuan'        => $request->tujuan,
                'lantai'        => $request->lantai,
                'nomor_rak'     => $request->nomor_rak,
                'foto'          => $path,
                'status'        => 'Masuk',
                'waktu_masuk'   => now(),
            ]);

            return redirect()->route('success');

        } catch (\Illuminate\Validation\ValidationException $e) {

            return back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {

            dd($e->getMessage());

        }
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function searchVisitor(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string',
        ]);

        $noHp = $this->normalizePhone((string) $request->no_hp);

        // Ambil data visitor terbaru berdasarkan nomor HP (tanpa filter status dulu)
        $visitor = Visitor::where('no_hp', $noHp)
            ->orderByDesc('waktu_masuk')
            ->first();

        if (!$visitor) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP tidak ditemukan dalam data visitor.',
            ], 404);
        }

        if ($visitor->status !== 'Masuk') {
            return response()->json([
                'success' => false,
                'message' => 'Visitor ini sudah melakukan checkout sebelumnya.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'          => $visitor->id,
                'nama'        => $visitor->nama,
                'no_hp'       => $visitor->no_hp,
                'email'       => $visitor->email,
                'perusahaan'  => $visitor->perusahaan,
                'tujuan'      => $visitor->tujuan,
                'lantai'      => $visitor->lantai ?: '-',
                'nomor_rak'   => $visitor->nomor_rak ?: '-',
                'foto'        => $visitor->foto
                    ? asset('storage/' . $visitor->foto)
                    : asset('assets/img/default-user.png'),
                'waktu_masuk' => $visitor->waktu_masuk
                    ? $visitor->waktu_masuk->format('d M Y - H:i') . ' WIB'
                    : '-',
            ],
        ]);
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:visitors,id',
        ]);

        $visitor = Visitor::where('id', $request->id)
                        ->where('status', 'Masuk')
                        ->first();

        if (!$visitor) {

            return response()->json([
                'success' => false,
                'message' => 'Visitor tidak ditemukan atau sudah checkout.',
            ], 404);
        }

        $visitor->update([

            'status' => 'Keluar',

            'waktu_keluar' => now()

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Checkout berhasil.',
        ]);
    }

    /**
     * Menormalkan format nomor HP:
     * - Menghapus semua karakter non-digit (spasi, tanda -, +, dsb)
     * - Mengubah awalan 62 atau +62 menjadi 0
     *
     * Contoh:
     * "+62 897-485-849" -> "0897485849"
     * "62897485849"      -> "0897485849"
     * "0897485849"       -> "0897485849"
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }

    /**
     * Ambil ID Visitor milik nomor HP ini, atau buat baru kalau belum ada.
     */
    private function visitorIdForPhone(string $noHp): string
    {
        $existing = Visitor::where('no_hp', $noHp)
            ->whereNotNull('visitor_id')
            ->value('visitor_id');

        return $existing ?: $this->generateVisitorId();
    }

    /**
     * Buat ID Visitor 5 digit angka yang belum dipakai siapa pun.
     */
    private function generateVisitorId(): string
    {
        do {
            $id = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (Visitor::where('visitor_id', $id)->exists());

        return $id;
    }
}

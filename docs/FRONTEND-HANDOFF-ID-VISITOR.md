# Catatan serah-terima Frontend -> Backend: ID Visitor

Frontend untuk ID Visitor (5 digit) sudah siap. **Backend belum diubah sama sekali**
pada revisi ini. Dokumen ini hanya menjelaskan apa yang sudah disiapkan frontend
dan apa yang diharapkan dari backend.

## 1. Registrasi -> tampilkan ID Visitor
- Halaman: `resources/views/success.blade.php` (route `success`).
- Frontend menampilkan kartu "ID Visitor Anda" (5 kotak digit + tombol Salin ID)
  jika session flash `visitor_id` berisi **tepat 5 digit angka**.
- Backend cukup mengirim ID setelah registrasi berhasil, contoh:
  `return redirect()->route('success')->with('visitor_id', '58321');`
- Jika `visitor_id` ada, halaman sukses **tidak redirect otomatis** (supaya visitor sempat
  menyimpan ID). Jika tidak ada (mis. visitor yang login), perilaku lama tetap (redirect 5 detik).
- Preview tampilan tanpa backend (hanya `APP_ENV=local`): `/success?preview_id=58321`

## 2. Login visitor = Nomor Telepon + ID Visitor
- Halaman: `resources/views/checkin-login.blade.php` + `public/assets/js/checkin-login.js`.
- Langkah 1 (Verifikasi): input `no_hp` dan `visitor_id` (5 digit, angka saja).
  Tombol "Lanjutkan" memanggil `GET {data-lookup-url}` (saat ini route `checkin.lookup`)
  dengan query `?no_hp=...&visitor_id=...`.
- Respons yang dipahami frontend:

  | Respons | Pesan di frontend |
  |---|---|
  | `{ "success": true, "data": { "nama", "perusahaan", "foto" } }` | lanjut ke langkah 2 |
  | `{ "success": false, "reason": "phone_not_found" }` (atau tanpa `reason`) | Nomor telepon tidak ditemukan. Harap periksa kembali nomor telepon Anda. |
  | `{ "success": false, "reason": "visitor_id_not_found" }` | ID Visitor tidak ditemukan. Harap periksa kembali ID Visitor Anda. |
  | `{ "success": false, "reason": "mismatch" }` | Nomor telepon dan ID Visitor tidak cocok. Harap periksa kembali data Anda. |
  | HTTP error / gagal koneksi | Tidak dapat terhubung ke server. Periksa koneksi Anda lalu coba lagi. |

- **Pencocokan nomor telepon <-> ID Visitor wajib dilakukan di backend.** Frontend hanya
  memvalidasi format. Jangan mengirim `visitor_id` yang tersimpan kembali ke browser.
- Langkah 2 (Data Kunjungan): `tujuan`, `lantai`, `nomor_rak`, `foto`. Submit akhir
  `POST route('visitor.login.store')` sekarang ikut mengirim field **`visitor_id`**
  (backend perlu memvalidasinya lagi saat submit).
- Error validasi dari server ditampilkan di bagian atas form untuk key:
  `blacklist`, `no_hp`, `visitor_id` (selain itu muncul pesan umum berbahasa Indonesia).

## 3. Catatan lain
- Setiap kunjungan memakai ID Visitor yang sama; ID tidak berubah per kunjungan
  (tidak ada perubahan di sisi frontend yang mengasumsikan sebaliknya).
- Favicon + logo baru: `public/favicon.ico`, `public/apple-touch-icon.png`,
  `public/assets/img/logo-sengked-icon.png`, `favicon-32.png`, `favicon-192.png`.
- Animasi UI dipusatkan di `public/assets/css/ui-motion.css` (dimuat lewat
  `resources/views/partials/head-assets.blade.php`).

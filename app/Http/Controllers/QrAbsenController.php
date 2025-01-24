<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrAbsen; // Menggunakan model QrAbsen untuk interaksi dengan database
use Endroid\QrCode\QrCode; // Library untuk membuat QR Code
use Endroid\QrCode\Writer\PngWriter; // Writer untuk output QR Code dalam format PNG
use Carbon\Carbon; // Library untuk manipulasi tanggal
use Barryvdh\DomPDF\Facade\Pdf; // Library untuk membuat file PDF

class QrAbsenController extends Controller
{
    /**
     * Generate kode unik untuk QR check-in
     */
    protected function generateQRCodeCheckin()
    {
        do {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Karakter yang digunakan untuk kode
            $code = substr(str_shuffle($characters), 0, 6); // Membuat kode acak sepanjang 6 karakter
        } while (QrAbsen::where('qr_checkin', $code)->exists()); // Periksa apakah kode sudah ada di database

        return $code; // Return kode yang unik
    }

    /**
     * Generate kode unik untuk QR check-out
     */
    protected function generateQRCodeCheckout()
    {
        do {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Karakter yang digunakan untuk kode
            $code = substr(str_shuffle($characters), 0, 6); // Membuat kode acak sepanjang 6 karakter
        } while (QrAbsen::where('qr_checkout', $code)->exists()); // Periksa apakah kode sudah ada di database

        return $code; // Return kode yang unik
    }

    /**
     * Membuat QR Code dari data tertentu
     */
    private function generateQrCode($data)
    {
        $qrCode = QrCode::create($data) // Membuat QR Code dengan data yang diberikan
            ->setSize(200) // Menentukan ukuran QR Code
            ->setMargin(10); // Menentukan margin QR Code

        $writer = new PngWriter(); // Menggunakan writer untuk output PNG
        $result = $writer->write($qrCode); // Menulis QR Code sebagai gambar PNG

        return base64_encode($result->getString()); // Encode hasil PNG ke base64 agar dapat digunakan di HTML
    }

    /**
     * Menampilkan halaman index QR Absen
     */
    public function index(Request $request)
    {
        $qrAbsen = QrAbsen::paginate(10); // Mengambil data QR Absen dengan pagination
        return view('pages.qr_absen.index', compact('qrAbsen')); // Mengirim data ke view
    }

    /**
     * Menampilkan detail QR Absen berdasarkan ID
     */
    public function show($id)
    {
        $qrAbsen = QrAbsen::find($id); // Mencari data QR Absen berdasarkan ID
        return view('pages.qr_absen.show', compact('qrAbsen')); // Mengirim data ke view
    }

    /**
     * Menampilkan halaman form untuk membuat QR Absen baru
     */
    public function create()
    {
        return view('pages.qr_absen.create'); // Menampilkan form create QR Absen
    }

    /**
     * Menyimpan QR Code untuk satu bulan penuh
     */
    public function store(Request $request)
    {
        // Validasi input bulan dalam format Y-m
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        // Membuat objek tanggal berdasarkan input bulan
        $month = Carbon::createFromFormat('Y-m', $request->month);
        $daysInMonth = $month->daysInMonth; // Mendapatkan jumlah hari dalam bulan tersebut

        // Looping untuk setiap hari dalam bulan
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $month->copy()->setDay($day); // Set tanggal pada hari tertentu

            QrAbsen::create([
                'date' => $date->format('Y-m-d'), // Format tanggal
                'qr_checkin' => $this->generateQRCodeCheckin(), // Generate kode check-in
                'qr_checkout' => $this->generateQRCodeCheckout(), // Generate kode check-out
            ]);
        }

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('qr_absens.index')->with('success', 'QR codes generated successfully for ' . $month->format('F Y'));
    }

    /**
     * Mengunduh file PDF yang berisi QR Code berdasarkan ID
     */
    public function downloadPDF($id)
    {
        $qrAbsen = QrAbsen::findOrFail($id); // Mencari data QR Absen berdasarkan ID atau gagal jika tidak ditemukan

        // Generate QR Code untuk check-in dan check-out sebagai gambar base64
        $qrCodeCheckin = $this->generateQrCode($qrAbsen->qr_checkin);
        $qrCodeCheckout = $this->generateQrCode($qrAbsen->qr_checkout);

        // Data yang akan dikirim ke view PDF
        $data = [
            'qrAbsen' => $qrAbsen,
            'qrCodeCheckin' => $qrCodeCheckin,
            'qrCodeCheckout' => $qrCodeCheckout,
        ];

        // Membuat PDF menggunakan view dan data
        $pdf = Pdf::loadView('pages.pdf.qr_absen', $data);

        // Mengunduh file PDF dengan nama file sesuai tanggal QR Absen
        return $pdf->download('qr_absen_' . $qrAbsen->date . '.pdf');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Contacts;
// use App\Models\User; // Tidak perlu jika tidak mengambil data user secara otomatis lagi
// use App\Models\Profile; // Tidak perlu jika tidak mengambil data profile secara otomatis lagi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller
{
    /**
     * Menampilkan semua pesan kontak.
     * (Biasanya untuk admin)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contacts::with('user')->get(); // Tetap bisa muat data user jika user_id ada
        return response()->json($contacts, 200);
    }

    /**
     * Mengirim pesan kontak baru.
     * Nama pengirim dan email pengirim WAJIB diinput di request body.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id', // User_id tetap opsional, jika user login
            'sender_name' => 'required|string|max:255', // Nama pengirim sekarang WAJIB diinput
            'sender_email' => 'required|email|max:255', // Email pengirim sekarang WAJIB diinput
            'sender_phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message_content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $contact = Contacts::create([
                'user_id' => $request->input('user_id'), // User_id diambil langsung dari input (bisa null)
                'sender_name' => $request->sender_name,   // Nama pengirim diambil dari input
                'sender_email' => $request->sender_email, // Email pengirim diambil dari input
                'sender_phone' => $request->sender_phone, // Telepon pengirim diambil dari input
                'subject' => $request->subject,
                'message_content' => $request->message_content,
                // Kolom 'status' tidak ada lagi
            ]);

            return response()->json([
                'message' => 'Pesan kontak berhasil dikirim',
                'contact' => $contact->load('user'), // Muat data user terkait (jika user_id ada)
            ], 201); // 201 Created

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengirim pesan kontak.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menampilkan detail pesan kontak tertentu.
     * Metode ini tetap sama.
     *
     * @param  int  $id ID pesan kontak
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contacts::with('user')->find($id); // Tetap bisa muat data user

        if (!$contact) {
            return response()->json(['message' => 'Pesan kontak tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail Pesan Kontak',
            'contact' => $contact,
        ], 200);
    }

    /**
     * Mengupdate pesan kontak.
     * Metode ini sekarang hanya untuk mengupdate field lain selain status (misalnya mengedit pesan).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id ID pesan kontak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contact = Contacts::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Pesan kontak tidak ditemukan'], 404);
        }

        // Validasi input update (semua field opsional saat update)
        $validator = Validator::make($request->all(), [
            'sender_name' => 'sometimes|required|string|max:255',
            'sender_email' => 'sometimes|required|email|max:255',
            'sender_phone' => 'nullable|string|max:20',
            'subject' => 'sometimes|required|string|max:255',
            'message_content' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $contact->update($request->all());

            return response()->json([
                'message' => 'Pesan kontak berhasil diupdate',
                'contact' => $contact->load('user'),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate pesan kontak.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menghapus pesan kontak.
     * Metode ini tetap sama.
     *
     * @param  int  $id ID pesan kontak
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contacts::find($id);

        if (!$contact) {
            return response()->json(['message' => 'Pesan kontak tidak ditemukan'], 404);
        }

        try {
            $contact->delete();
            return response()->json(['message' => 'Pesan kontak berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus pesan kontak.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Auth; // Tidak digunakan di sini, jadi bisa dikomentari/dihapus

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('user')->get();
        return response()->json($reservations, 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'reservation_datetime' => 'required|date_format:Y-m-d H:i:s|after:now',
            'number_of_guests' => 'required|integer|min:1',
            'area_preference' => 'nullable|string|in:indoor,outdoor',
            // Hapus validasi 'status' dari sini
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $reservation = Reservation::create([
                'user_id' => $request->user_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'reservation_datetime' => $request->reservation_datetime,
                'number_of_guests' => $request->number_of_guests,
                'area_preference' => $request->area_preference,
                // Hapus 'status' dari sini: 'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Reservasi berhasil dibuat',
                'reservation' => $reservation->load('user'),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat reservasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $reservation = Reservation::with('user')->find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservasi tidak ditemukan'], 404);
        }
        return response()->json([
            'message' => 'Detail Reservasi',
            'reservation' => $reservation,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservasi tidak ditemukan'], 404);
        }

        // Validasi input update (ubah 'required' menjadi 'sometimes|required' jika field opsional)
        $validator = Validator::make($request->all(), [
            'customer_name' => 'sometimes|required|string|max:255',
            'customer_phone' => 'sometimes|required|string|max:20',
            'reservation_datetime' => 'sometimes|required|date_format:Y-m-d H:i:s|after:now',
            'number_of_guests' => 'sometimes|required|integer|min:1',
            'area_preference' => 'nullable|string|in:indoor,outdoor',
            // Hapus validasi 'status' dari sini
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            // Update reservasi dengan semua data dari request (kecuali yang tidak di fillable)
            $reservation->update($request->all());

            return response()->json([
                'message' => 'Reservasi berhasil diupdate',
                'reservation' => $reservation->load('user'),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate reservasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservasi tidak ditemukan'], 404);
        }
        try {
            $reservation->delete();
            return response()->json(['message' => 'Reservasi berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus reservasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
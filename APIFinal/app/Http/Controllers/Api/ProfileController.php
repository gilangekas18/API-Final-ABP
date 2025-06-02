<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Diperlukan untuk transaksi database

class ProfileController extends Controller
{
    // Hapus metode 'storeOrUpdate' dan 'updateUserName' yang lama.

    /**
     * Menampilkan detail profil pengguna.
     * Metode ini tetap sama.
     *
     * @param  int  $userId ID dari user yang profilnya ingin dilihat.
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        // Mencari profil berdasarkan user_id, dan memuat data user terkait
        // Gunakan with(['user']) untuk memuat data user juga.
        $profile = Profile::with('user')->where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profile not found for this user.'], 404);
        }

        // Jika user tidak ditemukan melalui relasi (ini jarang terjadi jika FK onDelete:cascade)
        // Pastikan juga user tidak null
        if (!$profile->user) {
             return response()->json(['message' => 'User associated with this profile not found.'], 404);
        }

        return response()->json([
            'message' => 'Profile retrieved successfully',
            // Return data user dan profile dalam satu objek yang terstruktur
            'user' => $profile->user,
            'profile_details' => [
                'phone_number' => $profile->phone_number,
                'bio' => $profile->bio,
            ],
            // Atau Anda bisa return objek profile langsung seperti ini jika lebih suka:
            // 'profile' => $profile
        ], 200);
    }

    /**
     * Mengupdate nama, nomor HP, dan bio pengguna.
     * Nama di tabel 'users', nomor HP dan bio di tabel 'profiles'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId ID dari user yang profilnya ingin diupdate.
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255', // Nama opsional, tapi jika ada, wajib diisi
            'phone_number' => 'nullable|string|max:20', // No HP opsional
            'bio' => 'nullable|string|max:1000',         // Bio opsional
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Temukan user
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Gunakan transaksi untuk memastikan kedua update berhasil atau tidak sama sekali
        DB::beginTransaction();

        try {
            // Update nama user jika disediakan dalam request
            if ($request->has('name')) {
                $user->name = $request->name;
                $user->save();
            }

            // Update atau buat profil user
            // Jika profil belum ada, akan dibuat. Jika sudah ada, akan diupdate.
            $profile = Profile::updateOrCreate(
                ['user_id' => $userId], // Kunci untuk mencari profil
                [
                    'phone_number' => $request->phone_number,
                    'bio' => $request->bio,
                ]
            );

            DB::commit(); // Konfirmasi transaksi

            // Kembalikan data user dan profil yang sudah diupdate
            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user, // Data user yang sudah diupdate
                'profile_details' => [ // Detail profil yang sudah diupdate
                    'phone_number' => $profile->phone_number,
                    'bio' => $profile->bio,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika ada error
            return response()->json([
                'message' => 'Failed to update profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
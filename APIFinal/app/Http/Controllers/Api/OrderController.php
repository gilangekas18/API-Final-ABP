<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
// use Auth; // Ini dikomentari/dihapus karena Anda tidak menggunakan Auth::id() di sini

class OrderController extends Controller
{
    /**
     * Membuat pesanan baru.
     * Menerima array item menu yang dipesan (menggunakan nama menu).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validasi Input dari Frontend
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1', // Harus ada array 'items' dan tidak boleh kosong
            'items.*.menu_name' => 'required|string|min:1', // Setiap item harus punya nama menu (string tidak kosong)
            'items.*.quantity' => 'required|integer|min:1', // Kuantitas harus integer positif
            'user_id' => 'nullable|integer|exists:users,id', // user_id opsional dan harus ada di tabel users jika diisi
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        DB::beginTransaction(); // Memulai transaksi database

        try {
            $totalAmount = 0;
            $orderItemsData = [];
            $notFoundMenus = []; // Untuk melacak menu yang tidak ditemukan

            // 2. Iterasi dan Hitung Total Harga dari Setiap Item
            foreach ($request->items as $item) {
                // Cari menu berdasarkan nama di database
                $menu = Menu::where('name', $item['menu_name'])->first();

                if (!$menu) {
                    $notFoundMenus[] = $item['menu_name']; // Tambahkan ke daftar tidak ditemukan
                    continue; // Lanjutkan ke item berikutnya
                }

                $subtotal = $menu->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'menu_id' => $menu->id,
                    'menu_name_at_order' => $menu->name,  // Simpan nama menu dari DB (penting untuk nota)
                    'price_at_order' => $menu->price,    // Simpan harga menu dari DB
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                    'created_at' => now(), // Otomatis mengisi timestamp
                    'updated_at' => now(), // Otomatis mengisi timestamp
                ];
            }

            // Jika ada menu yang tidak ditemukan, batalkan transaksi dan beri tahu user
            if (!empty($notFoundMenus)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Gagal membuat pesanan. Beberapa menu tidak ditemukan atau salah nama:',
                    'not_found_menus' => $notFoundMenus,
                ], 404);
            }

            // Jika setelah filter tidak ada item yang valid untuk dipesan
            if (empty($orderItemsData)) {
                DB::rollBack();
                return response()->json(['message' => 'Tidak ada item menu yang valid untuk dipesan.'], 400);
            }

            // 3. Buat Entri Pesanan Utama di Tabel 'orders'
            $order = Order::create([
                // Mengambil user_id dari request body. Jika tidak ada, akan menjadi null (karena nullable di migrasi).
                'user_id' => $request->input('user_id'),
                // Kolom 'nomor_order' dan 'status' sudah DIBUANG dari migrasi dan model, jadi tidak perlu di sini.
                'total_amount' => $totalAmount,
            ]);

            // 4. Masukkan Detail Item Pesanan ke Tabel 'order_items'
            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            DB::commit(); // Konfirmasi semua perubahan database

            // 5. Berikan Respons Sukses
            return response()->json([
                'message' => 'Order berhasil dibuat',
                'order' => $order->load('orderItems'), // Muat item-item pesanan untuk respons
            ], 201); // HTTP Status 201 Created

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika terjadi kesalahan
            return response()->json([
                'message' => 'Gagal membuat pesanan.',
                'error' => $e->getMessage(),
            ], 500); // HTTP Status 500 Internal Server Error
        }
    }

    /**
     * Menampilkan detail pesanan (untuk nota).
     *
     * @param  int  $id ID pesanan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Muat pesanan beserta item-itemnya, dan dari item-item tersebut muat juga detail menu aslinya
        $order = Order::with(['orderItems.menu'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404); // HTTP Status 404 Not Found
        }

        return response()->json([
            'message' => 'Detail Pesanan',
            // Kolom 'id' dari tabel orders akan berfungsi sebagai ID Order Anda.
            'order' => $order,
        ], 200); // HTTP Status 200 OK
    }
}
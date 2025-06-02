<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menuItems = Menu::with('category')->get(); // Menampilkan kategori yang terkait
        return response()->json($menuItems);
    }

    public function show($id)
    {
        $menuItem = Menu::with('category')->find($id);
        if (!$menuItem) {
            return response()->json(['message' => 'Menu item not found'], 404);
        }
        return response()->json($menuItem);
    }
}
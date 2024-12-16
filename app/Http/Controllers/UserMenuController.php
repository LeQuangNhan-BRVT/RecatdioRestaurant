<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class UserMenuController extends Controller
{
    public function detail($slug)
    {
        $menu = Menu::where('slug', $slug)->firstOrFail();
        $suggestedMenus = Menu::where('category_code', $menu->category_code)
            ->where('id', '!=', $menu->id)
            ->where('status', 1)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('front.menu-detail', compact('menu', 'suggestedMenus'));
    }
}

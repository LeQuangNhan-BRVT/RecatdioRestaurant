<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class FrontController extends Controller
{
    public function index()
    {
        $categories = Category::with(['menu' => function($query) {
            $query->where('status', 1)
                  ->orderBy('position', 'ASC');
        }])
        ->where('status', 1)
        ->orderBy('id', 'ASC')
        ->get();

        return view('front.home', compact('categories'));
    }
    public function home() {
        return view('front.home');
    }

    public function about() {
        return view('front.about');
    }

    public function booking() {
        return view('front.booking');
    }
    public function contact(){
        return view('front.contact');
    }
    public function menu(){
        $categories = Category::with(['menu' => function($query) {
            $query->where('status', 1)
                  ->orderBy('position', 'ASC');
        }])
        ->where('status', 1)
        ->orderBy('id', 'ASC')
        ->get();
        
        return view('front.menu', compact('categories'));
    }
    public function service(){
        return view('front.service');
    }
    public function team(){
        return view('front.team');
    }
    public function testimonial(){
        return view('front.testimonial');
    }

    
}

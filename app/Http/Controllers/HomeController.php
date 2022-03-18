<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alimranahmed\LaraOCR\Facades\OCR;
use Alimranahmed\LaraOCR\Services\OcrAbstract;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function ocr(Request $request)
    {

        $input = $request->all();
        $ocr = app()->make(OcrAbstract::class);
        $parsedText = $ocr->scan($input['image']->getPathName());
        return view('lara_ocr.parsed_text', compact('parsedText'));
    }
}

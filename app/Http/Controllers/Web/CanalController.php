<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Canal;
use Inertia\Inertia;

class CanalController extends Controller
{
    public function getBySlug($slug)
    {
        $canal = Canal::with(['categories', 'appsCategories'])
        ->where('slug', $slug)->get()->first();
        

        if (!$canal) {
            return abort(404);
        }

        return Inertia::render('Canal', [
            'canal' => $canal
        ]);
    }
}

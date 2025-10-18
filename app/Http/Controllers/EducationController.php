<?php

namespace App\Http\Controllers;

use App\Models\Education;

class EducationController extends Controller
{
    public function index()
    {
        $educations = Education::whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('education.index', compact('educations'));
    }

    public function show(string $slug)
    {
        $education = Education::where('slug', $slug)->firstOrFail();

        return view('education.show', compact('education'));
    }
}

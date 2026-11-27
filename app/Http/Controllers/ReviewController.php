<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'rating'  => 'required|integer|min:1|max:5',
            'review'  => 'required|string|max:500',
            'service' => 'nullable|string|max:255',
        ], [
            'name.required'   => 'El nombre es obligatorio.',
            'rating.required' => 'Debes seleccionar una calificación.',
            'review.required' => 'La reseña es obligatoria.',
            'review.max'      => 'La reseña no puede tener más de 500 caracteres.',
        ]);

        // Guardamos la reseña (por defecto no está aprobada)
        Review::create($data);

        return back()->with('success', '¡Gracias por tu reseña! Será revisada antes de publicarse.');
    }
}
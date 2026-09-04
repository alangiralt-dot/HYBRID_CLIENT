<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CatalogueController extends Controller
{
    /**
     * Mostra els productes del catàleg connectant netament amb l'API de MariaDB.
     */
    public function showChildProducts(string $slug)
    {
        $slugToIdMap = [
            'llistons-de-fusta'                 => 7,
            'fusta-exterior'                    => 8,
            'bigues-fusta-laminades-autoclau'   => 11,
            'llistons-fusta-autoclau-marro'     => 9,
            'llistons-fusta-autoclau-verd'      => 10,
            'travesses-fusta-jardi'             => 12,
            'llistons-tropicals'                => 16,
            'motllures-de-fusta-pi-gallec'      => 4,
            'pals-rodons-de-fusta-a-l-autoclau' => 13,
            'perfils-laminats-finestra'         => 14,
            'fusta-vella-i-fusta-envellida'     => 15,
        ];

        if (!array_key_exists($slug, $slugToIdMap)) {
            abort(404);
        }

        $categoryId = $slugToIdMap[$slug];
        
        // Fem la petició HTTP directa cap al backend real de MariaDB que acabem de testar
        $apiBase = config('services.api_serra.url');
        $response = Http::get("{$apiBase}/api/categories/{$categoryId}/products");

        if ($response->failed()) {
            abort(500, 'Error en la connexió amb el repositori central de dades.');
        }

        // Extraiem les dades del JSON de la captura
        $apiData = $response->object();

        // Mapegem les propietats exactes: "category" i "products" tal com es veuen a la teva imatge
        $category = $apiData->category ?? null;
        $groupedProducts = $apiData->products ?? [];
        
        // Retornem la plantilla Blade de fusteria heretada de l'Sprint 4
        return view('catalogue', compact('groupedProducts', 'category'));
    }
}

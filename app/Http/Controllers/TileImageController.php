<?php

namespace App\Http\Controllers;

use App\Http\Resources\TileResource;
use App\Models\Tile;
use App\Services\TileImageService;
use App\Services\Validation\Base64ImageRule;
use Illuminate\Http\Request;

class TileImageController extends Controller
{
    public function update(Request $request, Tile $tile, TileImageService $service)
    {
        $rules = [
            'new_image_data' => [
                'nullable',
                new Base64ImageRule,
            ],
        ];

        $this->validate($request, $rules);

        $img = $request->input('new_image_data');
        $service->saveFront($tile, $img);

        if ($request->wantsJson()) {
            return new TileResource($tile);
        }
    }
}

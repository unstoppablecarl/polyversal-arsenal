<?php

namespace App\Http\Controllers;

use App\Models\Tile;
use App\Services\TileService;
use App\Services\Validation\Base64ImageRule;
use Illuminate\Http\Request;

class TileSourceImageController extends Controller
{
    public function updateFront(Request $request, TileService $service, Tile $tile)
    {
        $data = $this->sourceImageData($request);
        return [
            'source_image_url' => $service->updateFrontSourceImage($tile, $data),
        ];
    }

    public function updateBack(Request $request, TileService $service, Tile $tile)
    {
        $data = $this->sourceImageData($request);
        return [
            'source_image_url' => $service->updateBackSourceImage($tile, $data),
        ];
    }

    public function deleteFront(TileService $service, Tile $tile)
    {
        $service->deleteFrontSourceImage($tile);
    }

    public function deleteBack(TileService $service, Tile $tile)
    {
        $service->deleteBackSourceImage($tile);
    }

    protected function sourceImageData(Request $request)
    {
        $this->validate($request, [
            'source_image' => [
                app(Base64ImageRule::class),
            ],
        ]);

        return $request->input('source_image');
    }
}

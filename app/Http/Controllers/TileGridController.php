<?php

namespace App\Http\Controllers;

use App\Http\Resources\TileGridResource;
use App\Services\TileListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TileGridController extends Controller
{
    public function index()
    {
        return view('tiles.index');
    }

    public function grid(Request $request, TileListService $service)
    {
        $query   = $service->getQuery(Auth::user()->id);
        $perPage = $request->input('per_page', 10);

        $sortField     = $request->input('sort_field', 'id');
        $sortDirection = $request->input('sort_dir', 'asc');

        $sortFieldMap = [
            'targeting'  => 'targeting_id',
            'assault'    => 'assault_id',
            'tech_level' => 'tech_level_id',
            'mobility'   => 'mobility_id',
            'tile_class' => 'tile_class_id',
            'tile_type'  => 'tile_type_id',
        ];

        $sortField = array_get($sortFieldMap, $sortField, $sortField);

        $query->orderBy($sortField, $sortDirection);

        return TileGridResource::collection($query->paginate($perPage));
    }

}

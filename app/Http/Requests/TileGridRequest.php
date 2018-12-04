<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TileGridRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'per_page'   => Rule::in([10, 20]),
            'page'       => 'integer',
            'sort_field' => [
                'sometimes',
                Rule::in([
                    'id',
                    'tile_type',
                    'tile_class',
                    'tile_mobility',
                    'tech_level',

                    'armor',
                    'move',
                    'stealth',
                    'evasion',

                    'name',
                    'targeting_id',
                    'assault_id',
                    'anti_missile_system_id',

                    'cached_cost',
                ]),
            ],
            'sort_dir'   => [
                'sometimes',
                Rule::in([
                    'asc',
                    'desc',
                ]),
            ],
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Services\TileListService;
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
            'search'         => [
                'string',
                'nullable',
                'sometimes'
            ],
            'per_page'       => [
                'integer',
                Rule::in([10, 50, 100]),
            ],
            'page'           => 'integer',
            'sort_field'     => [
                'sometimes',
                Rule::in(TileListService::SORTABLE_COLUMNS),
            ],
            'sort_direction' => [
                'sometimes',
                Rule::in([
                    'asc',
                    'desc',
                ]),
            ],
        ];
    }

    public function perPage()
    {
        return (int)$this->input('per_page', 10);
    }

    public function page()
    {
        return (int)$this->input('page', 1);
    }

    public function sortField()
    {
        return $this->input('sort_field');
    }

    public function sortDirection()
    {
        return $this->input('sort_direction');
    }

    public function search()
    {
        return $this->input('search');
    }
}

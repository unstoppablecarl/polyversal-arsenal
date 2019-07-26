<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TilePrintSettingsSaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'front_is_printer_friendly' => 'bool',
            'front_invert_title'        => 'bool',
            'front_cut_line_color'      => null,
            'front_invert_abilities'    => 'bool',

            'back_is_printer_friendly'    => 'bool',
            'back_invert_title'           => 'bool',
            'back_cut_line_color'         => null,
            'back_invert_bottom_headings' => 'bool',
            'back_invert_flavor_text'     => 'bool',
        ];
    }
}

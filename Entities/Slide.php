<?php

namespace Modules\Slider\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'subheading',
        'button_text',
        'file',
    ];

    protected static function newFactory()
    {
        return \Modules\Slider\Database\factories\SlideFactory::new();
    }
}

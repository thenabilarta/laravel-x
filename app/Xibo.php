<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Xibo extends Model
{
    protected $guarded = [];

    public function xiboImage()
    {
        return ($this->image) ? '/storage/' . $this->image : '';
    }
}
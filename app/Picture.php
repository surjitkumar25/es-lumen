<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $table = 'tbl_pictures';

    protected $primaryKey = 'PictureID';
}

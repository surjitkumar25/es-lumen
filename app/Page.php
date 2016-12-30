<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * @var string
     */
    protected $table = 'tbl_pages';

    /**
     * @var string
     */
    protected $primaryKey = 'PageID';

    public function pictures()
    {
        return $this->hasMany(\App\Picture::class, 'PageID', 'PageID');
    }
}

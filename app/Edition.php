<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    /**
     * @var string
     */
    protected $table = 'tbl_editions';

    /**
     * @var string
     */
    protected $primaryKey = 'EditionId';

    /**
     * Defines one to many relation with Picture model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pictures()
    {
        return $this->hasMany(\App\Picture::class, 'EditionID', 'EditionID');
    }
}

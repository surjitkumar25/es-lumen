<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Story extends Model
{
    /**
     * @var string
     */
    protected $table = 'tbl_stories';

    /**
     * @var string
     */
    protected $primaryKey = 'StoryId';

    /**
     * Creates relation with Page model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(\App\Page::class, 'PageID');
    }

    /**
     * Creates relation with Edition model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function edition()
    {
        return $this->belongsTo(\App\Edition::class, 'EditionID');
    }
}

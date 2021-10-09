<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Finder\Iterator\FilecontentFilterIterator;

class Scoringcomponent extends Model
{
    use HasFactory;

    protected $fillable = ['abbr', 'bestscore','descr','eventversion_id', 'filecontenttype_id','interval','order_by','tolerance','worstscore',];

    protected $with = ['filecontenttype'];

    public function filecontenttype()
    {
        return $this->belongsTo(Filecontenttype::class);
    }
}

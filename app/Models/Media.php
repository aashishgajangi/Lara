<?php

namespace App\Models;

use Awcodes\Curator\Models\Media as CuratorMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends CuratorMedia
{
    use SoftDeletes;
}

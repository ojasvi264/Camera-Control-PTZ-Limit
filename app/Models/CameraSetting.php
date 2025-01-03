<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CameraSetting extends Model
{
    use HasFactory;

    protected $fillable = ['camera_id', 'zoom_level', 'hfov_left_right', 'vfov_up_down', 'pan_limit_max', 'pan_limit_min', 'tilt_limit_min', 'tilt_limit_max'];
}

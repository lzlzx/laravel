<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //关联的表
    public $table = 'permission';

    //表的主键
    public $primaryKey = 'id';

    //允许操作的字段
    // public $fillable = '['user_name']';
    public $guarded = [];//不允许操作字段

    //是否维护Created_at update_at
    public $timestamps = false;
}

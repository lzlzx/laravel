<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //关联的表
    public $table = 'user';

    //表的主键
    public $primaryKey = 'user_id';

    //允许操作的字段
   // public $fillable = '['user_name']';
    public $guarded = [];//不允许操作字段

    //是否维护Created_at update_at
    public $timestamps = false;

    //跟Role的关联模型
    public function role()
    {
        return $this->belongsToMany('App\Model\Role','user_role','user_id','role_id');
    }
}

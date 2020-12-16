<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //关联的表
    public $table = 'role';

    //表的主键
    public $primaryKey = 'id';

    //允许操作的字段
    // public $fillable = '['user_name']';
    public $guarded = [];//不允许操作字段

    //是否维护Created_at update_at
    public $timestamps = false;

    //添加动态属性 关联权限模型
    public function permission()
    {
        return $this->belongsToMany('App\Model\Permission','role_permission','role_id','permission_id');
    }
}

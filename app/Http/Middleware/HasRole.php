<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
use App\Model\Role;
use App\Model\Permission;

class HasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //1.获取当前请求的路径的控制器和方法名
        $route = \Route::current()->getActionName();
        //2.获取当前用户的权限组
        //$per_list = \Session::get('per_list');
        $per_list = session()->get('permission');
        if(empty($per_list))
        {
            $user = User::find(\Session::get('user')->user_id);
            $user_role = $user->role;
            $per_list = [];
            if(!empty($user_role))
            {
                echo 111;
                foreach ($user_role as $v)
                {
                    $role = Role::find($v->id);
                    $permission = $role->permission;
                    if(!empty($permission))
                    {
                        foreach ($permission as $value)
                        {
                            $per_list[] = $value->per_url;
                        }
                    }
                }
            }
            $per_list = array_unique($per_list);

            session()->put('permission',$per_list);
            $aaa = session()->get('permission');

        }
        //echo $route;
        //print_r($per_list);exit;
        if(in_array($route,$per_list))
        {
            return $next($request);
        }
        else
        {
            return redirect('admin/noaccess');
        }

    }
}

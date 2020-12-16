<?php

namespace App\Http\Controllers\Admin;

use App\Model\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Role;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = Role::orderBy('id','desc')
            ->where(function ($query) use ($request){
                $name = $request->input('rolename');
                if(!empty($name))
                {
                    $query->where('role_name','like','%'.$name.'%');
                }
            })
            ->paginate(2);

        return view('admin.role.list',['role'=>$role,'rolename'=>$request->input('rolename')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.role.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $role_name = $input['role_name'];
        $r = Role::where('role_name',$role_name)->exists();
        $is_res = true;
        //dd($r);
        if($r == true)
        {
            $is_res = false;
            $msg = '该角色已存在';
        }
        if($is_res)
        {
            $res = Role::create(['role_name'=>$role_name]);

            if($res)
            {
                return redirect('admin.role');
            }
            else
            {
                return back()->with('msg','添加失败')->withInput();
            }
        }
        else
        {
            return redirect('admin/role/create')->with('msg',$msg)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    //角色授权页面
    public function auth($id)
    {
        //获取当前角色
        $role = Role::find($id);
        //获取所有权限
        $perms = Permission::get();

        //获取当前角色权限
        $own = $role->permission;
        $own_perm = [];
        if(!empty($own))
        {
            foreach ($own as $k=>$v)
            {
                $own_perm[] = $v->id;
            }
        }

        return view('admin.role.auth',compact('role','perms','own_perm'));
    }
    //操作角色授权权限
    public function doauth(Request $request)
    {
        $input = $request->except('_token');
        //先删除当前用户已有的权限
        \DB::table('role_permission')->where('role_id',$input['role_id'])->delete();

        //添加新受理的权限
        if(!empty($input['permission_id']))
        {
            foreach ($input['permission_id'] as $k=>$v)
            {
                \DB::table('role_permission')->insert(['role_id'=>$input['role_id'],'permission_id'=>$v]);
            }
        }
        return redirect('admin/role');
    }
}

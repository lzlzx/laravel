<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Model\User;
use App\Model\Role;

class UserController extends Controller
{
    /**
     * 用户列表页面
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::orderBy('user_id','desc')
            ->where(function ($query) use ($request){
                $name = $request->input('username');
                if(!empty($name))
                {
                    $query->where('user_name','like','%'.$name.'%');
                }
            })
            ->paginate(2);
        return view('admin.user.list',['user'=>$user,'username'=>$request->input('username')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $user_name = $input['username'];
        $email     = $input['email'];
        $pass      = $input['pass'];
        $repass    = $input['repass'];

        $u = User::where('user_name',$user_name)->orWhere('email', $email)->exists();
        $is_res = true;
        //dd($u);
        if($u == true)
        {
            $is_res = false;
            $msg = '邮箱或者昵称重复';
        }
        if ($pass != $repass)
        {
            $is_res = false;
            $msg = '两次密码不同';
        }
        if($is_res)
        {
            $pass = Crypt::encrypt($pass);
            //print_r(['user_name'=>$user_name,'user_pass'=>$pass,'email'=>$email]);exit;
            $res = User::create(['user_name'=>$user_name,'user_pass'=>$pass,'email'=>$email]);

            if($res)
            {
                return ['status'=>0,'msg'=>'添加成功'];
            }
            else
            {
                return ['status'=>1,'msg'=>'添加失败'];
            }
        }
        else
        {
            return ['status'=>1,'msg'=>$msg];
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
        //$user = User::where('user_id',$id)->first();
        $user = User::find($id);
        return view('admin.user.edit',compact('user'));
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
        $input = $request->all();
        $user_name = $input['username'];
        $email     = $input['email'];
        $user = User::find($id);

        $user->user_name = $user_name;
        $user->email = $email;
        //print_R($user);exit;
        $res = $user->save();

        if($res)
        {
            return ['status'=>0,'msg'=>'修改成功'];
        }
        else
        {
            return ['status'=>1,'msg'=>'修改失败'];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $res = $user->delete();
        if($res)
        {
            return ['status'=>0,'msg'=>'删除成功'];
        }
        else
        {
            return ['status'=>1,'msg'=>'删除失败'];
        }
    }

    public function delAll(Request $request)
    {
        $input = $request->input('ids');

        $res = User::destroy($input);

        if($res){
            $data = [
                'status'=>0,
                'message'=>'删除成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'删除失败'
            ];
        }
        return $data;
    }

    //返回角色授权页面
    public function auth($id)
    {
        //根据ID获取用户
        $user = User::find($id);
        //获取所有的角色
        $roles = Role::get();

        //获取当前用户已经被授予的角色
        $own_roles = $user->role;
//        dd($own_roles);

        //当前用户拥有的角色的ID列表
        $own_roleids = [];
        foreach ($own_roles as $v){
            $own_roleids[] = $v->id;
        }

        return view('admin.user.auth',compact('user','roles','own_roleids'));
    }
    //处理角色授权
    public function doAuth(Request $request)
    {
        $input = $request->all();
//        dd($input);

        \DB::beginTransaction();

        try{
            //要执行的sql语句
            //删除当前角色被赋予的所有权限
            \DB::table('user_role')->where('user_id',$input['user_id'])->delete();

            if(!empty($input['role_id'])){
                //将提交的数据添加到 角色权限关联表
                foreach ($input['role_id'] as $v){
                    \DB::table('user_role')->insert([
                        'user_id'=>$input['user_id'],
                        'role_id'=>$v
                    ]);
                }
            }

            \DB::commit();

            return redirect('admin/user');


        }catch(Exception $e){
           \DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }

    }
}

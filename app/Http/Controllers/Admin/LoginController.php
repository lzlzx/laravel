<?php /** @noinspection PhpParamsInspection */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Model\User;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    //后台登录页面
    public function login()
    {

        return view("Admin.login");
    }

    //后台登录操作
    public function doLogin(Request $request)
    {
        //验证表单
        $input = $request->except("_token");
        $rule = [
            'username' => 'required|between:4,10',
            'password' => 'required|between:4,10',
            'code'     => 'required|captcha'
        ];
        $msg = [
            'username.required' => '用户名不能为空',
            'username.between' => '用户名长度在4-10位之间',
            'password.required' => '密码不能为空',
            'password.between' => '密码长度在4-10位之间',
            'code.required'     => '验证码不能为空',
            'code.captcha'     => '验证码错误',
        ];
        $validator = Validator::make($input, $rule,$msg);

        if($validator->fails())
        {
            return redirect('admin/login')->withErrors($validator)->withInput();
        }

        //验证成功从库中验证用户数据
        $user = User::where('user_name',$input['username'])->first();
        if(empty($user))
        {
            return redirect('admin/login')->with('msg','用户名错误')->withInput();
        }
        else
        {
            $pw = Crypt::decrypt($user->user_pass);
            if($input['password'] != $pw)
            {
                return redirect('admin/login')->with('msg','密码错误')->withInput();
            }
        }
        session()->put('user',$user);

        return redirect('admin/index');
    }

    public function logout()
    {
        session()->flush();
        return redirect('admin/login');
    }

    public function noaccess()
    {
        return view('errors.noaccess');
    }
}

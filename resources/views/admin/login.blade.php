<!doctype html>
<html  class="x-admin-sm">
<head>
	<meta charset="UTF-8">
	<title>后台登录</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{asset('css/admin/login.css')}}">
    @include('admin.public.css')
    @include('admin.public.script')

</head>
<body class="login-bg">
    
    <div class="login layui-anim layui-anim-up">
        <div class="message">博客后台-管理登录</div>
        <div id="darkbannerwrap"></div>

        @if(count($errors)>0)
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
         @endif
        @if(Session::has('msg'))
            {{Session::get('msg')}}
        @endif
         <form method="post" class="layui-form" action="{{url('admin/doLogin')}}">
             <input name="username" placeholder="用户名" value="{{old('username')}}"  type="text" lay-verify="required" class="layui-input" >
             <hr class="hr15">
             <input name="password" lay-verify="required" value="{{old('password')}}" placeholder="密码"  type="password" class="layui-input">
             <hr class="hr15">
             <input name="code" style="width:150px;display: initial;margin-right:50px; " lay-verify="required" placeholder="验证码"  type="text" class="layui-input">
             <img src="{{captcha_src()}}" onclick="this.src=this.src+'?'+Math.random()">
             <hr class="hr15">
             {{csrf_field()}}
             <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
             <hr class="hr20" >
         </form>
     </div>

     <script>
         $(function  () {
             layui.use('form', function(){
               var form = layui.form;
               // layer.msg('玩命卖萌中', function(){
               //   //关闭后的操作
               //   });
               //监听提交
                 form.on('submit(login)', function(data){

                 });

               });
             });

     </script>
     <!-- 底部结束 -->
 </body>
 </html>
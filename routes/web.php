<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//前台路由
Route::get('/home/index/index','Home\IndexController@index');

//后台路由
Route::group(['prefix'=>'admin','namespace'=>'Admin'],function (){
    //后台登录页
    Route::get('login','LoginController@login');
    //后台登录操作
    Route::post('doLogin','LoginController@doLogin');
    //没有权限路由
    Route::get('noaccess','LoginController@noaccess');
});

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['isLogin']],function(){
    //后台首页
    Route::get('index','IndexController@index');
    //后台欢迎页
    Route::get('welcome','IndexController@welcome');
    //后台退出登录
    Route::get('logout','LoginController@logout');
    //后台批量删除用户
    Route::post('user/del','UserController@delAll');
    //给用户授权相关路由
    Route::get('user/auth/{id}','UserController@auth');
    Route::post('user/doauth','UserController@doAuth');
    //后台用户模块路由
    Route::resource('user','UserController');

    //后台权限模块
    Route::resource('permission','PermissionController');
    //角色授权
    Route::get('role/auth/{id}','RoleController@auth');
    //操作角色授权
    Route::post('role/doauth','RoleController@doauth');
    //后台角色模块
    Route::resource('role','RoleController');

    // 权限模块路由
    Route::resource('permission','PermissionController');

    //    分类路由
//    修改排序
    Route::post('cate/changeorder','CateController@changeOrder');
    Route::resource('cate','CateController');

    //文章模块路由
    //上传路由
    //将markdown语法的内容转化为html语法的内容
    Route::post('article/pre_mk','ArticleController@pre_mk');
//    文章缩略图上传路由
    Route::post('article/upload','ArticleController@upload');
//    文章添加到推荐位路由
    Route::get('article/recommend','ArticleController@recommend');
    Route::resource('article','ArticleController');


});







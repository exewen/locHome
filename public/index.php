<?php
//1.自动加载
//
//2.初始化服务容器
//
//3. 注册共享的Kernel和异常处理器
//
//4. 处理请求和响应
//
//5. handle处理请求
//
//6. bootstrap方法
//
//7. 将响应信息发送到浏览器
//
//9. 处理继承自TerminableMiddleware
//
//10. Laravel路由
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/
//1.自动加载
require __DIR__.'/../bootstrap/autoload.php';
/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/
//4. 处理请求和响应 实例化App\Http\Kernel Illuminate\Foundation\Http\Kernel
//                设置$app/$router，初始化$router中middleware数值
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
//5. handle处理请求
//   a. 注册request实例到容器 ($app['request']->Illuminate\Http\Request)  --  $request是经过Symfony封装的请求对象
//        b. 清空之前容器中的request实例
//        c. 调用bootstrap方法，启动一系列启动类的bootstrap方法
//        d. 通过Pipeline发送请求，经过中间件，再由路由转发，最终返回响应
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
//7. 将响应信息发送到浏览器
$response->send();

//9. 处理继承自TerminableMiddleware
$kernel->terminate($request, $response);

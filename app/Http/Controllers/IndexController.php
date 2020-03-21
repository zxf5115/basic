<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-08
 *
 * 首页控制器类
 */
class IndexController extends Controller
{
  public function index()
  {
    return view('admin.index');
  }
}

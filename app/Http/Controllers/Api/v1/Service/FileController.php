<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

use App\Models\Service\System\File;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-24
 *
 * 文件上传接口控制器类
 */
class FileController extends ApiController
{

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-24
   * ------------------------------------------
   * 上传图片
   * ------------------------------------------
   *
   * 上传图片
   *
   * @param Request $request 请求参数
   * @return [type]
   */
  public function picture(Request $request)
  {
    $response = File::image('file', 'mavon');

    return self::success($response);
  }




  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-07
   * ------------------------------------------
   * 上传头像
   * ------------------------------------------
   *
   * 上传头像
   *
   * @param Request $request 请求参数
   * @return [type]
   */
  public function avatar(Request $request)
  {
    $response = File::image('file', 'avatar');

    return self::success($response);
  }
}

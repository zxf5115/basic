<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-19
 *
 * CrontabLog接口控制器类
 */
class CrontabLogController extends ApiController
{
  protected $_model = 'App\Models\Service\System\CrontabLog';


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-26
   * ------------------------------------------
   * 获取菜单tree
   * ------------------------------------------
   *
   * 获取菜单tree
   *
   * @param Request $request [description]
   * @return 菜单tree
   */
  public function list(Request $request)
  {
    $condition = [
      'status' => 1,
      'crontab_id' => $request->id
    ];

    $response = $this->_model::where($condition)->with(['crontab'=>function($query){
      $query->where(['status'=>1]);
    }])->paginate(20);

    return self::success($response);
  }
}

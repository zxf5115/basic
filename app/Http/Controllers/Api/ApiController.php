<?php
namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

use App\Http\Components\Code;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-19
 *
 * 基础接口控制器类
 */
class ApiController extends Controller
{
  protected $_relevance = false;

  private $_where = '';

  protected $_order = [
    ['key' => 'create_time', 'value' => 'desc'],
  ];

  private $_params = [
    'title',
    'username',
    'realname',
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-12
   * ------------------------------------------
   * 获取列表信息
   * ------------------------------------------
   *
   * 获取列表信息
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function list(Request $request)
  {
    $company_id = self::getCompanyId();

    $condition = [
      'status' => $this->_model::STATUS_ACTIVE,
      'company_id' => $company_id
    ];

    // 对用户请求进行过滤
    $filter = $this->filter($request->all());

    $condition = array_merge($condition, $filter);

    $response = $this->_model::getPaging($condition, $this->_order);

    return self::success($response);
  }


  public function view(Request $request, $id)
  {
    $company_id = self::getCompanyId();

    $condition = [
      'id' => $id,
      'company_id' => $company_id
    ];

    $response = $this->_model::getRow($condition, $this->_relevance)->getAttributes();

    return self::success($response);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-12
   * ------------------------------------------
   * 获取列表信息
   * ------------------------------------------
   *
   * 获取列表信息
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function select(Request $request)
  {
    $company_id = self::getCompanyId();
    // 对用户请求进行过滤
    $condition = ['status' => 1, 'company_id' => $company_id];

    $response = $this->_model::getList($condition, 'id', 'asc', true);

    return self::success($response);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-07
   * ------------------------------------------
   * 获取当前登录用户的企业编号
   * ------------------------------------------
   *
   * 获取当前登录用户的企业编号
   *
   * @return 企业编号
   */
  public static function getCompanyId()
  {
    return auth('api')->user()->id ?? 0;
    try
    {
      $company_id = auth('api')->user()->id;

      return $company_id;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return 0;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-07
   * ------------------------------------------
   * 获取当前用户编号
   * ------------------------------------------
   *
   * 获取当前用户编号
   *
   * @return 用户编号
   */
  public static function getCurrentId()
  {
    try
    {
      $current_user_id = Redis::hget('current_user', 'user_id');

      return $current_user_id;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return 0;
    }
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-12
   * ------------------------------------------
   * 删除信息
   * ------------------------------------------
   *
   * 删除信息
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function delete(Request $request)
  {
    try
    {
      $company_id = self::getCompanyId();
      $condition = ['status' => 1, 'company_id' => $company_id];


      $id = json_decode($request->id) ?? [0];

      $response = $this->_model::remove($id);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      return self::error(Code::$message[Code::ERROR]);
    }
  }











  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-12
   * ------------------------------------------
   * 对请求参数信息进行过滤与组装
   * ------------------------------------------
   *
   * 对请求参数信息进行过滤与组装
   *
   * @param [array] $request [请求参数]
   * @return [过滤与组装后的请求参数]
   */
  public function filter($request)
  {
    $company_id = self::getCompanyId();

    $condition = [
      'status' => 1,
      'company_id' => $company_id
    ];

    $params = array_filter($request, function($value, $key){
      return in_array($key, $this->_params);
    }, 1);

    if(!empty($params))
    {
      // 请求过滤操作
      foreach($params as $key => $param)
      {
        if('username' == $key || 'title' == $key || 'realname' == $key)
        {
          $condition[] = [$key, 'like', '%'.$param.'%'];
        }
        else
        {
          $condition = array_merge($condition, $param);
        }
      }

      if(!empty($where))
      {
        $condition = array_merge($condition, $where);
      }
    }

    return $condition;
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 成功信息
   * ------------------------------------------
   *
   * 返回成功信息
   *
   * @param array $data [数据信息]
   * @return 成功信息
   */
  public static function success($data = [], $message = '')
  {
    $code = Code::SUCCESS;

    $headers = ['content-type' => 'application/json'];

    $response = \Response::json(['status' => $code, 'message' => $message ?: Code::message($code), 'data' => $data]);
    return $response->withHeaders($headers);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 失败信息
   * ------------------------------------------
   *
   * 返回错误信息
   *
   * @param integer $code 错误代码
   * @return 错误信息
   */
  public static function error($code = 1000)
  {
    $headers = ['content-type' => 'application/json'];

    $response = \Response::json(['status' => $code, 'message' => Code::message($code)]);
    return $response->withHeaders($headers);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2019-11-03
   *
   * 本地环境下进行日志输出
   *
   * @param    [object]     $exception    [异常对象]
   *
   * @return   [false|错误]
   */
  public static function local(\Exception $exception)
  {
    if('local' == config('app.env'))
    {
      dd($exception->getMessage());
    }
    else
    {
      return false;
    }
  }
}

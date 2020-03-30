<?php
namespace App\Http\Controllers\Api\v1\Client\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-28
 *
 * 项目接口控制器类
 */
class ProjectController extends ApiController
{
  protected $_model = 'App\Models\Service\Project\Project';

  protected $_order = [
    ['key' => 'sort', 'value' => 'asc'],
  ];


  protected $_relevance = [
    'leader',
    'customer',
    'category',
    'personnel',
  ];


  protected $_params = [
    'category',
  ];



  public function list(Request $request)
  {
    $company_id = self::getCompanyId();

    $condition = [
      'status' => $this->_model::STATUS_ACTIVE,
      'company_id' => $company_id
    ];

    // 对用户请求进行过滤
    $filter = $this->filter($request->all());

    $condition = array_merge($condition, $this->_where, $filter);

    $response = $this->_model::getPaging($condition, $this->_relevance, $this->_order, false, 5);

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
  public function hot_list(Request $request)
  {
    $company_id = self::getCompanyId();
    // 对用户请求进行过滤
    $condition = ['status' => 1, 'hot' => 1, 'company_id' => $company_id];

    $response = $this->_model::getList($condition, $this->_relevance, 'id', 'asc', true, 5);

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
  public function new_list(Request $request)
  {
    $company_id = self::getCompanyId();
    // 对用户请求进行过滤
    $condition = ['status' => 1, 'new' => 1, 'company_id' => $company_id];

    $response = $this->_model::getList($condition, $this->_relevance, 'id', 'asc', true, 5);

    return self::success($response);
  }





  public function detail(Request $request)
  {
    $company_id = self::getCompanyId();

    $condition = [
      'id' => $request->id,
      'company_id' => $company_id
    ];

    $response = $this->_model::getRow($condition, $this->_relevance);

    return self::success($response);
  }
}

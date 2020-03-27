<?php
namespace App\Http\Controllers\Api\v1\Client\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-26
 *
 * 项目客户接口控制器类
 */
class CustomerController extends ApiController
{
  protected $_model = 'App\Models\Service\Project\Customer';

  protected $_order = [
    ['key' => 'sort', 'value' => 'asc'],
  ];


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

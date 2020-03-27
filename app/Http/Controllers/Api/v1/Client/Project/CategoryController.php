<?php
namespace App\Http\Controllers\Api\v1\Client\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-27
 *
 * 项目客户接口控制器类
 */
class CategoryController extends ApiController
{
  protected $_model = 'App\Models\Service\Project\Category';

  protected $_order = [
    ['key' => 'sort', 'value' => 'asc'],
  ];
}

<?php
namespace App\Http\Controllers\Api\v1\Service\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-27
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


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-12
   * ------------------------------------------
   * 新建信息
   * ------------------------------------------
   *
   * 新建信息
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function handle(Request $request)
  {
    $messages = [
      'title.required'  => '请您输入角色标题',
    ];

    $validator = Validator::make($request->all(), [
      'title' => 'required',
    ], $messages);


    if($validator->fails())
    {
      $error = $validator->getMessageBag()->toArray();
      $error = array_values($error);
      $message = $error[0][0] ?? Code::$message[Code::ERROR];

      return self::error($message);
    }
    else
    {
      $model = $this->_model::firstOrNew(['id' => $request->id]);

      $model->title = $request->title;
      $model->picture = $request->picture;
      $model->category = $request->category;
      $model->customer = $request->customer;
      $model->leader = $request->leader;
      $model->project_cost = $request->project_cost;
      $model->url = $request->url;
      $model->version = $request->version;
      $model->level = $request->level;
      $model->finish = $request->finish;
      $model->hot = $request->hot;
      $model->new = $request->new;
      $model->description = $request->description;
      $model->sort = $request->sort;


      foreach($request->personnel as $item)
      {
        $data[] = [
          'personnel_id' => $item,
          'company_id' => self::getCompanyId()
        ];
      }

      DB::beginTransaction();

      try
      {
        $response = $model->save();

        $relevance_response = $model->personnel()->createMany($data);

        DB::commit();

        return self::success(Code::$message[Code::HANDLE_SUCCESS]);
      }
      catch(\Exception $e)
      {
        DB::rollback();

        return self::error(Code::$message[Code::HANDLE_FAILURE]);
      }
    }
  }
}

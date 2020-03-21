<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Models\Service\System\User;
use App\Models\Service\System\Menu;
use App\Models\Service\System\Ｍessage;
use App\Models\Service\System\UserMessageRelevance;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-08
 *
 * User接口控制器类
 */
class UserController extends ApiController
{
  protected $_model = 'App\Models\Service\System\User';

  protected $_relevance = 'relevance';

  protected $_order = [
    ['key' => 'id', 'value' => 'asc'],
  ];

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-25
   * ------------------------------------------
   * 新增系统消息
   * ------------------------------------------
   *
   * 具体描述一些细节
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function handle(Request $request)
  {
    $messages = [
      'username.required' => '请您输入消息类型',
      'avatar.required'   => '请您输入头像',
      'realname.required' => '请您输入真实名称',
      'role_id.required'  => '请您选择角色',
    ];

    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'avatar' => 'required',
      'role_id' => 'required',
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

      if(empty($request->id))
      {
        $password = $this->_model::generate(123456);
        $model->password = $password;
      }

      $model->company_id = self::getCompanyId();
      $model->username = $request->username;
      $model->avatar   = $request->avatar;
      $model->realname = $request->realname;
      $model->email    = $request->email;
      $model->mobile   = $request->mobile;
      $model->status   = $request->status;

      $data = [
        'role_id' => $request->role_id
      ];

      DB::beginTransaction();

      try
      {
        $response = $model->save();

        $relevance_response = $model->relevance()->create($data);

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



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-25
   * ------------------------------------------
   * 修改密码
   * ------------------------------------------
   *
   * 修改密码
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function password(Request $request)
  {
    $messages = [
      'password.required' => '请您输入密码',
    ];

    $validator = Validator::make($request->all(), [
      'password' => 'required',
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
      $model = $this->_model::find($request->id);

      $model->password = $this->_model::generate($request->password);

      $response = $model->save();

      if($response)
      {
        return self::success(Code::$message[Code::HANDLE_SUCCESS]);
      }
      else
      {
        return self::error(Code::$message[Code::HANDLE_FAILURE]);
      }
    }
  }




  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-08
   * ------------------------------------------
   * 获取token
   * ------------------------------------------
   *
   * 通过客户信息获取token
   *
   * @param Request $request 请求参数
   * @return token信息
   */
  public function info()
  {
    try
    {
      if(! $id = self::getCurrentId())
      {
        return self::error(Code::API_UNAUTHORIZED);
      }

      $customer_logo = auth('api')->user()->customer_logo;
      $customer_name = auth('api')->user()->customer_name;

      $response = User::find($id);

      $result = [
        'id'              => $response->id,
        'realname'        => $response->realname,
        'avatar'          => $response->avatar,
        'last_login_time' => $response->last_login_time->format('Y-m-d H:i:s'),
        'last_login_ip'   => $response->last_login_ip,
        'customer_name'   => $customer_name,
        'customer_logo'   => $customer_logo
      ];

      return self::success($result);
    }
    catch(\Exception $e)
    {
      self::local($e);

      return self::error(Code::$message[Code::ERROR]);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-11
   * ------------------------------------------
   * 获取当前用户角色对应的菜单树
   * ------------------------------------------
   *
   * 获取当前用户角色对应的菜单树
   *
   * @param Request $request [请求参数]
   * @return [菜单树]
   */
  public function tree(Request $request)
  {
    try
    {
      $result = [];

      $user_id = self::getCurrentId();

      $role = User::find($user_id)->relevance->role;

      $permission = $role->permission->toArray();

      $menu_ids = array_column($permission, 'menu_id');

      // 获取菜单tree
      $menu = Menu::getCrrentMenus($menu_ids);

      // 获取按钮权限
      $button = Menu::getButton($menu_ids);
      $button = array_column($button, 'url');
      $button = array_map(function($item){
        return str_replace('/', ':', $item);
      }, $button);


      $result = ['menu' => $menu, 'button' => $button];

      return self::success($result);
    }
    catch(\Exception $e)
    {
      self::local($e);

      return self::error(Code::$message[Code::ERROR]);
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-27
   * ------------------------------------------
   * 当前用户消息
   * ------------------------------------------
   *
   * 当前用户消息
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function message(Request $request)
  {
    try
    {
      $user_id = self::getCurrentId();

      $unread = UserMessageRelevance::where(['user_id' => $user_id])->with(['message'=>function($query){
        $query->where(['status'=>1]);
      }])->where(['status'=>1])->get();

      $unread_count = count($unread) > 0 ? count($unread) : '';


      $readed =  UserMessageRelevance::where(['user_id' => $user_id])->with(['message'=>function($query){
        $query->where(['status'=>1]);
      }])->where(['status'=>2 ])->paginate(10);

      $response = [
        'unread'       => $unread,
        'unread_count' => $unread_count,
        'readed'       => $readed,
      ];

      return self::success($response);
    }
    catch(\Exception $e)
    {
      self::local($e);

      return self::error(Code::$message[Code::ERROR]);
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-28
   * ------------------------------------------
   * 函数功能简述
   * ------------------------------------------
   *
   * 具体描述一些细节
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function remove_message(Request $request)
  {
    try
    {
      $id = $request->id ?? '';
      $user_id = self::getCurrentId();

      $model = UserMessageRelevance::where(['user_id' => $user_id])->where(['status' => 2]);

      if($id > 0)
      {
        $model = $model->where(['id' => $id]);
      }

      $model->delete();

      return self::success(Code::$message[Code::HANDLE_SUCCESS]);
    }
    catch(\Exception $e)
    {
      self::log($e);

      return self::error(Code::$message[Code::HANDLE_FAILURE]);
    }
  }
}

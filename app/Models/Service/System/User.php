<?php
namespace App\Models\Service\System;

use App\Models\Basic;
use Illuminate\Support\Facades\Redis;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-19
 *
 * 系统用户模型类
 */
class User extends Basic
{
  // 表名
  protected $table = 'system_user';

  // 隐藏的属性
  protected $hidden = [
    'password', 'remember_token', 'password_salt', 'try_number', 'last_login_ip'
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 访问限制
   * ------------------------------------------
   *
   * 在一个小时内访问超过五次，就会触发禁止访问
   *
   * @param [type] $request [description]
   */
  public static function AccessRestrictions($request)
  {
    try
    {
      // 如果用户上次登录时间和当前时间相差小于一个小时并且登录次数小于五次，返回可以访问，否则禁止访问
      if(time() - $request->last_login_time < 3600 && $request->try_number > 5)
      {
        return true;
      }

      return false;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 验证密码
   * ------------------------------------------
   *
   * 具体描述一些细节
   *
   * @param array $request 用户对象
   * @param string $password 用户输入的密码
   * @return 密码正确返回false，否嘴true
   */
  public static function checkPassword($request, $password)
  {
    try
    {
      // $request->password_salt;

      if(password_verify($password, $request->password))
      {
        return false;
      }

      return true;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 设置密码尝试数据
   * ------------------------------------------
   *
   * 在用户输入密码错误后，进行尝试次数记录
   *
   * @param object $request 用户信息
   */
  public static function setTryNumber($request)
  {
    try
    {
      $request->increment('try_number'); //  = $request->try_number + 1
      $request->save();

      return true;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }




  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 生成密码
   * ------------------------------------------
   *
   * 生成密码 TODO： 后期修改进行加盐处理
   *
   * @param string $password 用户输入的密码
   * @return 加密的密码信息
   */
  public static function generate($password)
  {
    $salt = bin2hex(random_bytes(60));

    $options = [
      'cost' => 12,
    ];

    $password = password_hash($password, PASSWORD_BCRYPT, $options);

    // $password = $password | $salt;

    return $password;
  }



  public static function login($request)
  {
    try
    {
      $request->last_login_time = time();
      $request->try_number = 0;
      $request->save();

      return true;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }


    // $rules = Rule::getInstance()->getRulesByUID($userInfo['id']);
    // $this->request->session()->put('loginInfo', [
    //   'admin_id' => $userInfo['id'],
    //   'username' => $userInfo['username'],
    //   'rules'    => $rules,
    // ]);

    // $menus = [];
    // $list = Rule::getInstance()->getList(['menu' => 1, 'status' => 1]);
    // foreach ($list as $row) {
    //   if(in_array($row['name'], $rules)) {
    //     $menus[] = $row;
    //   }
    // }

    // $userInfo['menus'] = Common::generateRuleTree($menus, 0);

    // return $userInfo;
  }





  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 用户与角色关联函数
   * ------------------------------------------
   *
   * 用户与角色关联函数
   *
   * @return [关联对象]
   */
  public function relevance()
  {
      return $this->hasOne('App\Models\Service\System\UserRoleRelevance', 'user_id', 'id');
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-07
   * ------------------------------------------
   * 注册关联删除
   * ------------------------------------------
   *
   * 注册关联删除
   *
   * @return [type]
   */
  protected static function boot()
  {
    parent::boot();

    static::deleted(function($model) {
      $model->relevance()->delete();
    });
  }
}

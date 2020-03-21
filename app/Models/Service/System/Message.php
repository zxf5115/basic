<?php
namespace App\Models\Service\System;

use App\Models\Basic;
use Illuminate\Support\Facades\Redis;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-22
 *
 * 系统消息模型类
 */
class Message extends Basic
{
  // 表名
  protected $table = 'system_message';

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];


  /**
   * 转换属性类型
   */
  protected $casts = [
    'status' => 'array',
    'type' => 'array',
    'create_time' => 'datetime:Y-m-d H:i:s',
    'update_time' => 'datetime:Y-m-d H:i:s',
  ];




  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-22
   * ------------------------------------------
   * 消息类型属性类型转换函数
   * ------------------------------------------
   *
   * 消息类型属性类型转换函数
   *
   * @param int $value [数据库存在的值]
   * @return 消息类型
   */
  public function getTypeAttribute($value)
  {
    $status = [
      self::STATUS_ACTIVE   => '通知',
      self::STATUS_INACTIVE => '待办',
      self::STATUS_START    => '公告',
      self::STATUS_STOP     => '预警',
    ];

    return $status[$value];
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-23
   * ------------------------------------------
   * 获取消息类型
   * ------------------------------------------
   *
   * 获取消息类型
   *
   * @return [type]
   */
  public static function getType()
  {
    $status = [
      self::STATUS_ACTIVE   => '通知',
      self::STATUS_INACTIVE => '待办',
      self::STATUS_START    => '公告',
      self::STATUS_STOP     => '预警',
    ];

    return $status;
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-26
   * ------------------------------------------
   * 直接获取推送用户
   * ------------------------------------------
   *
   * 直接获取推送用户
   *
   * @param object $request [请求数据]
   * @return 推送用户
   */
  public static function getUsers($request)
  {
    if($request->type != 3 && 'user' == $request->accept_type)
    {
      $users = self::getWithUserUsers($request->user_list);
    }
    else if($request->type != 3 && 'role' == $request->accept_type)
    {
      $users = self::getWithRoleUsers($request->role_list);
    }
    else
    {
      $users = self::getAllUserId();
    }

    return $users;
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-26
   * ------------------------------------------
   * 直接获取推送用户
   * ------------------------------------------
   *
   * 根据推送类型为用户，直接获取推送用户
   *
   * @param　array $user_list [用户列表]
   * @return 推送用户列表
   */
  public static function getWithUserUsers($user_list)
  {
    $users = [];

    $current_user_id = Redis::hget('current_user', 'user_id');

    foreach($user_list as $user)
    {
      if(0 < $user && $current_user_id != $user)
      {
        $users[] = ['user_id' => $user, 'company_id' => auth('api')->user()->id];
      }
    }

    return $users;
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-26
   * ------------------------------------------
   * 通过角色查询获取推送用户
   * ------------------------------------------
   *
   * 根据推送类型为角色，通过角色查询获取推送用户
   *
   * @param [type] $role_list [角色列表]
   * @return 推送用户列表
   */
  public static function getWithRoleUsers($role_list)
  {
    $response = UserRoleRelevance::whereIn('role_id', $role_list)->get('user_id')->toArray();

    $current_user_id = Redis::hget('current_user', 'user_id');

    foreach($response as $key => $item)
    {
      if($current_user_id != $item['user_id'])
      {
        $response[$key]['company_id'] = auth('api')->user()->id;
      }
    }

    return $response;
  }


  public static function getAllUserId()
  {
    $current_user_id = Redis::hget('current_user', 'user_id');

    $response = User::where(['status' => 1])->where('id', '!=',$current_user_id)->get('id as user_id')->toArray();

    foreach($response as $key => $item)
    {
      $response[$key]['company_id'] = auth('api')->user()->id;
    }

    return $response;
  }







  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-22
   * ------------------------------------------
   * 系统消息与用户关联函数
   * ------------------------------------------
   *
   * 系统消息与用户关联函数
   *
   * @return [关联对象]
   */
  public function relevance()
  {
      return $this->hasMany('App\Models\Service\System\UserMessageRelevance', 'message_id');
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

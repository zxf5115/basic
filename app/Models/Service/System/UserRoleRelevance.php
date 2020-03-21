<?php
namespace App\Models\Service\System;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-20
 *
 * 系统角色模型类
 */
class UserRoleRelevance extends Basic
{
  // 表名
  protected $table = 'system_user_role_relevance';

  /**
   * 可以被批量赋值的属性
   */
  protected $fillable = ['role_id'];


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
  public function role()
  {
      return $this->hasOne('App\Models\Service\System\Role', 'id', 'role_id');
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-07
   * ------------------------------------------
   * 反向关联用户
   * ------------------------------------------
   *
   * 反向关联用户
   *
   * @return [type]
   */
  public function user()
  {
    return $this->belongsTo('App\Models\Service\System\User');
  }
}

<?php
namespace App\Models\Service\System;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-20
 *
 * 系统权限模型类
 */
class Permission extends Basic
{
  // 表名
  protected $table = 'system_permission';

  /**
   * 可以被批量赋值的属性
   */
  protected $fillable = ['menu_id'];




  // 关联函数 ------------------------------------------------------


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
  public function role()
  {
    return $this->belongsTo('App\Models\Service\System\Role');
  }
}

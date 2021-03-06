<?php
namespace App\Models\Service\System;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-19
 *
 * 系统定时任务日志模型类
 */
class CrontabLog extends Basic
{
  // 表名
  protected $table = 'system_crontab_log';

  /**
   * 转换属性类型
   */
  protected $casts = [
    'status' => 'array',
    'create_time' => 'datetime:Y-m-d H:i:s',
    'update_time' => 'datetime:Y-m-d H:i:s',
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 状态属性类型转换函数
   * ------------------------------------------
   *
   * 状态属性类型转换函数
   *
   * @param int $value [数据库存在的值]
   * @return 状态值
   */
  public function getStatusAttribute($value)
  {
    $status = [
      self::STATUS_ACTIVE   => '成功',
      self::STATUS_INACTIVE => '失败',
      self::STATUS_DELETE   => '删除',
    ];

    return $status[$value];
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
  public function crontab()
  {
    return $this->hasOne('App\Models\Service\System\Crontab', 'id', 'crontab_id');
  }

}

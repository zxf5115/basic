<?php
namespace App\Models\Service\System;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-12
 *
 * 系统菜单模型类
 */
class Log extends Basic
{
  // 表名
  protected $table = 'system_log';

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];
}

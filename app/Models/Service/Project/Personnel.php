<?php
namespace App\Models\Service\Project;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-26
 *
 * 项目开发人员模型类
 */
class Personnel extends Basic
{
  // 表名
  protected $table = 'module_project_personnel';


  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];
}

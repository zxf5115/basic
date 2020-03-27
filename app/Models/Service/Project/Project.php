<?php
namespace App\Models\Service\Project;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-26
 *
 * 项目模型类
 */
class Project extends Basic
{
  // 表名
  protected $table = 'module_project';


  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];
}

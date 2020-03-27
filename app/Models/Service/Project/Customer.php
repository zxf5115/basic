<?php
namespace App\Models\Service\Project;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-26
 *
 * 项目客户模型类
 */
class Customer extends Basic
{
  // 表名
  protected $table = 'module_project_customer';


  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];
}

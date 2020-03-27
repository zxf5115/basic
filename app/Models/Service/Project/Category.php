<?php
namespace App\Models\Service\Project;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-26
 *
 * 项目分类模型类
 */
class Category extends Basic
{
  // 表名
  protected $table = 'module_project_category';


  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];

}

<?php
namespace App\Models\Service\Project;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-26
 *
 * 项目与项目开发人员关联模型类
 */
class ProjectPersonnelRelevance extends Basic
{
  // 表名
  protected $table = 'module_project_personnel_relevance';

  /**
   * 可以被批量赋值的属性
   */
  protected $fillable = ['personnel_id'];

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];



  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-28
   * ------------------------------------------
   * 项目开发人员与开发人员关联函数
   * ------------------------------------------
   *
   * 项目开发人员与开发人员关联函数
   *
   * @return [关联对象]
   */
  public function personnel()
  {
      return $this->hasOne('App\Models\Service\Project\Personnel', 'id', 'personnel_id');
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-28
   * ------------------------------------------
   * 反向关联项目
   * ------------------------------------------
   *
   * 反向关联项目
   *
   * @return [type]
   */
  public function project()
  {
    return $this->belongsTo('App\Models\Service\Project\Project');
  }
}

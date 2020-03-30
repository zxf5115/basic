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





  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-27
   * ------------------------------------------
   * 项目与项目分类关联函数
   * ------------------------------------------
   *
   * 项目与项目分类关联函数
   *
   * @return [关联对象]
   */
  public function category()
  {
      return $this->hasOne('App\Models\Service\Project\Category', 'id', 'category');
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-27
   * ------------------------------------------
   * 项目与项目客户关联函数
   * ------------------------------------------
   *
   * 项目与项目客户关联函数
   *
   * @return [关联对象]
   */
  public function customer()
  {
      return $this->hasOne('App\Models\Service\Project\Customer', 'id', 'customer');
  }

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-27
   * ------------------------------------------
   * 项目与项目客户关联函数
   * ------------------------------------------
   *
   * 项目与项目客户关联函数
   *
   * @return [关联对象]
   */
  public function leader()
  {
      return $this->hasOne('App\Models\Service\Project\Personnel', 'id', 'leader');
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-27
   * ------------------------------------------
   * 项目与项目客户关联函数
   * ------------------------------------------
   *
   * 项目与项目客户关联函数
   *
   * @return [关联对象]
   */
  public function personnel()
  {
      return $this->hasMany('App\Models\Service\Project\ProjectPersonnelRelevance', 'project_id', 'id');
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
      $model->personnel()->delete();
    });
  }
}

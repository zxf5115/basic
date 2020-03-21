<?php
namespace App\Models\Service\System;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-20
 *
 * 系统菜单模型类
 */
class Menu extends Basic
{
  // 表名
  protected $table = 'system_menu';


  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];

  // 追加到模型数组表单的访问器
  protected $appends = ['remark'];

  /**
   * 转换属性类型
   */
  protected $casts = [
    'type' => 'array',
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
      self::STATUS_ACTIVE   => '正常',
      self::STATUS_INACTIVE => '禁用',
      self::STATUS_DELETE   => '删除',
    ];

    return $status[$value];
  }


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
  public function getTypeAttribute($value)
  {
    $status = [
      self::STATUS_ACTIVE   => '菜单',
      self::STATUS_INACTIVE => '按钮',
    ];

    return $status[$value];
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-08
   * ------------------------------------------
   * 拼接菜单信息
   * ------------------------------------------
   *
   * 拼接菜单信息, 用于显示菜单权限
   *
   * @return 菜单权限
   */
  public function getRemarkAttribute()
  {
    return $this->title . ' ['. $this->type . ']';
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-22
   * ------------------------------------------
   * 函数功能简述
   * ------------------------------------------
   *
   * 具体描述一些细节
   *
   * @return [type]
   */
  public static function getCrrentMenus($ids)
  {
    try
    {
      $response = self::with(['children' => function($query) use ($ids)
                  {
                    return $query->with(['children' => function($query) use ($ids)
                           {
                            $query->where('type', 1)->whereIn('id', $ids);
                           }])
                           ->where('type', 1)->whereIn('id', $ids);
                  }])
                  ->whereIn('id', $ids)
                  ->where(['pid' => 0])
                  ->where('type', 1)
                  ->where(['status' => 1])
                  ->orderBy('sort')
                  ->get()
                  ->toArray();

      return $response;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-22
   * ------------------------------------------
   * 函数功能简述
   * ------------------------------------------
   *
   * 具体描述一些细节
   *
   * @return [type]
   */
  public static function getMenus()
  {
    try
    {
      $response = self::with(['children' => function($query)
                  {
                    return $query->with(['children' => function($query)
                           {
                            $query->where('type', 1);
                           }]);
                  }])
                  ->where(['pid' => 0])
                  ->where(['status' => 1])
                  ->orderBy('sort')
                  ->get()
                  ->toArray();

      return $response;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }




  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-08
   * ------------------------------------------
   * 获取按钮权限
   * ------------------------------------------
   *
   * 获取按钮权限
   *
   * @param string $ids 按钮id值
   * @return 按钮权限
   */
  public static function getButton($ids)
  {
    try
    {
      $response = self::whereIn('id', $ids)
                      ->whereIn('type', [2, 3])
                      ->where(['status' => 1])
                      ->orderBy('sort')
                      ->get()
                      ->toArray();

      return $response;
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }



  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 权限与菜单关联函数
   * ------------------------------------------
   *
   * 权限与菜单关联函数
   *
   * @return [关联对象]
   */
  public function permission()
  {
    return $this->hasOne('Role', 'role_id');
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-22
   * ------------------------------------------
   * 菜单本类关联函数
   * ------------------------------------------
   *
   * 菜单无限分类下，使用本类进行关联查询
   *
   * @return [type]
   */
  public function children()
  {
    return $this->hasMany(__CLASS__, 'pid');
  }


}

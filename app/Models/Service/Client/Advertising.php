<?php
namespace App\Models\Service\Client;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-09
 *
 * 广告中心模型类
 */
class Advertising extends Basic
{
  // 表名
  protected $table = 'client_advertising';


  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];


  /**
   * 转换属性类型
   */
  protected $casts = [
    'status' => 'array',
    'type' => 'array',
    'start_time' => 'string',
    'end_time' => 'string',
    'create_time' => 'datetime:Y-m-d H:i:s',
    'update_time' => 'datetime:Y-m-d H:i:s',
  ];



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-22
   * ------------------------------------------
   * 消息类型属性类型转换函数
   * ------------------------------------------
   *
   * 消息类型属性类型转换函数
   *
   * @param int $value [数据库存在的值]
   * @return 消息类型
   */
  public function getTypeAttribute($value)
  {
    $status = [
      self::STATUS_ACTIVE   => '首页广告',
      self::STATUS_INACTIVE => '详情广告',
    ];

    return $status[$value];
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-22
   * ------------------------------------------
   * 消息类型属性类型转换函数
   * ------------------------------------------
   *
   * 消息类型属性类型转换函数
   *
   * @param int $value [数据库存在的值]
   * @return 消息类型
   */
  public function getStartTimeAttribute($value)
  {
    $timestamp = $value / 1000;
    return date('Y-m-d', $timestamp);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-22
   * ------------------------------------------
   * 消息类型属性类型转换函数
   * ------------------------------------------
   *
   * 消息类型属性类型转换函数
   *
   * @param int $value [数据库存在的值]
   * @return 消息类型
   */
  public function getEndTimeAttribute($value)
  {
    $timestamp = $value / 1000;
    return date('Y-m-d', $timestamp);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-23
   * ------------------------------------------
   * 获取消息类型
   * ------------------------------------------
   *
   * 获取消息类型
   *
   * @return [type]
   */
  public static function getType()
  {
    $status = [
      self::STATUS_ACTIVE   => '首页广告',
      self::STATUS_INACTIVE => '详情广告',
    ];

    return $status;
  }

}

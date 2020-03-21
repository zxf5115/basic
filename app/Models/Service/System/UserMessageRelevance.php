<?php
namespace App\Models\Service\System;

use App\Models\Basic;
use Illuminate\Support\Facades\Redis;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-22
 *
 * 系统用户消息模型类
 */
class UserMessageRelevance extends Basic
{
  // 表名
  protected $table = 'system_user_message_relevance';

  // 隐藏的属性
  protected $hidden = [
    'update_time'
  ];

  /**
   * 转换属性类型
   */
  protected $casts = [
    'status' => 'array',
    'create_time' => 'datetime:Y-m-d H:i:s',
    'update_time' => 'datetime:Y-m-d H:i:s',
  ];


  /**
   * 可以被批量赋值的属性
   */
  protected $fillable = ['user_id'];

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-22
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
      self::STATUS_ACTIVE   => '未读',
      self::STATUS_INACTIVE => '已读',
      self::STATUS_DELETE   => '删除',
    ];

    return $status[$value];
  }










  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-28
   * ------------------------------------------
   * 设置为已读消息
   * ------------------------------------------
   *
   * 设置为已读消息
   *
   * @param int $id [消息id]
   */
  public static function setReaded($id)
  {
    try
    {
      $current_user_id = Redis::hget('current_user', 'user_id');

      $model = self::where(['user_id' => $current_user_id]);

      // id 为0时表示为全部已读
      if($id > 0)
      {
        $model->where(['id' => $id]);
      }

      return $model->update(['status' => 2]);
    }
    catch(\Exception $e)
    {
      return self::log($e);
    }
  }






  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-22
   * ------------------------------------------
   * 用户关联函数
   * ------------------------------------------
   *
   * 用户关联函数
   *
   * @return [关联对象]
   */
  public function user()
  {
      return $this->hasOne('App\Models\Service\System\User', 'id', 'user_id');
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-26
   * ------------------------------------------
   * 消息关联函数
   * ------------------------------------------
   *
   * 消息关联函数
   *
   * @return [关联对象]
   */
  public function message()
  {
    return $this->belongsTo('App\Models\Service\System\Message');
  }
}

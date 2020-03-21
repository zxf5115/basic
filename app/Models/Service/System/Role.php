<?php
namespace App\Models\Service\System;

use App\Models\Basic;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-20
 *
 * 系统角色模型类
 */
class Role extends Basic
{
  // 表名
  protected $table = 'system_role';



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-08
   * ------------------------------------------
   * 获取菜单编号
   * ------------------------------------------
   *
   * 获取菜单编号
   *
   * @param array $request 菜单编号
   * @return 菜单编号
   */
  public static function getMenuId($request)
  {
    $response = [];

    foreach($request as $key => $item)
    {
      $response[$key]['menu_id'] = $item;
    }

    return $response;
  }





  /**
   * 获取用户权限
   * @param $adminId
   * @return array
   */
  public static function getRulesByUID($user_id)
  {
    $model = self::getRow(['user_id' => $user_id]);
dd($model);
    $data = [];
    //god组有所有权限
    if(in_array(1, $groupIds)) {
      $rules = Rule::getInstance()->where([
        'status' => 1,
        'deleted' => 0
      ])->get()->toArray();
      foreach ($rules as $rule) {
        $data[$rule['id']] = $rule['name'];
      }

      return $data;
    }

    $groups = Group::getInstance()->whereIn('id', $groupIds)->get()->toArray();

    foreach ($groups as $row) {
      $ruleIds = explode(',' , $row['rules']);
      $rules = Rule::getInstance()->where([
        'status' => 1,
        'deleted' => 0
      ])->whereIn('id', $ruleIds)->get()->toArray();
      foreach ($rules as $rule) {
        $data[$rule['id']] = $rule['name'];
      }
    }

    return $data;
  }



  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-20
   * ------------------------------------------
   * 角色与权限关联函数
   * ------------------------------------------
   *
   * 角色与权限关联函数
   *
   * @return [关联对象]
   */
  public function permission()
  {
      return $this->hasMany('App\Models\Service\System\Permission', 'role_id');
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
      $model->permission()->delete();
    });
  }
}

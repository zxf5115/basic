<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-21
 *
 * 客户中心模型类
 */
class CustomerCenter extends Authenticatable implements JWTSubject
{
  protected $table = 'customer_center';


  // 隐藏的属性
  protected $hidden = [
    'username',
    'password',
    'password_salt',
    'mobile',
    'email',
    // 'failure_time',
    'customer_name',
    'status',
    'create_time',
    'update_time'
  ];


  use Notifiable;


  /**
   * TODO: 后期将客户中心独立出来进行收费与版权控制
   */


  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
      return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
      return [];
  }




  // 关联函数 ------------------------------------------------------

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-08
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
      return $this->hasOne('Permission', 'role_id');
  }
}

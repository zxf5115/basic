<?php
namespace App\Models\Service\System;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-21
 *
 * Token模型类
 */
class Token extends Authenticatable implements JWTSubject
{
  protected $table = 'system_user';

  use Notifiable;

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



}

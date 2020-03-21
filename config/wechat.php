<?php

return [

  /**
   *--------------------------------------------------------------------------
   * AppID and AppSecret configuration
   *--------------------------------------------------------------------------
   *
   * Multiple AppId and AppSecret
   * Usage:
   * - default: new Iwanli\Wxxcx\Wxxcx();
   * - other: new Iwanli\Wxxcx\Wxxcx(config('wxxcx.other'));
   */

  'default' => [
    'appId' => 'wx1bc1843726933192',
    'secret' => '33991375a13f6a35ef7c29b482fbf743',
  ],

  'other' => [
    'appId' => 'your other AppSecret',
    'secret' => 'your other AppSecret',
  ],

  // and more ...

  'common' => [
    'debug' => true,
    'code2sessionUrl' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
  ],

];

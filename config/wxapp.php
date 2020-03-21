<?php

return [
    'appid' => env('WX_APPID', 'wx1bc1843726933192'),

    'secret' => env('WX_SECRET', '33991375a13f6a35ef7c29b482fbf743'),

    'grant_type' => 'authorization_code',

    // api
    'user_info_url' => 'https://api.weixin.qq.com/sns/jscode2session',

    'access_token_api' => 'https://api.weixin.qq.com/cgi-bin/token',

    'msg_sec_check' => 'https://api.weixin.qq.com/wxa/msg_sec_check'
];

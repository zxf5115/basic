<?php
namespace App\Http\Components;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-19
 *
 * 错误码常量类
 */
class Code
{
  // 公共
  const SUCCESS = 200;
  const ERROR = 1000;
  const NO_PERMISSION = 1001;
  const DELETE_SUCCESS = 1002;
  const DELETE_FAILURE = 1003;
  const HANDLE_SUCCESS = 1004;
  const HANDLE_FAILURE = 1005;


  // 服务器
  const SERVER_ERROR        = 2000;
  const USER_EXIST          = 2001;
  const PASSWORD_ERROR      = 2002;
  const ACCESS_RESTRICTIONS = 2003;
  const CUSTOMER_EXIST      = 2004;
  const CUSTOMER_FAILURE    = 2005;
  const TOKEN_ERROR         = 2006;
  const TOKEN_EXPIRED       = 2007;
  const TOKEN_NO_VERIFIED       = 2008;


  // API
  const API_ERROR = 3000;
  const API_UNAUTHORIZED = 3001;


  const DATABASE_ERROR = 4000;


  const CACHE_ERROR = 5000;

  const FILE_UPLOAD_EXIST = 6000;
  const FILE_UPLOAD_FAILURE_RETRY = 6001;
  const FILE_UPLOAD_EXTENSION_ERROR = 6002;
  const FILE_UPLOAD_ERROR = 6003;
  const FILE_UPLOAD_SAVE_ERROR = 6004;
  const FILE_EXIST = 6005;


  const WX_CODE_EXIST = 7001;







  public static $message = [
    self::SUCCESS       => '成功',
    self::ERROR         => '系统错误',
    self::NO_PERMISSION => '没有权限',
    self::DELETE_SUCCESS => '删除成功',
    self::DELETE_FAILURE => '删除失败',
    self::HANDLE_SUCCESS => '操作成功',
    self::HANDLE_FAILURE => '操作失败',



    // 服务器错误
    self::SERVER_ERROR        => '服务器错误',
    self::USER_EXIST          => '用户不存在',
    self::PASSWORD_ERROR      => '请输入正确密码',
    self::ACCESS_RESTRICTIONS => '输错密码次数太多，请一小时后再试！',
    self::CUSTOMER_EXIST      => '客户不存在',
    self::CUSTOMER_FAILURE    => '客户已失效',
    self::TOKEN_ERROR         => 'Token丢失',
    self::TOKEN_EXPIRED       => 'Token失效',
    self::TOKEN_NO_VERIFIED   => 'Token格式错误',


    self::API_ERROR => '接口错误',
    self::API_UNAUTHORIZED => '未授权',

    self::DATABASE_ERROR => '数据库错误',
    self::CACHE_ERROR    => '缓存错误',


    self::FILE_UPLOAD_EXIST           => '上传文件为空',
    self::FILE_UPLOAD_FAILURE_RETRY   => '上传失败，请重试',
    self::FILE_UPLOAD_EXTENSION_ERROR => '文件类型不被允许',
    self::FILE_UPLOAD_ERROR => '上传失败',
    self::FILE_UPLOAD_SAVE_ERROR => '保存文件失败',
    self::FILE_EXIST => '文件不存在',


    self::WX_CODE_EXIST => 'Code丢失',
  ];


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-22
   * ------------------------------------------
   * 组装Code对应显示内容
   * ------------------------------------------
   *
   * 组装Code对应显示内容
   *
   * @param int $code 信息代码
   * @return 信息内容
   */
  public static function message($code)
  {
    return self::$message[$code] ?: self::$message[self::SERVER_ERROR];
  }
}

<?php
namespace App\Models\Service\System;

use Illuminate\Support\Facades\Storage;

use App\Models\Basic;
use App\Http\Components\Code;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-24
 *
 * 文件上传模型
 */
class File extends Basic
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-24
   * ------------------------------------------
   * 上传图片
   * ------------------------------------------
   *
   * 上传图片
   *
   * @param string $name 文件名
   * @param string $path 路径
   * @param boolean $type 是否支持上传服务器，默认不上传
   * @param string $disk 用那种方式上传 oss, cos, qiniu, 又拍云
   * @param array $extension 允许上传的后缀
   * @return [type]
   */
  public static function image($name, $path = 'uploads', $disk = 'public', $extension = [])
  {
    $allowExtension = [
      'jpg',
      'jpeg',
      'png',
      'gif',
      'bmp'
    ];

    if($extension)
      $allowExtension = array_merge($allowExtension, $extension);

    return self::file($name, $path, $disk, $allowExtension);
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-24
   * ------------------------------------------
   * 上传文件
   * ------------------------------------------
   *
   * 上传文件
   *
   * @param string $name 文件名
   * @param string $path 路径
   * @param string $disk 用那种方式上传 oss, cos, qiniu, 又拍云
   * @param array $extension 允许上传的后缀
   * @return [type]
   */
  public static function file($name, $path = 'uploads', $disk = 'public', $allowExtension = [])
  {
    if (!request()->hasFile($name))
    {
      return [
        'status' => Code::FILE_UPLOAD_EXIST,
        'message' => Code::$message[Code::FILE_UPLOAD_EXIST]
      ];
    }

    $file = request()->file($name);
    if(!$file->isValid())
    {
      return [
        'status' => Code::FILE_UPLOAD_FAILURE_RETRY,
        'message' => Code::$message[Code::FILE_UPLOAD_FAILURE_RETRY]
      ];
    }

      // 过滤所有的.符号
    $path = str_replace('.', '', $path);

      // 先去除两边空格
    $path = trim($path, '/');

      // 获取文件后缀
    $extension = strtolower($file->getClientOriginalExtension());

      // 组合新的文件名
    $newName = md5(time()).'.'.$extension;

      // 获取上传的文件名
    $oldName = $file->getClientOriginalName();

    if (!empty($allowExtension) && !in_array($extension, $allowExtension))
    {
      return [
        'status' => Code::FILE_UPLOAD_EXTENSION_ERROR,
        'message' => Code::$message[Code::FILE_UPLOAD_EXTENSION_ERROR]
      ];
    }

    $dir = $path . DIRECTORY_SEPARATOR . date('Y-m-d');

    Storage::disk($disk)->makeDirectory($dir);

    $filename = $dir . DIRECTORY_SEPARATOR . $newName;
    if(Storage::disk($disk)->put($filename, file_get_contents($file)))
    {
      return [
        'status' => Code::SUCCESS,
        'message' => Storage::url($filename)
      ];
    }
    else
    {
      return [
        'status' => Code::FILE_UPLOAD_ERROR,
        'message' => Code::$message[Code::FILE_UPLOAD_ERROR]
      ];
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-24
   * ------------------------------------------
   * 删除文件
   * ------------------------------------------
   *
   * 删除文件
   *
   * @param string $path 路径
   * @param boolean $type 是否是服务器
   * @param [type] $disk 储存方式
   * @return [type]
   */
  public static function delete2($path = null, $type = false, $disk = null)
  {
    if(!$path || (!is_file($path) && !$type))
    {
      return [
        'status' => Code::FILE_EXIST,
        'message' => Code::$message[Code::FILE_EXIST]
      ];
    }

    if(!$type)
    {
      @unlink(public_path($path));
      return [
        'status' => Code::DELETE_SUCCESS,
        'message' => Code::$message[Code::DELETE_SUCCESS]
      ];
    }
    else
    {
      $storage = Storage::disk($disk);

      if(!$storage->exists($path))
      {
        return [
          'status' => Code::FILE_EXIST,
          'message' => Code::$message[Code::FILE_EXIST]
        ];
      }

      if($storage->delete($path))
      {
        return [
          'status' => Code::DELETE_SUCCESS,
          'message' => Code::$message[Code::DELETE_SUCCESS]
        ];
      }
      else
      {
        return [
          'status' => Code::DELETE_FAILURE,
          'message' => Code::$message[Code::DELETE_FAILURE]
        ];
      }
    }
  }
}

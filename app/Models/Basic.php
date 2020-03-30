<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-07
 *
 * 基础模型类
 */
class Basic extends Model
{
  /**
   * 表名
   */
  protected $table = '';


  // 是否由模型去维护时间戳字段，如果我们想手动去维护，可以设置为 false
  public $timestamps = true;


  /**
   * mass assignment 的时候可以批量设置的属性，目的是防止用户提交我们不想更新的字段
   * 注意：
   *      和 $guarded 同时使用的时候， $guard 设置的会无效
   */
  protected $fillable = ['id'];


  /**
   * 默认属性值
   * @var [type]
   */
  protected $attributes = [
    'company_id' => 1
  ];


  /**
   * 其中一个用法，根据现有某几个属性，计算出新属性，并在 模型 toArray 的时候显示
   * usage：
   *      模型里面定义： protected $appends = ['full_name'];
   *      public function getFullNameAttribute() { return $this->firstName . ' ' . $this->lastName; }
   */
  protected $appends = [];


  /**
   * 隐藏的属性，我们调用模型的 toArray 方法的时候不会得到该数组中的属性，
   * 如果需要也得到隐藏属性，可以通过 withHidden 方法
   */
  protected $hidden = [];


  /**
   * 需要进行时间格式转换的字段
   * 应用场景：
   *      一般情况我们只定义了 created_at、updated_at，我们还可能会保存用户注册时间这些，register_time，
   *      这样我们就可以定义，protected $dates = ['register_time'];
   * 好比如：
   *      我们定义的 $dateFormat 为 mysql 的 datetime 格式，我们即使把 register_time 设置为 time(),
   *      实际保存的其实是 datetime 格式的
   */
  protected $dates = [];

  /**
   * created_at、updated_at、$dates数组 进行时间格式转换的时候使用的格式
   * 默认使用 mysql 的 datetime 类型，如果需要更改为 10 位整型，可以设置 protected $dateFormat = 'U';
   */
  protected $dateFormat = 'U';


  /**
   * 创建时间戳字段名称
   */
  const CREATED_AT = 'create_time';

  /**
   * 更新时间戳字段名称
   */
  const UPDATED_AT = 'update_time';


  const STATUS_ACTIVE   = 1;
  const STATUS_INACTIVE = 2;
  const STATUS_START    = 3;
  const STATUS_STOP     = 4;
  const STATUS_DELETE   = -1;


  /**
   * 转换属性类型
   */
  protected $casts = [
    'status' => 'array',
    'last_login_time' => 'datetime:Y-m-d H:i:s',
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
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 获取图片url
   * ------------------------------------------
   *
   * 获取图片url
   *
   * @return [type]
   */
  public function getPictureUrl()
  {
      return asset('uploads/product/'. $this->picture);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 日志输出
   * ------------------------------------------
   *
   * 本地环境下进行日志输出
   *
   * @param \Exception $exception [description]
   * @return [type]
   */
  public static function log(\Exception $exception)
  {
    if('local' == config('app.env'))
    {
      dd($exception);
    }
    else
    {
      return '系统错误';
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-13
   * ------------------------------------------
   * 新增数据
   * ------------------------------------------
   *
   * 公有，新增数据
   *
   * @param [array] $request [请求数据]
   */
  public static function add($request)
  {
    try
    {
      return self::create($request);
    }
    catch(\Exception $e)
    {
      self::log($e);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-13
   * ------------------------------------------
   * 更新数据
   * ------------------------------------------
   *
   * 公有，更新数据
   *
   * @param [array] $request [请求数据]
   */
  public static function edit($request)
  {
    try
    {
      return self::where(['id' => $request['id']])->update($request);
    }
    catch(\Exception $e)
    {
      self::log($e);
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-２8
   * ------------------------------------------
   * 批量更新数据
   * ------------------------------------------
   *
   * 批量更新数据
   *
   * @param [array] $request [请求数据]
   */
  public static function batchEdit($id, $data)
  {
    try
    {
      return self::whereIn('id', $id)->update($data);
    }
    catch(\Exception $e)
    {
      self::log($e);
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-13
   * ------------------------------------------
   * 删除数据
   * ------------------------------------------
   *
   * 公有，删除数据
   *
   * @param [string] $id [请求id]
   */
  public static function remove($id)
  {
    try
    {
      return self::destroy($id);
    }
    catch(\Exception $e)
    {
      self::log($e);
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 单行查询
   * ------------------------------------------
   *
   * 根据查询条件获取一行数据
   *
   * @param array $conditon [查询条件]
   * @param boolean $is_array 是否返回数组
   * @return [对象|数组]
   */
  public static function getRow($conditon, $relevance = false, $is_array = false)
  {
    try
    {
      $response = self::where($conditon);

      if(is_string($relevance))
      {
        $response->relevance = $response->relevance;
      }
      else if(is_array($relevance))
      {
        foreach($relevance as $item)
        {
          $response = $response->with([$item=>function($query){
            $query->where(['status'=>1]);
          }]);
        }
      }

      $response = $response->first();

      if($is_array)
      {
        $response = $response->toArray();
      }

      return $response;
    }
    catch(\Exception $e)
    {
      self::log($e);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 多行查询
   * ------------------------------------------
   *
   * 根据查询条件获取多行数据
   *
   * @param array $conditon [查询条件]
   * @param boolean $is_array 是否返回数组
   * @return [type]
   */
  public static function getList($conditon, $relevance = false, $order = 'create_time', $asc = 'desc', $is_array = false, $limit = false)
  {
    try
    {
      $response = self::where($conditon);

      if(is_string($relevance))
      {
        $response->relevance = $response->relevance;
      }
      else if(is_array($relevance))
      {
        foreach($relevance as $item)
        {
          $response = $response->with([$item=>function($query){
            $query->where(['status'=>1]);
          }]);
        }
      }

      $response = $response->orderBy($order, $asc);

      if($limit)
      {
        $response = $response->limit($limit);
      }

      $response = $response->get();

      if($is_array)
      {
        $response = $response->toArray();
      }

      return $response;
    }
    catch(\Exception $e)
    {
      self::log($e);
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 分页查询
   * ------------------------------------------
   *
   * 根据查询条件获取分页数据
   *
   * @param array $condition 查询条件
   * @param array $relevance 关联查询对象
   * @param boolean $is_array 是否返回数组
   * @param integer $pageSize 分页数量
   * @return 分页数据
   */
  public static function getPaging($condition, $relevance, $orders, $is_array = false, $pageSize = 10)
  {
    try
    {
      $model = self::where($condition);

      if(is_array($relevance))
      {
        foreach($relevance as $item)
        {
          $model = $model->with([$item=>function($query){
            $query->where(['status'=>1]);
          }]);
        }
      }


      foreach($orders as $order)
      {
        $model = $model->orderBy($order['key'], $order['value']);
      }

      $response = $model->paginate($pageSize);

      if($is_array)
      {
        $response = $response->toArray();
      }

      return $response;
    }
    catch(\Exception $e)
    {
      self::log($e);
    }
  }
}

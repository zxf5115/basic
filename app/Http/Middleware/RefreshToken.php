<?php
namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

use App\Http\Components\Code;

// use Auth;
// use Closure;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
// use Tymon\JWTAuth\Exceptions\TokenExpiredException;
// use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

// 注意，我们要继承的是 jwt 的 BaseMiddleware
class RefreshToken extends BaseMiddleware
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-21
   * ------------------------------------------
   * Token 刷新
   * ------------------------------------------
   *
   * Token 刷新
   *
   * @param Object $request 请求参数
   * @param Closure $next 下一步
   * @return [type]
   */
  public function handle($request, Closure $next)
  {
    try
    {
      JWTAuth::parseToken();

      $token = JWTAuth::getToken();

      $request->user = JWTAuth::authenticate($token);

      $customer = $request->user;

      if(is_null($customer))
      {
        return self::error(Code::CUSTOMER_EXIST);
      }

      $failure_time = $customer->failure_time;

      if(time() > $failure_time)
      {
        return self::error(Code::CUSTOMER_FAILURE);
      }
    }
    catch(TokenBlacklistedException $e)
    {
      return self::error(Code::TOKEN_EXPIRED);
    }
    catch(TokenInvalidException $e)
    {
      return self::error(Code::TOKEN_NO_VERIFIED);
    }
    catch (TokenExpiredException $e)
    {
      try
      {
        // 尝试刷新 token
        $token = JWTAuth::refresh($token);

        JWTAuth::setToken($token);

        $request->user = JWTAuth::authenticate($token);

        // 在头部返回新的 token
        return $this->setAuthenticationHeader($next($request), $token);
      }
      catch(TokenBlacklistedException $e)
      {
        return self::error(Code::TOKEN_EXPIRED);
      }
      catch(TokenInvalidException $e)
      {
        return self::error(Code::TOKEN_NO_VERIFIED);
      }
      catch (TokenExpiredException $e)
      {
        return self::error(Code::TOKEN_EXPIRED);
      }
      catch(JWTException $e)
      {
        return self::error(Code::TOKEN_ERROR);
      }
      catch(\Exception $e)
      {
        return self::error(Code::ERROR);
      }
    }
    catch(JWTException $e)
    {
      return self::error(Code::TOKEN_ERROR);
    }
    catch(\Exception $e)
    {
      return self::error(Code::ERROR);
    }

    return $next($request);
  }




  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-19
   * ------------------------------------------
   * 失败信息
   * ------------------------------------------
   *
   * 返回错误信息
   *
   * @param integer $code 错误代码
   * @return 错误信息
   */
  public static function error($code = 1000)
  {
    $headers = ['content-type' => 'application/json'];

    $response = \Response::json(['status' => $code, 'message' => Code::message($code)]);
    return $response->withHeaders($headers);
  }
    // /**
    //  * Handle an incoming request.
    //  *
    //  * @param  \Illuminate\Http\Request $request
    //  * @param  \Closure $next
    //  *
    //  * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
    //  *
    //  * @return mixed
    //  */
    // public function handle($request, Closure $next)
    // {
    //     // 检查此次请求中是否带有 token，如果没有则抛出异常。
    //     $this->checkForToken($request);

    //    // 使用 try 包裹，以捕捉 token 过期所抛出的 TokenExpiredException  异常
    //     try {
    //         // 检测用户的登录状态，如果正常则通过
    //         if ($this->auth->parseToken()->authenticate()) {
    //             return $next($request);
    //         }
    //         throw new UnauthorizedHttpException('jwt-auth', '未登录');
    //     } catch (TokenExpiredException $exception) {
    //       // 此处捕获到了 token 过期所抛出的 TokenExpiredException 异常，我们在这里需要做的是刷新该用户的 token 并将它添加到响应头中
    //         try {
    //             // 刷新用户的 token
    //             $token = $this->auth->refresh();
    //            // 使用一次性登录以保证此次请求的成功
    //             Auth::guard('api')->onceUsingId($this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']);
    //         } catch (JWTException $exception) {
    //            // 如果捕获到此异常，即代表 refresh 也过期了，用户无法刷新令牌，需要重新登录。
    //             throw new UnauthorizedHttpException('jwt-auth', $exception->getMessage());
    //         }
    //     }

    //     // 在响应头中返回新的 token
    //     return $this->setAuthenticationHeader($next($request), $token);
    // }
}

<?php

namespace Yurun\OAuthLogin;

use Yurun\Util\HttpRequest;

abstract class Base
{
    /**
     * http请求类.
     *
     * @var Yurun\Util\HttpRequest
     */
    public $http;

    /**
     * 应用的唯一标识。
     *
     * @var string
     */
    public $appid;

    /**
     * appid对应的密钥.
     *
     * @var string
     */
    public $appSecret;

    /**
     *
     * @var string
     */
    public $callbackUrl;

    /**
     * state值，调用getAuthUrl方法后可以获取到.
     *
     * @var string
     */
    public $state;

    /**
     *
     * @var array
     */
    public $scope;

    /**
     * 接口调用结果.
     *
     * @var array
     */
    public $result;

    /**
     * AccessToken，调用相应方法后可以获取到.
     *
     * @var string
     */
    public $accessToken;

    /**
     * openid，调用相应方法后可以获取到.
     *
     * @var string
     */
    public $openid;

    /**
     *
     * @var string
     */
    public $loginAgentUrl;

    /**
     * 构造方法.
     *
     * @param string $appid       应用的唯一标识
     * @param string $appSecret   appid对应的密钥
     */
    public function __construct($appid = null, $appSecret = null, $callbackUrl = null)
    {
        $this->appid = $appid;
        $this->appSecret = $appSecret;
        $this->callbackUrl = $callbackUrl;
        $this->http = new HttpRequest();
    }

    /**
     * 把jsonp转为php数组.
     *
     * @param string $jsonp jsonp字符串
     *
     * @return array
     */
    public function jsonp_decode($jsonp, $assoc = false)
    {
        $jsonp = trim($jsonp);
        if (isset($jsonp[0]) && '[' !== $jsonp[0] && '{' !== $jsonp[0])
        {
            $begin = strpos($jsonp, '(');
            if (false !== $begin)
            {
                $end = strrpos($jsonp, ')');
                if (false !== $end)
                {
                    $jsonp = substr($jsonp, $begin + 1, $end - $begin - 1);
                }
            }
        }

        return json_decode($jsonp, $assoc);
    }

    /**
     * http_build_query — 生成 URL-encode 之后的请求字符串.
     *
     * @param array  $query_data
     * @param string $numeric_prefix
     * @param string $arg_separator
     * @param int    $enc_type
     *
     * @return void
     */
    public function http_build_query($query_data, $numeric_prefix = '', $arg_separator = '&', $enc_type = \PHP_QUERY_RFC1738)
    {
        return http_build_query($query_data, $numeric_prefix, $arg_separator, $enc_type);
    }

    /**
     * 获取state值
     *
     * @param string $state
     *
     * @return string
     */
    protected function getState($state = null)
    {
        if (null === $state)
        {
            if (null === $this->state)
            {
                $this->state = md5(uniqid('', true));
            }
        }
        else
        {
            $this->state = $state;
        }

        return $this->state;
    }

    /**
     * 检测state是否相等.
     *
     * @param string $storeState 本地存储的正确的state
     * @param string $state      回调传递过来的state
     *
     * @return bool
     */
    public function checkState($storeState, $state = null)
    {
        if (null === $state)
        {
            if (null === $this->state)
            {
                if (isset($_GET['state']))
                {
                    $state = $_GET['state'];
                }
                else
                {
                    $state = '';
                }
            }
            else
            {
                $state = $this->state;
            }
        }

        return $storeState === $state;
    }

    /**
     *
     *
     * @return string
     */
    abstract public function getAuthUrl($callbackUrl = null, $state = null, $scope = null);

    /**
     *
     * @param string $storeState 存储的正确的state
     * @param string $code       第一步里$callbackUrl地址中传过来的code，为null则通过get参数获取
     * @param string $state      回调接收到的state，为null则通过get参数获取
     *
     * @return string
     */
    public function getAccessToken($storeState = '', $code = null, $state = null)
    {
        if (!$this->checkState($storeState, $state))
        {
        }

        return $this->__getAccessToken($storeState, $code, $state);
    }

    /**
     *
     * @param string $storeState 存储的正确的state
     * @param string $code       第一步里$callbackUrl地址中传过来的code，为null则通过get参数获取
     * @param string $state      回调接收到的state，为null则通过get参数获取
     *
     * @return string
     */
    abstract protected function __getAccessToken($storeState, $code = null, $state = null);

    /**
     *
     * @param string $accessToken
     *
     * @return array
     */
    abstract public function getUserInfo($accessToken = null);

    /**
     * 刷新AccessToken续期
     *
     * @param string $refreshToken
     *
     * @return bool
     */
    abstract public function refreshToken($refreshToken);

    /**
     *
     * @param string $accessToken
     *
     * @return bool
     */
    abstract public function validateAccessToken($accessToken = null);

    /**
     *
     * @return void
     */
    public function displayLoginAgent()
    {
        $ref = new \ReflectionClass(static::class);
        echo file_get_contents(\dirname($ref->getFileName()) . '/loginAgent.html');
    }

    /**
     * 获取回调地址
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return null === $this->loginAgentUrl ? $this->callbackUrl : ($this->loginAgentUrl . '?' . http_build_query(['redirect_uri' => $this->callbackUrl]));
    }
}

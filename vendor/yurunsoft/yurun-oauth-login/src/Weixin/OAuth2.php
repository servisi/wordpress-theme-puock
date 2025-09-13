<?php

namespace Yurun\OAuthLogin\Weixin;

use Yurun\OAuthLogin\ApiException;
use Yurun\OAuthLogin\Base;

class OAuth2 extends Base
{
    /**
     * api接口域名.
     */
    const API_DOMAIN = 'https://api.weixin.qq.com/';

    /**
     * 开放平台域名.
     */
    const OPEN_DOMAIN = 'https://open.weixin.qq.com/';

    /**
     * 语言，默认为zh_CN.
     *
     * @var string
     */
    public $lang = 'zh_CN';

    /**
     * openid从哪个字段取，默认为openid.
     *
     * @var int
     */
    public $openidMode = OpenidMode::OPEN_ID;

    /**
     * 获取url地址
     *
     * @param string $name   跟在域名后的文本
     * @param array  $params GET参数
     *
     * @return string
     */
    public function getUrl($name, $params = [])
    {
        if ('http' === substr($name, 0, 4))
        {
            $domain = $name;
        }
        else
        {
            $domain = static::API_DOMAIN . $name;
        }

        return $domain . (empty($params) ? '' : ('?' . $this->http_build_query($params)));
    }

    /**
     *
     *
     * @return string
     */
    public function getAuthUrl($callbackUrl = null, $state = null, $scope = null)
    {
        $option = [
            'appid'				       => $this->appid,
            'redirect_uri'		  => null === $callbackUrl ? (null === $this->callbackUrl ? (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '') : $this->callbackUrl) : $callbackUrl,
            'response_type'		 => 'code',
            'scope'				       => null === $scope ? (null === $this->scope ? 'snsapi_login' : $this->scope) : $scope,
            'state'				       => $this->getState($state),
        ];
        if (null === $this->loginAgentUrl)
        {
            return $this->getUrl(static::OPEN_DOMAIN . 'connect/qrconnect', $option) . '#wechat_redirect';
        }
        else
        {
            return $this->loginAgentUrl . '?' . $this->http_build_query($option);
        }
    }

    /**
     *
     *
     * @return string
     */
    public function getWeixinAuthUrl($callbackUrl = null, $state = null, $scope = null)
    {
        $option = [
            'appid'				       => $this->appid,
            'redirect_uri'		  => null === $callbackUrl ? (null === $this->callbackUrl ? (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '') : $this->callbackUrl) : $callbackUrl,
            'response_type'		 => 'code',
            'scope'				       => null === $scope ? (null === $this->scope ? 'snsapi_userinfo' : $this->scope) : $scope,
            'state'				       => $this->getState($state),
        ];
        if (null === $this->loginAgentUrl)
        {
            return $this->getUrl(static::OPEN_DOMAIN . 'connect/oauth2/authorize', $option) . '#wechat_redirect';
        }
        else
        {
            $option['isMp'] = 1;

            return $this->loginAgentUrl . '?' . $this->http_build_query($option);
        }
    }

    /**
     *
     * @param string $storeState 存储的正确的state
     * @param string $code       第一步里$redirectUri地址中传过来的code，为null则通过get参数获取
     * @param string $state      回调接收到的state，为null则通过get参数获取
     *
     * @return string
     */
    protected function __getAccessToken($storeState, $code = null, $state = null)
    {
        $this->result = $this->http->get($this->getUrl('sns/oauth2/access_token', [
            'appid'			    => $this->appid,
            'secret'		    => $this->appSecret,
            'code'			     => isset($code) ? $code : (isset($_GET['code']) ? $_GET['code'] : ''),
            'grant_type'	 => 'authorization_code',
        ]))->json(true);
        if (isset($this->result['errcode']) && 0 != $this->result['errcode'])
        {
            throw new ApiException($this->result['errmsg'], $this->result['errcode']);
        }
        else
        {
            switch ((int) $this->openidMode)
            {
                case OpenidMode::OPEN_ID:
                    $this->openid = $this->result['openid'];
                    break;
                case OpenidMode::UNION_ID:
                    $this->openid = $this->result['unionid'];
                    break;
                case OpenidMode::UNION_ID_FIRST:
                    $this->openid = empty($this->result['unionid']) ? $this->result['openid'] : $this->result['unionid'];
                    break;
            }

            return $this->accessToken = $this->result['access_token'];
        }
    }

    /**
     *
     * @param string $accessToken
     *
     * @return array
     */
    public function getUserInfo($accessToken = null)
    {
        $this->result = $this->http->get($this->getUrl('sns/userinfo', [
            'access_token'	 => null === $accessToken ? $this->accessToken : $accessToken,
            'openid'		      => $this->openid,
            'lang'			       => $this->lang,
        ]))->json(true);
        if (isset($this->result['errcode']) && 0 != $this->result['errcode'])
        {
            throw new ApiException($this->result['errmsg'], $this->result['errcode']);
        }
        else
        {
            return $this->result;
        }
    }

    /**
     * 刷新AccessToken续期
     *
     * @param string $refreshToken
     *
     * @return bool
     */
    public function refreshToken($refreshToken)
    {
        $this->result = $this->http->get($this->getUrl('sns/oauth2/refresh_token', [
            'appid'			       => $this->appid,
            'grant_type'	    => 'refresh_token',
            'refresh_token'	 => $refreshToken,
        ]))->json(true);

        return isset($this->result['errcode']) && 0 == $this->result['errcode'];
    }

    /**
     *
     * @param string $accessToken
     *
     * @return bool
     */
    public function validateAccessToken($accessToken = null)
    {
        $this->result = $this->http->get($this->getUrl('sns/auth', [
            'access_token'	 => null === $accessToken ? $this->accessToken : $accessToken,
            'openid'		      => $this->openid,
        ]))->json(true);

        return isset($this->result['errcode']) && 0 == $this->result['errcode'];
    }

    /**
     * 调用后可以使用$this->result['openid']或$this->result['unionid']获取相应的值
     *
     * @param string $jsCode
     *
     * @return string
     */
    public function getSessionKey($jsCode)
    {
        $this->result = $this->http->get($this->getUrl('sns/jscode2session', [
            'appid'		    => $this->appid,
            'secret'	    => $this->appSecret,
            'js_code'	   => $jsCode,
            'grant_type' => 'authorization_code',
        ]))->json(true);

        if (isset($this->result['errcode']) && 0 != $this->result['errcode'])
        {
            throw new ApiException($this->result['errmsg'], $this->result['errcode']);
        }
        else
        {
            switch ((int) $this->openidMode)
            {
                case OpenidMode::OPEN_ID:
                    $this->openid = $this->result['openid'];
                    break;
                case OpenidMode::UNION_ID:
                    $this->openid = $this->result['unionid'];
                    break;
                case OpenidMode::UNION_ID_FIRST:
                    $this->openid = empty($this->result['unionid']) ? $this->result['openid'] : $this->result['unionid'];
                    break;
            }
        }

        return $this->result['session_key'];
    }

    /**
     * 解密小程序 wx.getUserInfo() 敏感数据.
     *
     * @param string $encryptedData
     * @param string $iv
     * @param string $sessionKey
     *
     * @return array
     */
    public function descryptData($encryptedData, $iv, $sessionKey)
    {
        if (24 != \strlen($sessionKey))
        {
            throw new \InvalidArgumentException('sessionKey 格式错误');
        }
        if (24 != \strlen($iv))
        {
            throw new \InvalidArgumentException('iv 格式错误');
        }
        $aesKey = base64_decode($sessionKey);
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, 'AES-128-CBC', $aesKey, 1, $aesIV);
        $dataObj = json_decode($result, true);
        if (!$dataObj)
        {
        }

        return $dataObj;
    }
}

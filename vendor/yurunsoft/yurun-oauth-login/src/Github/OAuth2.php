<?php

namespace Yurun\OAuthLogin\Github;

use Yurun\OAuthLogin\ApiException;
use Yurun\OAuthLogin\Base;

class OAuth2 extends Base
{
    /**
     */
    const AUTH_DOMAIN = 'https://github.com/';

    /**
     * api接口域名.
     */
    const API_DOMAIN = 'https://api.github.com/';

    /**
     *
     * @var bool
     */
    public $allowSignup = false;

    /**
     *
     * @param string $name   跟在域名后的文本
     * @param array  $params GET参数
     *
     * @return string
     */
    public function getAuthLoginUrl($name, $params = [])
    {
        return static::AUTH_DOMAIN . $name . (empty($params) ? '' : ('?' . $this->http_build_query($params)));
    }

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
        return static::API_DOMAIN . $name . (empty($params) ? '' : ('?' . $this->http_build_query($params)));
    }

    /**
     *
     *
     * @return string
     */
    public function getAuthUrl($callbackUrl = null, $state = null, $scope = null)
    {
        $option = [
            'client_id'			   => $this->appid,
            'redirect_uri'		 => null === $callbackUrl ? $this->callbackUrl : $callbackUrl,
            'scope'				      => null === $scope ? $this->scope : $scope,
            'state'				      => $this->getState($state),
            'allow_signup'		 => $this->allowSignup,
        ];
        if (null === $this->loginAgentUrl)
        {
            return $this->getAuthLoginUrl('login/oauth/authorize', $option);
        }
        else
        {
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
        $this->result = $this->http->accept('application/json')->get($this->getAuthLoginUrl('login/oauth/access_token', [
            'client_id'			    => $this->appid,
            'client_secret'		 => $this->appSecret,
            'code'				        => isset($code) ? $code : (isset($_GET['code']) ? $_GET['code'] : ''),
            'redirect_uri'		  => $this->getRedirectUri(),
            'state'				       => isset($state) ? $state : (isset($_GET['state']) ? $_GET['state'] : ''),
        ]))->json(true);
        if (isset($this->result['error']))
        {
            throw new ApiException($this->result['error'], 0);
        }
        else
        {
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
        $token = null === $accessToken ? $this->accessToken : $accessToken;
        $this->result = $this->http->ua('YurunOAuthLogin')
            ->header('Authorization', "token {$token}")
            ->get($this->getUrl('user'))
            ->json(true);
        if (isset($this->result['message']))
        {
            throw new ApiException($this->result['message'], 0);
        }
        else
        {
            $this->openid = $this->result['id'];

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
        // 不支持
        return false;
    }

    /**
     *
     * @param string $accessToken
     *
     * @return bool
     */
    public function validateAccessToken($accessToken = null)
    {
        try
        {
            $this->getUserInfo($accessToken);

            return true;
        }
        catch (ApiException $e)
        {
            return false;
        }
    }
}

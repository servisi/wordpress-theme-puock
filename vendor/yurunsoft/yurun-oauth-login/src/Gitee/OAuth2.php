<?php

namespace Yurun\OAuthLogin\Gitee;

use Yurun\OAuthLogin\ApiException;
use Yurun\OAuthLogin\Base;

class OAuth2 extends Base
{
    /**
     * api域名.
     */
    const API_DOMAIN = 'https://gitee.com/';

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
     * @return void
     */
    public function login($username, $password, $scope = 'user_info')
    {
        $response = $this->http->post($this->getUrl('oauth/token'), [
            'grant_type'	    => 'password',
            'username'		     => $username,
            'password'		     => $password,
            'client_id'		    => $this->appid,
            'client_secret'	 => $this->appSecret,
            'scope'			       => $scope,
        ]);
        $this->result = $response->json(true);
        if (!isset($this->result['error']))
        {
            return $this->accessToken = $this->result['access_token'];
        }
        else
        {
            throw new ApiException(isset($this->result['error_description']) ? $this->result['error_description'] : '', $response->httpCode());
        }
    }

    /**
     *
     * @param array  $scope       无用
     *
     * @return string
     */
    public function getAuthUrl($callbackUrl = null, $state = null, $scope = null)
    {
        $option = [
            'client_id'			    => $this->appid,
            'redirect_uri'		  => null === $callbackUrl ? $this->callbackUrl : $callbackUrl,
            'response_type'		 => 'code',
            'state'				       => $this->getState($state),
        ];
        if (null === $this->loginAgentUrl)
        {
            return $this->getUrl('oauth/authorize', $option);
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
        $response = $this->http->post($this->getUrl('oauth/token'), [
            'grant_type'	    => 'authorization_code',
            'code'			        => isset($code) ? $code : (isset($_GET['code']) ? $_GET['code'] : ''),
            'client_id'		    => $this->appid,
            'redirect_uri'	  => $this->getRedirectUri(),
            'client_secret'	 => $this->appSecret,
        ]);
        $this->result = $response->json(true);
        if (!isset($this->result['error']))
        {
            return $this->accessToken = $this->result['access_token'];
        }
        else
        {
            throw new ApiException(isset($this->result['error_description']) ? $this->result['error_description'] : '', $response->httpCode());
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
        $response = $this->http->get($this->getUrl('api/v5/user', [
            'access_token'	 => null === $accessToken ? $this->accessToken : $accessToken,
        ]));
        $this->result = $response->json(true);
        if (isset($this->result['id']))
        {
            $this->openid = $this->result['id'];

            return $this->result;
        }
        else
        {
            throw new ApiException(isset($this->result['message']) ? $this->result['message'] : '', $response->httpCode());
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
        $response = $this->http->post($this->getUrl('oauth/token', [
            'grant_type'	 => 'refresh_token',
            'refresh_token'	 => $refreshToken,
        ]));
        $this->result = $response->json(true);

        if (!isset($this->result['error']))
        {
            return $this->accessToken = $this->result['access_token'];
        }
        else
        {
            throw new ApiException(isset($this->result['error_description']) ? $this->result['error_description'] : '', $response->httpCode());
        }
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

<?php

namespace Yurun\OAuthLogin\Coding;

use Yurun\OAuthLogin\ApiException;
use Yurun\OAuthLogin\Base;

class OAuth2 extends Base
{
    /**
     * api域名.
     */
    const API_DOMAIN = 'https://coding.net/';

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
     * @param string $state       coding无用
     *
     * @return string
     */
    public function getAuthUrl($callbackUrl = null, $state = null, $scope = null)
    {
        $option = [
            'client_id'			    => $this->appid,
            'redirect_uri'		  => null === $callbackUrl ? $this->callbackUrl : $callbackUrl,
            'response_type'		 => 'code',
            'scope'				       => $scope,
        ];
        if (null === $this->loginAgentUrl)
        {
            return $this->getUrl('oauth_authorize.html', $option);
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
        $this->result = $this->http->get($this->getUrl('api/oauth/access_token'), [
            'client_id'		    => $this->appid,
            'client_secret'	 => $this->appSecret,
            'grant_type'	    => 'authorization_code',
            'code'			        => isset($code) ? $code : (isset($_GET['code']) ? $_GET['code'] : ''),
        ])->json(true);
        if ($this->isSuccess($this->result))
        {
            return $this->accessToken = $this->result['access_token'];
        }
        else
        {
            throw new ApiException($this->getErrorCode($this->result), $this->getErrorMsg($this->result));
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
        $this->result = $this->http->get($this->getUrl('api/account/current_user', [
            'access_token'	 => null === $accessToken ? $this->accessToken : $accessToken,
        ]))->json(true);
        if ($this->isSuccess($this->result))
        {
            $this->openid = $this->result['data']['global_key'];

            return $this->result;
        }
        else
        {
            throw new ApiException($this->getErrorCode($this->result), $this->getErrorMsg($this->result));
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

    public function isSuccess($result)
    {
        return !isset($result['code']) || 0 == $result['code'];
    }

    public function getErrorCode($result)
    {
        if (isset($result['msg']))
        {
            $keys = array_keys($result['msg']);

            return isset($keys[0]) ? $keys[0] : '';
        }
    }

    public function getErrorMsg($result)
    {
        if (isset($result['msg']))
        {
            $values = array_values($result['msg']);

            return isset($values[0]) ? $values[0] : '';
        }
    }
}

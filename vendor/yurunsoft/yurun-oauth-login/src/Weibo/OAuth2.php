<?php

namespace Yurun\OAuthLogin\Weibo;

use Yurun\OAuthLogin\ApiException;
use Yurun\OAuthLogin\Base;

class OAuth2 extends Base
{
    /**
     * api域名.
     */
    const API_DOMAIN = 'https://api.weibo.com/';

    /**
     * 当display=mobile时，使用该域名.
     */
    const API_MOBILE_DOMAIN = 'https://open.weibo.cn/';

    /**
     *
     * @var string
     */
    public $display;

    /**
     *
     * @var bool
     */
    public $forcelogin = false;

    /**
     *
     * @var string
     */
    public $language;

    /**
     *
     * @var string
     */
    public $screenName;

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
     * 获取display=mobile时的url地址
     *
     * @param string $name   跟在域名后的文本
     * @param array  $params GET参数
     *
     * @return string
     */
    public function getMobileUrl($name, $params)
    {
        return static::API_MOBILE_DOMAIN . $name . (empty($params) ? '' : ('?' . $this->http_build_query($params)));
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
            'scope'				      => $scope,
            'state'				      => $this->getState($state),
            'display'			     => $this->display,
            'forcelogin'		   => $this->forcelogin,
            'language'			    => $this->language,
        ];
        if (null === $this->loginAgentUrl)
        {
            if ('mobile' === $this->display)
            {
                return $this->getMobileUrl('oauth2/authorize', $option);
            }
            else
            {
                return $this->getUrl('oauth2/authorize', $option);
            }
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
        $this->result = $this->http->post($this->getUrl('oauth2/access_token'), [
            'client_id'		    => $this->appid,
            'client_secret'	 => $this->appSecret,
            'grant_type'	    => 'authorization_code',
            'code'			        => isset($code) ? $code : (isset($_GET['code']) ? $_GET['code'] : ''),
            'redirect_uri'	  => $this->getRedirectUri(),
        ])->json(true);
        if (isset($this->result['error_code']))
        {
            throw new ApiException($this->result['error'], $this->result['error_code']);
        }
        else
        {
            $this->openid = $this->result['uid'];

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
        $this->result = $this->http->get($this->getUrl('2/users/show.json', [
            'access_token'	 => null === $accessToken ? $this->accessToken : $accessToken,
            'uid'			        => $this->openid,
            'screenName'	   => $this->screenName,
        ]))->json(true);
        if (isset($this->result['error_code']))
        {
            throw new ApiException($this->result['error'], $this->result['error_code']);
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
        // 微博不支持刷新
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
        $this->result = $this->http->post($this->getUrl('oauth2/get_token_info'), [
            'access_token'	 => null === $accessToken ? $this->accessToken : $accessToken,
        ])->json(true);
        if (isset($this->result['error_code']))
        {
            throw new ApiException($this->result['error'], $this->result['error_code']);
        }
        else
        {
            return $this->result['expire_in'] > 0;
        }
    }
}

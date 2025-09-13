<?php

namespace Yurun\OAuthLogin\GitLab;

use Yurun\OAuthLogin\ApiException;
use Yurun\OAuthLogin\Base;

class OAuth2 extends Base
{
    /**
     */
    public $authDomain = 'https://gitlab.com/';

    /**
     * api域名.
     */
    public $apiDomain = 'https://gitlab.com/api/v4/';

    /**
     *
     * @param string $domain
     * @return $this
     */
    public function setAuthDomain(string $domain)
    {
        $this->authDomain = $domain;

        return $this;
    }

    /**
     * 设置api域名.
     *
     * @param string $domain
     * @return $this
     */
    public function setApiDomain(string $domain)
    {
        $this->apiDomain = $domain;

        return $this;
    }

    /**
     *
     * @param string $name 跟在域名后的文本
     * @param array $params GET参数
     *
     * @return string
     */
    public function getAuthLoginUrl($name, $params = [])
    {
        return $this->authDomain . $name . (empty($params) ? '' : ('?' . $this->http_build_query($params)));
    }

    /**
     * 获取url地址
     *
     * @param string $name 跟在域名后的文本
     * @param array $params GET参数
     *
     * @return string
     */
    public function getUrl($name, $params = [])
    {
        return $this->apiDomain . $name . (empty($params) ? '' : ('?' . $this->http_build_query($params)));
    }

    /**
     *
     *
     * @return string
     */
    public function getAuthUrl($callbackUrl = null, $state = null, $scope = null)
    {
        $option = [
            'client_id' => $this->appid,
            'redirect_uri' => null === $callbackUrl ? $this->callbackUrl : $callbackUrl,
            'response_type' => 'code',
            'state' => $this->getState($state),
            'scope' => null === $scope ? $this->scope : $scope,
        ];
        if (null === $this->loginAgentUrl) {
            return $this->getAuthLoginUrl('oauth/authorize', $option);
        } else {
            return $this->loginAgentUrl . '?' . $this->http_build_query($option);
        }
    }

    /**
     *
     * @param string $storeState 存储的正确的state
     * @param string $code 第一步里$redirectUri地址中传过来的code，为null则通过get参数获取
     * @param string $state 回调接收到的state，为null则通过get参数获取
     *
     * @return string
     */
    protected function __getAccessToken($storeState, $code = null, $state = null)
    {
        $response = $this->http->post($this->getAuthLoginUrl('oauth/token'), [
            'client_id' => $this->appid,
            'client_secret' => $this->appSecret,
            'code' => isset($code) ? $code : (isset($_GET['code']) ? $_GET['code'] : ''),
            'redirect_uri' => $this->getRedirectUri(),
            'state' => isset($state) ? $state : (isset($_GET['state']) ? $_GET['state'] : ''),
            'grant_type' => 'authorization_code',
        ]);
        $this->result = $response->json(true);
        if (isset($this->result['error_description'])) {
            throw new ApiException($this->result['error_description'], 0);
        } else {
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
        $this->result = $this->http->header('Authorization', "Bearer {$token}")
            ->get($this->getUrl('user'))
            ->json(true);
        if (isset($this->result['error_description'])) {
            throw new ApiException($this->result['error_description'], 0);
        } else {
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
        $response = $this->http->post($this->getAuthLoginUrl('oauth/token'), [
            'client_id' => $this->appid,
            'client_secret' => $this->appSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
            'redirect_uri' => $this->getRedirectUri(),
        ]);
        $this->result = $response->json(true);
        if (isset($this->result['error_description'])) {
            throw new ApiException($this->result['error_description'], 0);
        } else {
            return $this->accessToken = $this->result['access_token'];
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
        try {
            $this->getUserInfo($accessToken);

            return true;
        } catch (ApiException $e) {
            return false;
        }
    }
}

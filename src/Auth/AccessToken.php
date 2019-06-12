<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/5
 * Time: 9:20 AM
 */

namespace EasySmartProgram\Auth;

use EasySmartProgram\Support\Component;

/**
 * Class AccessToken
 * @package EasySmartProgram\Auth
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class AccessToken extends Component
{
    /**
     * @var string
     */
    protected $endpointToGetToken = 'https://openapi.baidu.com/oauth/2.0/token';

    /**
     * @var string
     */
    protected $cachePrefix = 'easy-smart-program.access_token.';

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'grant_type' => 'client_credentials',
            'client_id' => $this->app['config']['app_key'],
            'client_secret' => $this->app['config']['app_secret'],
            'scope' => 'smartapp_snsapi_base',
        ];
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->cachePrefix . md5(json_encode($this->getCredentials()));
    }

    /**
     * @return array|null
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function accessTokenRequest()
    {
        $key = $this->getCacheKey();
        if ($this->cache()->has($key)) {
            return $this->cache()->get($key);
        }

        $response = $this->http()->request(
            $this->endpointToGetToken,
            'POST',
            ['base_uri' => '', 'form_params' => $this->getCredentials(), 'without_token' => true]
        )->toArray();

        $this->cache()->set($key, $response);

        return $response;
    }

    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAccessToken()
    {
        $response = $this->accessTokenRequest();
        return $response['access_token'] ?? null;
    }

    /**
     * @return array
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getQuery()
    {
        return ['access_token' => $this->getAccessToken()];
    }
}
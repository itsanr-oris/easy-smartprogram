<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/5
 * Time: 9:20 AM
 */

namespace EasySmartProgram\Auth;

use EasySmartProgram\Support\Component;
use EasySmartProgram\Support\Http\ResponseHandler;

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
            'client_secret' => $this->app['config']['secret_key'],
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
     * @param bool $refresh
     * @return array|null
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function accessTokenRequest($refresh = false)
    {
        $key = $this->getCacheKey();
        if (!$refresh && $this->cache()->has($key)) {
            return $this->cache()->get($key);
        }

        $client = $this->http();
        $client->withoutAccessToken();
        $client->setResponseType(ResponseHandler::TYPE_ARRAY);

        $response = $client->request(
            $this->endpointToGetToken,
            'POST',
            ['base_uri' => '', 'form_params' => $this->getCredentials()]
        );


        $this->cache()->set($key, $response);

        return $response;
    }

    /**
     * @param bool $refresh
     * @return mixed|null
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAccessToken($refresh = false)
    {
        $response = $this->accessTokenRequest($refresh);
        return $response['access_token'] ?? null;
    }

    /**
     * @return array
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getQuery()
    {
        return ['access_token' => $this->getAccessToken()];
    }
}
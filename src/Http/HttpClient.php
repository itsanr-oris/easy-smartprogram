<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/18
 * Time: 10:45 AM
 */

namespace EasySmartProgram\Http;

use EasySmartProgram\Application;
use EasySmartProgram\Support\Http\Response;

/**
 * Class HttpClient
 * @package EasySmartProgram\Http
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class HttpClient extends \EasySmartProgram\Support\Http\HttpClient
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var bool
     */
    protected $withAccessToken = true;

    /**
     * HttpClient constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        parent::__construct($app->config['http_client'] ?? []);
    }

    /**
     * @return $this
     */
    public function withAccessToken()
    {
        $this->withAccessToken = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function withoutAccessToken()
    {
        $this->withAccessToken = false;
        return $this;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     * @return Response
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $url, $method = 'GET', $options = []): Response
    {
        if ($this->withAccessToken && empty($options['query']['access_token'])) {
            $options['query']['access_token'] = $this->app->access_token->getAccessToken();
        }

        return $this->handleResponse(parent::request($url, $method, $options));
    }

    /**
     * @param Response $response
     * @return Response
     */
    protected function handleResponse(Response $response)
    {
        // reset next request with access token
        $this->withAccessToken();

        return $response;
    }
}
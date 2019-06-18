<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/13
 * Time: 3:55 PM
 */

namespace EasySmartProgram\Tests;

use EasySmartProgram\Application;
//use EasySmartProgram\Support\Http\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

/**
 * Class TestCase
 * @package EasySmartProgram\Tests
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class TestCase extends \EasySmartProgram\Support\Test\TestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var MockHandler
     */
    protected $mockHandler;

    /**
     * @var array
     */
    protected $historyRequest = [];

    /**
     * set up test environment
     *
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->historyRequest = [];
        $this->mockHandler = new MockHandler();
        $this->app = new Application(require __DIR__ . '/../config.example.php');

        $handlerStack = $this->app->http_client->getHandlerStack();
        $handlerStack->setHandler($this->mockHandler);
        $handlerStack->push(Middleware::history($this->historyRequest));
        $this->app->http_client->setHandlerStack($handlerStack);

        $this->setUpAccessToken();
    }

    /**
     * @return Application
     */
    protected function app()
    {
        return $this->app;
    }

    /**
     * @param int $code
     * @param array $headers
     * @param string $body
     * @param string $version
     * @param string|null $reason
     * @return $this
     */
    protected function appendResponse(
        int $code = 200,
        array $headers = [],
        string $body = '',
        string $version = '1.1',
        string $reason = null
    ) {
        $this->mockHandler->append(new Response($code, $headers, $body, $version, $reason));
        return $this;
    }

    /**
     * @return array
     */
    public function historyRequest()
    {
        return $this->historyRequest;
    }

    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     */
    public function setUpAccessToken()
    {
        $data = [
            'access_token' => 'access_token',
            'expires_in' => 0,
            'refresh_token' => 'refresh_token',
            'scope' => 'scope',
            'session_key' => 'session_key',
            'session_secret' => 'session_secret',
        ];

        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        $this->app()->access_token->getAccessToken(true);
        $this->historyRequest = [];
    }
}
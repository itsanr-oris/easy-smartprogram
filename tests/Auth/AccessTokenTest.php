<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/18
 * Time: 10:00 AM
 */

namespace EasySmartProgram\Tests\Auth;

use EasySmartProgram\Tests\TestCase;

/**
 * Class AccessTokenTest
 * @package EasySmartProgram\Tests\Auth
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class AccessTokenTest extends TestCase
{
    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testGetAccessToken()
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

        $this->assertSame('access_token', $this->app()->access_token->getAccessToken(true));
        $this->assertSame('access_token', $this->app()->access_token->getAccessToken());
    }

    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidConfigException
     * @throws \EasySmartProgram\Support\Exception\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function testFormatAccessTokenToQueryParam()
    {
        $params = $this->app()->access_token->getQuery();
        $this->assertTrue(isset($params['access_token']));
    }
}
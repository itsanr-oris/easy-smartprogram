<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/17
 * Time: 4:01 PM
 */

namespace EasySmartProgram\Tests\Auth;

use EasySmartProgram\Support\Http\Response;
use EasySmartProgram\Tests\TestCase;
use GuzzleHttp\Psr7\Request;

/**
 * Class AuthTest
 * @package EasySmartProgram\Tests\Auth
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class AuthTest extends TestCase
{
    /**
     * @var string
     */
    protected $endPoint = 'https://spapi.baidu.com/oauth/jscode2sessionkey';

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSession()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            '{"openid":"test_openid","session_key":"test_session_key"}'
        );

        $response = $this->app()->auth->session('test_session_code');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($this->historyRequest());
        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $url = call_user_func_array([$request, 'getUri'], []);
        $this->assertTrue(strpos($url, $this->endPoint) !== false);

        $query = [];
        parse_str($url->getQuery(), $query);

        $expectParams = [
            'code' => 'test_session_code',
            'client_id' => $this->app()->config['app_key'],
            'sk' => $this->app()->config['secret_key'],
        ];

        foreach ($expectParams as $key => $value) {
            $this->assertTrue(!empty($query[$key]) && $query[$key] == $value);
        }
    }
}
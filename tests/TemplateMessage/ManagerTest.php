<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/18
 * Time: 2:04 PM
 */

namespace EasySmartProgram\Tests\TemplateMessage;

use EasySmartProgram\Support\Http\Response;
use EasySmartProgram\Tests\TestCase;

/**
 * Class ClientTest
 * @package EasySmartProgram\Tests\TemplateMessage
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ManagerTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testListLibraryTemplate()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'errno' => 0,
                'msg' => 'success',
                'data' => [
                    'list' => [
                        ['id' => 'BD001', 'title' => '订单支付通知'],
                        ['id' => 'BD002', 'title' => '购买成功通知'],
                    ],
                    'total_count' => 2293,
                ],
            ])
        );

        $response = $this->app()->template_message->list(0, 2);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $url = call_user_func_array([$request, 'getUri'], []);
        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/template/librarylist';
        $this->assertTrue(strpos($url, $endPoint) !== false);

        $query = [];
        parse_str($url->getQuery(), $query);
        $this->assertTrue(isset($query['access_token']) && 'access_token' == $query['access_token']);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetLibraryTemplateDetail()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'errno' => 0,
                'msg' => 'success',
                'data' => [
                    'id' => 'BD0001',
                    'title' => '订单支付成功通知',
                    'keyword_count' => 2,
                    'keyword_list' => [
                        ['keyword_id' => '1', 'name' => '单号', 'example' => '123456'],
                        ['keyword_id' => '2', 'name' => '金额', 'example' => '30元'],
                    ],
                ],
            ])
        );

        $response = $this->app()->template_message->get('BD0001');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $url = call_user_func_array([$request, 'getUri'], []);
        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/template/libraryget';
        $this->assertTrue(strpos($url, $endPoint) !== false);

        $query = [];
        parse_str($url->getQuery(), $query);
        $this->assertTrue(isset($query['access_token']) && $query['access_token'] == 'access_token');

        $form = [];
        parse_str(call_user_func_array([$request, 'getBody'], []), $form);
        $this->assertTrue(isset($form['id']) && $form['id'] == 'BD0001');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testAddUserAccountTemplate()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'errno' => 0,
                'msg' => 'success',
                'data' => [
                    'template_id' => 'f34178cd598201d9dc8d5c88cd87b44cf7cd0e62NwmP',
                ],
            ])
        );

        $response = $this->app()->template_message->add('BD0001', [1,2,3]);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $url = call_user_func_array([$request, 'getUri'], []);
        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/template/templateadd';
        $this->assertTrue(strpos($url, $endPoint) !== false);

        $query = [];
        parse_str($url->getQuery(), $query);
        $this->assertTrue(isset($query['access_token']) && $query['access_token'] == 'access_token');

        $form = [];
        parse_str(call_user_func_array([$request, 'getBody'], []), $form);
        $this->assertTrue(isset($form['id']) && $form['id'] == 'BD0001');
        $this->assertTrue(isset($form['id']) && $form['keyword_id_list'] == json_encode([1,2,3]));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteUserAccountTemplate()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'errno' => 0,
                'msg' => 'success',
                'data' => [],
            ])
        );

        $response = $this->app()->template_message->delete('template_id');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $url = call_user_func_array([$request, 'getUri'], []);
        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/template/templatedel';
        $this->assertTrue(strpos($url, $endPoint) !== false);

        $query = [];
        parse_str($url->getQuery(), $query);
        $this->assertTrue(isset($query['access_token']) && $query['access_token'] == 'access_token');

        $form = [];
        parse_str(call_user_func_array([$request, 'getBody'], []), $form);
        $this->assertTrue(isset($form['template_id']) && $form['template_id'] == 'template_id');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testListUserAccountTemplate()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'errno' => 0,
                'msg' => 'success',
                'data' => [
                    'total_count' => 1,
                    'list' => [
                        [
                            'template_id' => 'e4313219538c4b0262e3a14a0507000e8bd79e9PTPAz',
                            'title' => '成功续费通知',
                            'content' => '购买时间{{keyword1.DATA}}\n物品名称{{keyword2.DATA}}',
                            'example' => '购买时间: 2016年6月6日\n物品名称: 奶茶',
                        ]
                    ],
                ],
            ])
        );

        $response = $this->app()->template_message->getTemplates(0, 2);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $url = call_user_func_array([$request, 'getUri'], []);
        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/template/templatelist';
        $this->assertTrue(strpos($url, $endPoint) !== false);

        $query = [];
        parse_str($url->getQuery(), $query);
        $this->assertTrue(isset($query['access_token']) && $query['access_token'] == 'access_token');
    }

    /**
     * @throws \EasySmartProgram\Support\Exception\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSendTemplateMessage()
    {
        $message = [
            'template_id' => 'template_id',
            'touser_openId' => 'openid',
            'scene_id' => 'scene_id',
            'scene_type' => 1,
            'data' => [
                'keyword1' => [
                    'value' => 'value_1',
                ],
            ],
        ];

        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'errno' => 0,
                'msg' => 'success',
                'data' => [
                    'msg_key' => 158,
                ]
            ])
        );

        $response = $this->app()->template_message->send($message);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $url = call_user_func_array([$request, 'getUri'], []);
        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/template/send?access_token';
        $this->assertTrue(strpos($url, $endPoint) !== false);

        $query = [];
        parse_str($url->getQuery(), $query);
        $this->assertTrue(isset($query['access_token']) && $query['access_token'] == 'access_token');

        $form = [];
        parse_str(call_user_func_array([$request, 'getBody'], []), $form);
        $this->assertTrue(isset($form['touser_openId']) && $form['touser_openId'] == $message['touser_openId']);

        foreach ($message as $key => $value) {
            if ($key == 'data') {
                $this->assertTrue(isset($form[$key]) && $form[$key] == json_encode($message[$key]));
                continue;
            }

            $this->assertTrue(isset($form[$key]) && $form[$key] == $message[$key]);
        }
    }
}
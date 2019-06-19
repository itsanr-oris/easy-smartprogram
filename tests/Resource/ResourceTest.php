<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/19
 * Time: 9:17 AM
 */

namespace EasySmartProgram\Tests\Resource;

use EasySmartProgram\Support\Http\Response;
use EasySmartProgram\Tests\TestCase;
use GuzzleHttp\Psr7\Request;

/**
 * Class ResourceTest
 * @package EasySmartProgram\Tests\Resource
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class ResourceTest extends TestCase
{
    /**
     * @var array
     */
    protected $submitField = [
        'title', 'body', 'path', 'mapp_type', 'mapp_sub_type', 'feed_type', 'feed_sub_type', 'tags', 'ext', 'images'
    ];

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSubmitResource()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['errno' => 0, 'msg' => 'success', 'data' => ''])
        );

        $response = $this->app()->resource->submit([]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/access/submitresource';
        $this->assertTrue(strpos(call_user_func_array([$request, 'getUri'], []), $endPoint) !== false);

        $form = [];
        parse_str(call_user_func_array([$request, 'getBody'], []), $form);

        foreach ($this->submitField as $field) {
            $this->assertTrue(isset($form[$field]) && $form[$field] == '');
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteResource()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([])
        );

        $path = '/pages/index/index';
        $response = $this->app()->resource->delete($path);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/access/deleteresource';
        $this->assertTrue(strpos(call_user_func_array([$request, 'getUri'], []), $endPoint) !== false);

        $form = [];
        parse_str(call_user_func_array([$request, 'getBody'], []), $form);

        $this->assertTrue(isset($form['app_id']) && $form['app_id'] == $this->app()->config['app_id']);
        $this->assertTrue(isset($form['path']) && $form['path'] == $path);
    }
}
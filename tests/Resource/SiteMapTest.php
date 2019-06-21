<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/19
 * Time: 8:59 AM
 */

namespace EasySmartProgram\Tests\Resource;

use EasySmartProgram\Resource\SiteMap;
use EasySmartProgram\Tests\TestCase;
use GuzzleHttp\Psr7\Request;

/**
 * Class SiteMapTest
 * @package EasySmartProgram\Tests\Resource
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class SiteMapTest extends TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSubmitSiteMap()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([])
        );

        $url = 'http://localhost/site-map.json';
        $desc = 'test submit site-map';
        $this->app()->site_map->submit($url, $desc);

        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/access/submitsitemap';
        $this->assertTrue(strpos(call_user_func_array([$request, 'getUri'], []), $endPoint) !== false);

        $form = [];
        parse_str(call_user_func_array([$request, 'getBody'], []), $form);

        $this->assertTrue(isset($form['app_id']) && $form['app_id'] == $this->app()->config['app_id']);
        $this->assertTrue(isset($form['url']) && $form['url'] == $url);
        $this->assertTrue(isset($form['desc']) && $form['desc'] == $desc);
        $this->assertTrue(isset($form['type']) && $form['type'] == SiteMap::SITE_MAP_TYPE_INCREMENT);
        $this->assertTrue(isset($form['frequency']) && $form['frequency'] == SiteMap::UPDATE_FREQUENCY_DAILY);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteSiteMap()
    {
        $this->appendResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([])
        );

        $url = 'http://localhost/site-map.json';
        $this->app()->site_map->delete($url);

        $this->assertCount(1, $this->historyRequest());

        $request = $this->historyRequest()[0]['request'];
        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('POST', call_user_func_array([$request, 'getMethod'], []));

        $endPoint = 'https://openapi.baidu.com/rest/2.0/smartapp/access/deletesitemap';
        $this->assertTrue(strpos(call_user_func_array([$request, 'getUri'], []), $endPoint) !== false);

        $form = [];
        parse_str(call_user_func_array([$request, 'getBody'], []), $form);

        $this->assertTrue(isset($form['app_id']) && $form['app_id'] == $this->app()->config['app_id']);
        $this->assertTrue(isset($form['url']) && $form['url'] == $url);
    }
}
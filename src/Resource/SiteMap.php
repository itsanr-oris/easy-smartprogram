<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/18
 * Time: 5:06 PM
 */

namespace EasySmartProgram\Resource;

use EasySmartProgram\Support\Component;

/**
 * Class SiteMap
 * @package EasySmartProgram\Resource
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class SiteMap extends Component
{
    /**
     * site map type constant
     */
    const SITE_MAP_TYPE_OFFLINE = 0;
    const SITE_MAP_TYPE_INCREMENT = 1;

    /**
     * site map update frequency constant
     */
    const UPDATE_FREQUENCY_DAILY = 3;
    const UPDATE_FREQUENCY_WEEKLY = 4;
    const UPDATE_FREQUENCY_MONTHLY = 5;
    const UPDATE_FREQUENCY_YEARLY = 6;

    /**
     * @var string
     */
    protected $submitEndPoint = 'access/submitsitemap';
    protected $deleteEndPoint = 'access/deletesitemap';

    /**
     * @param string $url
     * @param string $desc
     * @param int    $type
     * @param int    $frequency
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submit(
        string $url,
        string $desc,
        int $type = self::SITE_MAP_TYPE_INCREMENT,
        int $frequency = self::UPDATE_FREQUENCY_DAILY
    ) {
        $appId = [
            'app_id' => $this->app->config['app_id']
        ];

        return $this->http()->post(
            $this->submitEndPoint,
            array_merge($appId, compact('url', 'desc', 'type', 'frequency')
        ));
    }

    /**
     * @param string $url
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $url)
    {
        $appId = [
            'app_id' => $this->app->config['app_id']
        ];
        return $this->http()->post($this->deleteEndPoint, array_merge($appId, compact( 'url')));
    }
}
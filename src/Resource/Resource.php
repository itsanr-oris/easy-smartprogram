<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/18
 * Time: 4:50 PM
 */

namespace EasySmartProgram\Resource;

use EasySmartProgram\Support\Component;

/**
 * Class Resource
 * @package EasySmartProgram\Resource
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Resource extends Component
{
    /**
     * @var string
     */
    protected $submitEndPoint = 'access/submitresource';
    protected $deleteEndPoint = 'access/deleteresource';

    /**
     * @var array
     */
    protected $format = [
        'title', 'body', 'path', 'mapp_type', 'mapp_sub_type', 'feed_type', 'feed_sub_type', 'tags', 'ext', 'images'
    ];

    /**
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function submit(array $data)
    {
        foreach ($this->format as $key) {
            empty($data[$key]) && $data[$key] = '';
            is_array($data[$key]) && $data[$key] = json_encode($data[$key]);
        }

        $appId = [
            'app_id' => $this->app->config['app_id']
        ];
        return $this->http()->post($this->submitEndPoint, array_merge($appId, $data));
    }

    /**
     * @param string $path
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $path)
    {
        $appId = [
            'app_id' => $this->app->config['app_id']
        ];
        return $this->http()->post($this->deleteEndPoint, array_merge($appId, compact( 'path')));
    }
}
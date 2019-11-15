<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/1
 * Time: 9:49 AM
 */

namespace EasySmartProgram\Auth;

use EasySmartProgram\Support\Component;

/**
 * Class Client
 * @package EasySmartProgram\Auth
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Auth extends Component
{
    /**
     * @var string
     */
    protected $endPoint = 'https://spapi.baidu.com/oauth/jscode2sessionkey';

    /**
     * 认证
     *
     * @param string $code
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function session($code)
    {
        $params = [
            'code' => $code,
            'client_id' => $this->app['config']['app_key'],
            'sk' => $this->app['config']['secret_key'],
        ];

        return $this->http()->withoutAccessToken()->post(sprintf('%s?%s', $this->endPoint, http_build_query($params)));
    }

    /**
     * 获取用户unionid
     *
     * @param string $openid
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUnionid(string $openid)
    {
        $params = [
            'openid' => $openid
        ];

        $response = $this->http()->post('getunionid', $params);
        return empty($response['errno']) ? $response['data'] : $response;
    }
}

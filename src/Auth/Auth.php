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
     * 小程序登录接口
     *
     * @var string
     */
    protected $code2SessionEndPoint = 'https://spapi.baidu.com/oauth/jscode2sessionkey';

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
            'sk' => $this->app['config']['app_secret'],
        ];

        return $this->http()->post(sprintf('%s?%s', $this->code2SessionEndPoint, http_build_query($params)));
    }
}
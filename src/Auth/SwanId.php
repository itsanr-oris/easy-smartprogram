<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/13
 * Time: 2:48 PM
 */

namespace EasySmartProgram\Auth;

use EasySmartProgram\Support\Component;

/**
 * Class SwanId
 * @package EasySmartProgram\Auth
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class SwanId extends Component
{
    /**
     * @param string $swanId
     * @param string $signature
     * @return bool
     */
    public function valid(string $swanId, string $signature)
    {
        return $signature == $this->generateSwanIdSignature($swanId);
    }

    /**
     * @param $swanId
     * @return string
     */
    public function generateSwanIdSignature(string $swanId)
    {
        $params = [
            'appkey' => $this->app->config['app_key'],
            'secret_key' => $this->app->config['secret_key'],
            'swanid' => $swanId,
        ];

        ksort($params);

        $material = '';
        foreach ($params as $key => $value) {
            $material .= sprintf("%s=%s", $key, $value);
        }

        return md5($material);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/17
 * Time: 3:43 PM
 */

namespace EasySmartProgram\Tests\Auth;

use EasySmartProgram\Tests\TestCase;

class SwanIdTest extends TestCase
{
    /**
     * @param $swanId
     * @return string
     */
    protected function generateSwanIdSignature(string $swanId)
    {
        $params = [
            'appkey' => $this->app()->config['app_key'],
            'secret_key' => $this->app()->config['secret_key'],
            'swanid' => $swanId,
        ];

        ksort($params);

        $material = '';
        foreach ($params as $key => $value) {
            $material .= sprintf("%s=%s", $key, $value);
        }

        return md5($material);
    }

    /**
     * test generate swan_id
     */
    public function testGenerateSwanId()
    {
        $this->assertSame(
            $this->generateSwanIdSignature('swan_id'),
            $this->app()->swan_id->generateSwanIdSignature('swan_id')
        );
    }

    /**
     * test valid swan_id signature
     */
    public function testValidSwanId()
    {
        $swanId = 'swan_id';
        $signature = $this->generateSwanIdSignature($swanId);
        $this->assertTrue($this->app()->swan_id->valid($swanId, $signature));
        $this->assertFalse($this->app()->swan_id->valid('swan_id_2', $signature));
    }
}
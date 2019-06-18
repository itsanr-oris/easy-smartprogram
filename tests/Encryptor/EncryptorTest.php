<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/18
 * Time: 4:21 PM
 */

namespace EasySmartProgram\Tests\Encryptor;

use EasySmartProgram\Application;
use EasySmartProgram\Support\Exception\DecryptException;
use EasySmartProgram\Tests\TestCase;

/**
 * Class EncryptorTest
 * @package EasySmartProgram\Tests\Encryptor
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class EncryptorTest extends TestCase
{
    /**
     * @throws \EasySmartProgram\Support\Exception\DecryptException
     */
    public function testDecryData()
    {
        $app = new Application([
            'app_id' => 'app_id',
            'app_key' => 'y2dTfnWfkx2OXttMEMWlGHoB1KzMogm7',
            'secret_key' => 'secret_key',
        ]);

        $sessionKey = '1df09d0a1677dd72b8325aec59576e0c';
        $iv = "1df09d0a1677dd72b8325Q==";
        $cipherText = "OpCoJgs7RrVgaMNDixIvaCIyV2SFDBNLivgkVqtzq2GC10egsn+PKmQ/+5q+chT8xzldLUog2haTItyIkKyvzvmXonBQLIMeq54axAu9c3KG8IhpFD6+ymHocmx07ZKi7eED3t0KyIxJgRNSDkFk5RV1ZP2mSWa7ZgCXXcAbP0RsiUcvhcJfrSwlpsm0E1YJzKpYy429xrEEGvK+gfL+Cw==";

        $expectedData = [
            'openid' => 'open_id',
            'nickname' => 'baidu_user',
            'headimgurl' => 'url of image',
            'sex' => 1,
        ];

        $this->assertSame($expectedData, $app->encryptor->decryptData($sessionKey, $iv, $cipherText));
    }

    /**
     * @throws DecryptException
     */
    public function testInvalidPayload()
    {
        $app = new Application([
            'app_id' => 'app_id',
            'app_key' => 'app_key',
            'secret_key' => 'secret_key',
        ]);

        $sessionKey = '1df09d0a1677dd72b8325aec59576e0c';
        $iv = "1df09d0a1677dd72b8325Q==";
        $cipherText = "OpCoJgs7RrVgaMNDixIvaCIyV2SFDBNLivgkVqtzq2GC10egsn+PKmQ/+5q+chT8xzldLUog2haTItyIkKyvzvmXonBQLIMeq54axAu9c3KG8IhpFD6+ymHocmx07ZKi7eED3t0KyIxJgRNSDkFk5RV1ZP2mSWa7ZgCXXcAbP0RsiUcvhcJfrSwlpsm0E1YJzKpYy429xrEEGvK+gfL+Cw==";

        $this->expectException(DecryptException::class);
        $this->expectExceptionMessage('The given payload is invalid.');
        $app->encryptor->decryptData($sessionKey, $iv, $cipherText);
    }

    /**
     * @throws DecryptException
     */
    public function testOpensslDecryptFalse()
    {
        $app = new Application([
            'app_id' => 'app_id',
            'app_key' => 'app_key',
            'secret_key' => 'secret_key',
        ]);

        $this->expectException(DecryptException::class);
        $this->expectExceptionMessage('The given payload is invalid.');
        $app->encryptor->decryptData('', '', '');
    }
}
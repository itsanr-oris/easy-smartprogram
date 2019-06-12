<?php /** @noinspection PhpDeprecationInspection */

/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/1
 * Time: 9:55 AM
 */

namespace EasySmartProgram\Encryptor;

use EasySmartProgram\Support\Component;
use EasySmartProgram\Support\Exception\DecryptException;

/**
 * Class Encryptor
 * @package EasySmartProgram\Encryptor
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
class Encryptor extends Component
{
    /**
     * Decrypt data.
     *
     * 数据解密：低版本使用mcrypt库（PHP < 5.3.0），高版本使用openssl库（PHP >= 5.3.0）。
     *
     * 扩展包要求 php >= 7.0 去除mcrypt解密代码
     *
     * @param string $sessionKey
     * @param string $iv
     * @param string $encrypted
     *
     * @return array
     * @throws DecryptException
     */
    public function decryptData(string $sessionKey, string $iv, string $encrypted): array
    {
        $appKey = $this->app['config']['app_key'];
        $sessionKey = base64_decode($sessionKey);
        $iv = base64_decode($iv);
        $encrypted = base64_decode($encrypted);

        $method = "AES-192-CBC";
        $options = OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING;
        $plainText = openssl_decrypt($encrypted, $method, $sessionKey, $options, $iv);

        if ($plainText == false) {
            throw new DecryptException('The given payload is invalid.');
        }

        // trim pkcs#7 padding
        $pad = ord(substr($plainText, -1));
        $pad = ($pad < 1 || $pad > 32) ? 0 : $pad;
        $plainText = substr($plainText, 0, strlen($plainText) - $pad);

        // trim header
        $plainText = substr($plainText, 16);
        // get content length
        $unpack = unpack("Nlen/", substr($plainText, 0, 4));
        // get content
        $content = substr($plainText, 4, $unpack['len']);
        // get app_key
        $appKeyCode = substr($plainText, $unpack['len'] + 4);

        $content = json_decode($content, true);
        if ($appKey != $appKeyCode || !$content) {
            throw new DecryptException('The given payload is invalid.');
        }

        return $content;
    }
}
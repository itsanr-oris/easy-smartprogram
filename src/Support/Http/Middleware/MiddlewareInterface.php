<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/10
 * Time: 6:03 PM
 */

namespace EasySmartProgram\Support\Http\Middleware;

/**
 * Interface MiddlewareInterface
 * @package EasySmartProgram\Support\Http\Middleware
 * @author  f-oris <us@f-oris.me>
 * @version 1.0.0
 */
interface MiddlewareInterface
{
    /**
     * @return string
     */
    public function name() : string;

    /**
     * @return callable
     */
    public function callable() : callable;
}
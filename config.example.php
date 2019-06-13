<?php
/**
 * Created by PhpStorm.
 * User: f-oris
 * Date: 2019/6/13
 * Time: 3:38 PM
 */

return [
    /**
     * smart-program app_id
     */
    'app_id' => 'your smart-program app_id here',

    /**
     * smart-program app_key
     */
    'app_key' => 'your smart-program app_key here',

    /**
     * smart-program secret_key
     */
    'secret_key' => 'your smart-program secret_key here',

    /**
     * log config
     */
    'log' => [
        /**
         * default log channel
         */
        'default' => 'dev',

        /**
         * available log channels
         */
        'channels' => [
            'dev' => [
                'name' => 'dev',
                'driver' => 'single',
                'path' => sys_get_temp_dir() . '/logs/smart-program.log',
                'level' => 'debug',
            ],
            'prod' => [
                'name' => 'prod',
                'driver' => 'daily',
                'path' => sys_get_temp_dir() . '/logs/smart-program.log',
                'level' => 'info',
            ],
        ],
    ],

    /**
     * cache config
     */
    'cache' => [
        /**
         * default cache driver
         */
        'default' => 'file',

        /**
         * available cache drivers
         */
        'drivers' => [
            'file' => [
                'path' => sys_get_temp_dir() . '/cache/',
            ],
        ]
    ],
];
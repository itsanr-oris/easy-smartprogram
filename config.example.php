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
                'path' => __DIR__ . '/logs/smart-program.log',
                'level' => 'debug',
            ],
            'prod' => [
                'name' => 'prod',
                'driver' => 'daily',
                'path' => __DIR__ . '/logs/smart-program.log',
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
         * cache life time
         */
        'life_time' => 1800,

        /**
         * available cache drivers
         */
        'drivers' => [
            'file' => [
                'path' => __DIR__ . '/cache/',
            ],
        ]
    ],

    /**
     * http client config
     */
    'http_client' => [
        /**
         * request log template
         */
        'log_template' => \GuzzleHttp\MessageFormatter::DEBUG,

        /**
         * log level
         */
        'log_level' => \Psr\Log\LogLevel::DEBUG,

        /**
         * max retry times
         */
        'max_retries' => 1,

        /**
         * retry delay time, default 500ms
         */
        'retry_delay' => 500,
    ]
];
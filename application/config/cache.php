<?php defined('SYSPATH') or die('No direct script access.');
return array
(
    'memcache' => array(
        'driver'             => 'memcache',
        'default_expire'     => 3600,
        'compression'        => FALSE,              // Use Zlib compression (can cause issues with integers)
        'servers'            => array(
            'local' => array(
                'host'             => '10.201.1.62',  // Memcache Server
                'port'             => 11211,        // Memcache port number
                'persistent'       => FALSE,        // Persistent connection
                'weight'           => 1,
                'timeout'          => 1,
                'retry_interval'   => 15,
                'status'           => TRUE,
            ),
        ),
        'instant_death'      => TRUE,               // Take server offline immediately on first fail (no retry)
    ),
    'file'    => array(
        'driver'             => 'file',
        'cache_dir'          => APPPATH.'cache',
        'default_expire'     => 3600,
        'ignore_on_delete'   => array(
            '.gitignore',
            '.git',
            '.svn'
        )
    ),
    'redis' => array (
            // rediska instance name, must be exists on rediska config
            'driver'=>'redis',
            'instance' => 'cache'
    )

);

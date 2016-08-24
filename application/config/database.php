<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
    'default' => array
    (
        'type'       => 'MySQL',
        'connection' => array(
            /**
             * The following options are available for MySQL:
             *
             * string   hostname     server hostname, or socket
             * string   database     database name
             * string   username     database username
             * string   password     database password
             * boolean  persistent   use persistent connections?
             * array    variables    system variables as "key => value" pairs
             *
             * Ports and sockets may be appended to the hostname.
             */
            'hostname'   => 'anxinmeidaodi.gotoftp4.com',
            'database'   => 'anxinmeidaodi',
            'username'   => 'anxinmeidaodi',
            'password'   => 'zaq1xsw2',
            'persistent' => FALSE,
        ),
        'table_prefix' => 'anxin_',
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ),
    'zixun' => array
    (
        'type'       => 'MySQL',
        'connection' => array(
            /**
             * The following options are available for MySQL:
             *
             * string   hostname     server hostname, or socket
             * string   database     database name
             * string   username     database username
             * string   password     database password
             * boolean  persistent   use persistent connections?
             * array    variables    system variables as "key => value" pairs
             *
             * Ports and sockets may be appended to the hostname.
             */
            'hostname'   => '10.23.50.232',
            //'hostname'   => '192.168.1.62',
            'database'   => 'yijuhua_zixun',
            'username'   => 'yijuhua_zixun',
            'password'   => 'Mes0Hi76zLCz8ex82Ucq',
            'persistent' => FALSE,
        ),
        'table_prefix' => 'czzs_',
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ),

    'read' => array(
        'type'       => 'PDO',
        'connection' => array(
            /**
             * The following options are available for PDO:
             *
             * string   dsn         Data Source Name
             * string   username    database username
             * string   password    database password
             * boolean  persistent  use persistent connections?
             */
            'dsn'        => 'mysql:host=192.168.1.62;dbname=lnvestment_platform',
            'username'   => 'yijuhua',
            'password'   => '123yijuhua',
            'persistent' => FALSE,
        ),
        /**
         * The following extra options are available for PDO:
         *
         * string   identifier  set the escaping identifier
         */
        'table_prefix' => 'czzs_',
        'charset'      => 'utf8',
        'caching'      => FALSE,
    ),
    'write' => array(
                'type'       => 'PDO',
                'connection' => array(
                        /**
                         * The following options are available for PDO:
        *
        * string   dsn         Data Source Name
        * string   username    database username
        * string   password    database password
        * boolean  persistent  use persistent connections?
        */
                        'dsn'        => 'mysql:host=192.168.1.62;dbname=lnvestment_platform',
                        'username'   => 'yijuhua',
                        'password'   => '123yijuhua',
                        'persistent' => FALSE,
                ),
                /**
                 * The following extra options are available for PDO:
        *
        * string   identifier  set the escaping identifier
        */
                'table_prefix' => 'czzs_',
                'charset'      => 'utf8',
                'caching'      => FALSE,
        ),
);

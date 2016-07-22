<?php
return array(

    Rediska::DEFAULT_NAME => array (
        'name'         => Rediska::DEFAULT_NAME,
        'namespace'    => '',

    ),
    'cache'   => array (
        'name'         => 'cache',
        'namespace'    => 'cache::',
        'servers'      => array(
            array(
                'host'   => '10.23.50.233',
                //'host'   => '127.0.0.1',
                'port'   => '6379',
                'weight' => '1'
            )
        ),
    ),
    'list'   => array (
            'name'         => 'list',
            'namespace'    => 'list::',
            'servers'      => array(
                    array(
                            'host'   => '10.23.50.233',
                            //'host'   => '127.0.0.1',
                            'port'   => '6379',
                            'weight' => '1'
                    )
            ),
    ),
    'rc'   => array (
        'name'         => 'rc',
        'namespace'    => 'rc::',
        'servers'      => array(
            array(
                'host'   => '10.201.6.34',
                //'host'   => '127.0.0.1',
                'port'   => '6378',
                'weight' => '1'
            )
        ),
    ),

);

?>

<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana extends Kohana_Core {
    //及时打印sql
    public static $pfm = false;
    //sql执行保存
    public static $performance =array();


    public static $environment = Kohana::TESTING;
}

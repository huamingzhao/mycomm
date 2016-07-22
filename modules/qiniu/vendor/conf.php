<?php

global $QINIU_UP_HOST;
global $QINIU_RS_HOST;
global $QINIU_RSF_HOST;
 
global $QINIU_ACCESS_KEY;
global $QINIU_SECRET_KEY;

$QINIU_UP_HOST	= 'http://up.qiniu.com';
$QINIU_RS_HOST	= 'http://rs.qbox.me';
$QINIU_RSF_HOST	= 'http://rsf.qbox.me';

$QINIU_ACCESS_KEY	= Kohana::$config->load('conf.QINIU_ACCESS_KEY');
$QINIU_SECRET_KEY	= Kohana::$config->load('conf.QINIU_SECRET_KEY');


<meta charset="UTF-8">
<title></title>
<style>
    table{ border-collapse:collapse;border:1px solid #CCC;background:#efefef;width:100%; margin-bottom:1em;} 
    table th{ text-align:left; font-weight:bold; padding:.5em 2em .5em .75em; line-height:1.6em; font-size:12px; border:1px solid #CCC;} 
    table td{ padding:.5em .75em; line-height:1.6em; font-size:12px; border:1px solid #CCC;background-color:#fff;}
    .c1{ width: 150px;}
    .c2{ width: 120px;}
    .c3{ width: 130px;}
    .c4{ width: 130px;}
    caption{ font-size:14px; font-weight:bold; line-height:2em; text-align:left; }
    </style>
<?php
$dbserver="192.168.1.62";
$dbusername="platform";
$dbpassword="platform123";
if($_GET['db']==''){
    $database = 'lnvestment_platform';
}else{
    $database = $_GET['db'];
}
$mysql_conn=@mysql_connect("$dbserver","$dbusername","$dbpassword") or die("Mysql connect is error.");
mysql_select_db($database,$mysql_conn);
$result = mysql_list_tables($database,$mysql_conn);
mysql_query('SET NAMES utf8',$mysql_conn);
while ($tableList = mysql_fetch_array($result)){
    $table = $tableList[0];
	$TABLE_COMMENT=mysql_query("select TABLE_COMMENT from information_schema.tables where table_name='$table'",$mysql_conn);
	$C=mysql_fetch_array($TABLE_COMMENT);
	$C=$C["TABLE_COMMENT"];
    $field_result= mysql_query("SELECT * FROM
                               INFORMATION_SCHEMA.COLUMNS
                               WHERE
                               table_name = '$table' AND table_schema = '$database'",$mysql_conn
                                );
    echo ''.chr(13);
    echo ''.chr(13);
    echo ''.chr(13);
    echo "<table><caption>表名:   $table&nbsp;&nbsp;&nbsp;&nbsp;备注：$C</caption><tbody><tr>
            <th>字段名</th>
            <th>数据类型</th>
            <th>默认值</th>
            <th>字符编码</th>
            <th>备注</th>
            </tr>";
    while ($f = mysql_fetch_array($field_result)){
        echo ''.chr(13);
        echo ''.chr(13);
        echo ''.chr(13);
        echo ''.chr(13);
        echo ''.chr(13);
        echo ''.chr(13);
        echo ''.chr(13);
   
    echo '<tr><td class="c1">'.$f['COLUMN_NAME'].'</td><td class="c2">'.$f['COLUMN_TYPE'].'</td><td class="c3">'.$f['COLUMN_DEFAULT'].'</td><td class="c4">'.$f['COLLATION_NAME'].'</td><td class="c5">'.$f['COLUMN_COMMENT'].'</td></tr>';
    }
    echo "</tbody></table>";
}

mysql_close($mysql_conn);

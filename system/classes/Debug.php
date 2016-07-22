<?php defined('SYSPATH') OR die('No direct script access.');

class Debug extends Kohana_Debug {
    public static function printsql(){
        if (Kohana::$pfm) {
            $total = 0;
            if (Kohana::$performance) {
                foreach ( Kohana::$performance as $i=>$p ) {
                    //echo "消耗时间（单位微秒）：<span style='color:red;'>{$p[0]}</span><br> SQL:  {$p[1]}<br>";
                    /*
                     * if(strpos($p[2][0]['file'], "ORM.php")){ echo "ORM查询，暂无定位<br><br>"; } else{ echo "<span style='color:green;'>DB查询,行数：{$p[2][0]['line']} ;文件名：".$p[2][0]['file']."</span><br><br>"; }
                     */
                    /*
                    if ($p [2]) {
                        foreach ( $p [2] as $detail ) {
                            echo Debug::path(Arr::get ( $detail, "file" )) . "=>" . Debug::source(Arr::get ( $detail, "file" ),  Arr::get ( $detail, "line" )).Arr::get ( $detail, 'function' ) . "=>" . Arr::get ( $detail, "line" ) . "<br>";
                        }
                    }
                    */
                    echo View::factory("kohana/sqldebug",array("trace"=>$p[2],"sql"=>$p[1],'excute_time'=>$p[0],'num'=>$i+1));
                    $total += $p [0];
                }
                echo "SQL 总计消耗时间为 秒<span style='color:red;'>{$total}</span><br><br>";
            } else {
                echo "没有SQL执行";
            }
        }
    }
}

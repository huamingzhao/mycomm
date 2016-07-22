<?php
/**
 * 企业用户各种保障状态
 * @author 许晟玮
 *
 */
class zixun {

    /**
     * 获取限制显示的栏目名称
     *
     * @author 许晟玮
     */
    public static function getLimitCol() {
        $arr = array (
                '开店指导',
                '开店管理',
                '行业透视'
        );

        return $arr;
    }
    // end function

    /**
     * 过滤字符串的特殊字符
     *
     * @author 许晟玮
     */
    public static function setContentReplace($content, $allow = "") {
        $return = strip_tags ( htmlspecialchars_decode ( html_entity_decode($content) ), $allow );
        // 过滤空格
        if( $allow=="" ){
            $return = str_replace ( ' ', '', trim ( $return ) );
            $return= str_replace("\n", "", $return);
            $return= str_replace("\r", "", $return);
            $return= str_replace("<br />", "", $return);
        }
        return $return;
    }
    // end function

    /**
     * 过滤字符串的特殊字符【并且对关键字添加上A标签】
     * @author 钟涛
     */
    public static function setContentReplaceByAddKey($content, $allow = "",$id=0) {
        $memcache = Cache::instance ( 'memcache' );
        $_cache_get_time = 86400;//一天
        $memcahcename='ContentByZhongtao'.$id;
        if($memcache->get($memcahcename) && intval($id) && $id>0){
            return $memcache->get($memcahcename);
        }else{
            $return = strip_tags ( htmlspecialchars_decode ( html_entity_decode($content) ), $allow );
            // 过滤空格
            if( $allow=="" ){
                $return = str_replace ( ' ', '', trim ( $return ) );
                $return= str_replace("\n", "", $return);
                $return= str_replace("\r", "", $return);
                $return= str_replace("<br />", "", $return);
            }
            //seo优化添加内链接
//             $keyarr=common::zixunkey();
//             foreach($keyarr as $zixun_k=>$zixun_v){
//                 $reg ='/'.$zixun_k.'(?![^a]*\<\/a\>)/';
//                 $reg2 = '<a class="key_words" target="_blank" href="'.$zixun_v.'">'.$zixun_k.'</a>';
//                 $return= preg_replace($reg,$reg2,$return,1);
//             }
            $memcache->set($memcahcename,$return,$_cache_get_time);
            return $return;
        }
    }

    /**
     * 过滤行业新闻字符串的特殊字符【并且对关键字添加上A标签】
     * @author 花文刚
     */
    public static function setContentReplaceOfIndustry($content, $allow = "",$id=0) {
        $memcache = Cache::instance ( 'memcache' );
        $_cache_get_time = 86400;//一天
        $memcahcename='ContentReplaceOfIndustry'.$id;
        if($memcache->get($memcahcename) && intval($id) && $id>0){
            return $memcache->get($memcahcename);
        }else{
            $return = strip_tags ($content , $allow );
            // 过滤空格
            if( $allow=="" ){
                $return = str_replace ( ' ', '', trim ( $return ) );
                $return= str_replace("\n", "", $return);
                $return= str_replace("\r", "", $return);
                $return= str_replace("<br />", "", $return);
            }
            //seo优化添加内链接
//             $keyarr=common::zixunkey();
//             foreach($keyarr as $zixun_k=>$zixun_v){
//                 $reg ='/'.$zixun_k.'(?![^a]*\<\/a\>)/';
//                 $reg2 = '<a class="key_words" target="_blank" href="'.$zixun_v.'">'.$zixun_k.'</a>';
//                 $return= preg_replace($reg,$reg2,$return,1);
//             }
            $memcache->set($memcahcename,$return,$_cache_get_time);
            return $return;
        }
    }

    /**
     * 定义文章详情中的可以html元素
     *
     * @author 许晟玮
     */
    public static function setContentApply($type=false) {
        $str = "<p><div><a>><br><strong><span>";
        if( $type===true ){
            $str.= "<img>";
        }
        return $str;
    }
    // end function

    /**
     * 删除指定的HTML标签及其中内容，暂时只支持单标签清理
     *
     * @param string $string
     *            -- 要处理的字符串
     * @param string $tagname
     *            -- 要删除的标签名称
     * @param boolean $clear
     *            -- 是否删除标签内容
     * @return string -- 返回处理完的字符串
     * @author 许晟玮
     */
    public static function replace_html_tag($string, $tagname, $clear = false) {
        $re = $clear ? '' : '\1';
        $sc = '/<' . $tagname . '(?:\s[^>]*)?>([\s\S]*?)?<\/' . $tagname . '>/i';
        return preg_replace ( $sc, $re, $string );
    }
    // end function

    /**
     *文章内容简介生成
     * @param unknown $text
     * @param string $len
     * @return unknown
     */
    public static function Generate_Brief($text,$len='500') {

        mb_regex_encoding ( "UTF-8" );
        if (mb_strlen ( $text ) <= $len)
            return $text;
        $Foremost = mb_substr ( $text, 0, $len );
        $re = "<(\/?)(P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|TABLE|TR|TD|TH|INPUT|SELECT|TEXTAREA|OBJECT|A|UL|OL|LI|BASE|META|LINK|HR|BR|PARAM|AREA|INPUT|SPAN)[^>]*(>?)";
        $Single = "/BASE|META|LINK|HR|BR|PARAM|AREA|INPUT|BR/i";
        $Stack = array ();
        $posStack = array ();
        mb_ereg_search_init ( $Foremost, $re, 'i' );
        while ( $pos = mb_ereg_search_pos () ) {
            $match = mb_ereg_search_getregs ();
            /*
             * [Child-matching Formulation]: $matche[1] : A "/" charactor indicating whether current "<...>" Friction is Closing Part $matche[2] : Element Name. $matche[3] : Right > of a "<...>" Friction
             */
            if ($match [1] == "") {
                $Elem = $match [2];
                if (mb_eregi ( $Single, $Elem ) && $match [3] != "") {
                    continue;
                }
                array_push ( $Stack, mb_strtoupper ( $Elem ) );
                array_push ( $posStack, $pos [0] );
            } else {
                $StackTop = $Stack [count ( $Stack ) - 1];
                $End = mb_strtoupper ( $match [2] );
                if (strcasecmp ( $StackTop, $End ) == 0) {
                    array_pop ( $Stack );
                    array_pop ( $posStack );
                    if ($match [3] == "") {
                        $Foremost = $Foremost . ">";
                    }
                }
            }
        }
        $cutpos = array_shift ( $posStack ) - 1;
        $Foremost = mb_substr ( $Foremost, 0, $cutpos, "UTF-8" );
        return $Foremost;
    }
    // end function

    /**
     * 配置充值总金额对应的每一个项目下免费发布文章次数
     * @author许晟玮
     */
    public static function account_zixun( $account ){
        $account= ceil( $account );
        if( $account==0 ){
            return '3';
        }elseif ( $account>=5000 && $account<10000 ){
            return '10';
        }elseif ( $account>=10000 && $account<20000 ){
            return '20';
        }elseif ( $account>=20000 ){
            return '40';
        }
    }
    //end function

    public static function getZxPorjectId($article_id){
        $cache = Cache::instance("memcache");
        $return_id = "ProjectArticle_".$article_id;
        $project_id = $cache->get($return_id);
        if($project_id){
            return $project_id;
        }
        else{
            $obj = ORM::factory("ProjectArticle")
                                ->where("article_id","=",$article_id)
                                ->find();
            if($obj->loaded()){
                $cache->set($return_id, $obj->project_id);
                return $obj->project_id;
            }
        }
        return false;
    }


}
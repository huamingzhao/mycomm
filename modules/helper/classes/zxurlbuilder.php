<?php
/**
 * 资讯构建url
 * @author 龚湧
 *
 */
class zxurlbuilder extends urlbuilder{
    /**
     * 资讯栏目地址
     * @param unknown $id
     * @author 周进
     */
    public static function column($parent=''){
        $list = common::column();
        if ($parent!=''){
            foreach ($list as $k=>$v){
                if ($v['parent']==$parent)
                    return URL::website("zixun/".$k.".html");
                else{
                    foreach ($v['children'] as $j=>$h){
                        if ($h==$parent)
                            return URL::website("zixun/".$k."/".$j.".html");
                    }
                }
            }
            return  URL::website("zixun/index/column?parent=".$parent);
        }
        return URL::website("zixun/index/column?parent=".$parent);
    }
    /**
     * 项目向导子栏目地址
     * @param unknown $id
     * @author 周进
     */
    public static function xiangmucolumn($parent=''){
        $list = common::column();
        if ($parent!=''){
            foreach ($list as $k=>$v){
                if ($v['parent']==$parent)
                    return URL::website("zixun/".$k.".html");
                else{
                    foreach ($v['children'] as $j=>$h){
                        if ($h==$parent)
                            return URL::website("zixun/fugle/".$j.".html");
                    }
                }
            }
            return  URL::website("zixun/index/xiangmu?parent=".$parent);
        }
        return URL::website("zixun/index/xiangmu?parent=".$parent);
    }

    /**
     * 项目向导子栏目单个文章地址
     * @param unknown $id
     * @author 周进
     */
    public static function xiangmucolumnxmid($parent='',$xmid){
        $list = common::column();
        if ($parent!=''){
            foreach ($list as $k=>$v){
                if ($v['parent']==$parent)
                    return URL::website("zixun/".$k.".html");
                else{
                    foreach ($v['children'] as $j=>$h){
                        if ($h==$parent)
                            return URL::website("zixun/fugle/".$j."-{$xmid}.html");
                    }
                }
            }
            return  URL::website("zixun/index/xiangmu?parent=".$parent);
        }
        return URL::website("zixun/index/xiangmu?parent=".$parent);
    }

    /**
     * 资讯投稿 zixun/zixun/tougao
     * @return string
     * @author 龚湧
     */
    public static function zixunTougao(){
        return URL::website("help/report/");
    }

    /**
     * 专栏文章详情
     * @return string
     * @author 赵路生
     */
    public static function zhuanlaninfo($id){
        return URL::website("zixun/hot/".$id.".shtml");
    }
    /**
     * 资讯详细页
     * @author 周进
     */
    public static function zixuninfo($id,$datepath=null){
        if(isset($datepath) && !is_null($datepath)){
            $path=$datepath;
        }
        else{
            $path=date('Ym');
        }
        return URL::website("zixun/".$path."/".$id.".shtml");
    }

    /**
     * 行业新闻 详细页
     * @author 龚湧
     */
    public static function industryzixuninfo($id,$datepath=null){
        if(isset($datepath) && !is_null($datepath)){
            $path=$datepath;
        }
        else{
            $path=date('Ym');
        }
        return URL::website("industry/".$path."/".$id.".shtml");
    }

    /**
     * 项目新闻 详细页
     * @author 龚湧
     */
    public static function porjectzixuninfo($project_id,$id){
        return URL::website("info/p-".$project_id."-".$id.".html");
    }

    /**
     * 标签页
     * @author 周进
     */
    public static function tag($name){
        return URL::website("zixun/tag/".$name.".html");
    }

    /**
     * 项目新闻标签
     * @param unknown $name
     * @return string
     * @author 龚湧
     */
    public static function ptag($name){
        return URL::website("zixun/info-".$name.".html");
    }
    
    /**
     * 行业新闻标签
     * @param unknown $name
     * @return string
     * @author 郁政
     */
	public static function hyxwtag($name,$page = 1){
		if($page == 1){
			return URL::website("zixun/industryinfo-a/".$name.".html");
		}else{
			return URL::website("zixun/industryinfo-a/".$name."-p".$page.".html");
		}        
    }

    /**
     * 资讯投稿 zixun/zixun/tougao
     * @return string
     * @author 龚湧
     */
    public static function zixun(){
        return URL::website("zixun/");
    }

    /**
     * 资讯分页处理
     * @author 周进
     */
    public static function siteMapPage($parent, $page){
        $list = common::column();
        if ($parent!=''){
            foreach ($list as $k=>$v){
                if ($parent==$k)
                    return URL::website("zixun/".$k."-".$page.".html");
                else{
                    foreach ($v['children'] as $j=>$h){
                        if ($j==$parent)
                            return URL::website("zixun/".$k."/".$j."-{$page}.html");
                    }
                }
            }
            return URL::website("zixun/list-{$page}.html");
        }
        if($page == 1){
        	return URL::website("zixun/");
        }
        return URL::website("zixun/list-{$page}.html");
    }

    /**
     * 资讯分页处理
     * @author 赵路生
     */
    public static function siteZhuanlan($parent, $page ,$id=false){
        if(!$id){
            return URL::website("zixun/".$parent."-p".$page.".html");
        }elseif($page==1){
            return  URL::website("zixun/".$parent."/".$id."-p".$page.".shtml");
        }else{
            return  URL::website("zixun/".$parent."/".$id."-p".$page.".shtml#content_list");
        }

    }

    /**
     * 行业新闻分页处理
     * @author 花文刚
     */
    public static function siteIndustry($industry_id,$page){
        if($page>1){
            if ($industry_id){
                return  URL::website("zixun/industryinfo-a/hy".$industry_id."-".$page.".html");
            }
            else{
                return URL::website("zixun/industryinfo-a-".$page.".html");
            }
        }
        else{
            if ($industry_id){
                return  URL::website("zixun/industryinfo-a/hy".$industry_id.".html");
            }
            else{
                return URL::website("zixun/industryinfo-a.html");
            }
        }
    }

    /**
     * 行业新闻分页处理(项目官网)
     * @author 花文刚
     */
    public static function siteIndustryProject($project_id,$page){
        if($page>1){
            return  URL::website("industry/".$project_id."-p".$page.".html");
        }
        else{
            return URL::website("industry/".$project_id.".html");
        }
    }

    /**
     * 项目官网资讯列表翻页
     * @author许晟玮
     */
    public static function siteProjectZixun( $projectid,$page ){
        $url= "info/{$projectid}-p{$page}.html";
        return URL::website($url);
    }

    /**
     * 行业新闻行业菜单地址
     * @param unknown $industry_id 行业id
     * @author 花文刚
     */
    public static function industry_news_menu($industry_id=0){
        if ($industry_id){
            return  URL::website("zixun/industryinfo-a/hy".$industry_id.".html");
        }
        else{
            return URL::website("zixun/industryinfo-a.html");
        }
    }

    //end function
}
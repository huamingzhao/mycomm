<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 企业中户中心项目海报
 * @author 龚湧
 *
 */
class Service_User_Company_Poster{

    /**
    * 判断项目是有海报
    * @author 龚湧
    * @param int $project_id
    * @return bool
    */
    public function isHasPoster($project_id){
        $poster = ORM::factory("Projectposter",$project_id);
        if($poster->project_id){
            return true;
        }
        return false;
    }

    /**
     * TODO 增加海报内容
     * 获取海报内容
     * @author 龚湧
     * @param unknown_type $project_id
     * @return ORM|boolean
     */
    public function getPosterById($project_id){
        $poster = ORM::factory("Projectposter",$project_id);
        if($poster->project_id){
            return $poster;
        }
        return false;
    }

    /**
    * 删除海报，暂时为硬删除
    * @author 龚湧
    * @param int $user_id 用户id，作为校验
    * @param int $project_id删除项目海报
    */
    public function delPoster($user_id,$project_id){
        $poster = ORM::factory("Projectposter",$project_id);
        if($poster->project_id){
            //用户项目所属项目海报
            $project_user_id = $poster->project->user_company->com_user_id;
            if($project_user_id == $user_id){
                try{
                    if($poster->content->project_id){
                        //TODO 图片做删除操作
                        $poster->content->delete();
                    }
                    $poster->delete();
                }catch(Kohana_Exception $e){
                    throw $e;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * 根据模板生成海报
     * @author 龚湧
     * @return ORM | bool
     */
    public function addPosterByTemplate(array $poster){
        if(!Arr::get($poster, "project_id")){
            return false;
        }
        $posters = ORM::factory("Projectposter",$poster['project_id']);
        try{
            //$poster = $poster->values($poster);
            $posters->project_id = $poster['project_id'];
            $posters->poster_type = 1;//使用模板类型
            $posters->template_id = $poster['template_id'];
            $posters->add_time = time();
            $posters->save();
        }
        catch (Kohana_Exception $e){
            throw  $e;
        }
        return $posters;
    }

    /**
     * 用户自己上传海报
     * @author 龚湧
     * @return ORM
     */
    public function addPosterByUpload(array $poster){
        $poster = ORM::factory("Projectposter");
    }

    /**
     * 针对用户上传和采集认领的海报，暂时不做，直接删除好了
     * @author 龚湧
     */
    public function editPoster($user_id,$project_id){
        $poster = ORM::factory("Projectposter",$project_id);
    }
}
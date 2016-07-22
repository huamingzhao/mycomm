<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_UpdateArticleStatus extends Minion_Task
{
     /**
     * 资讯文章定时自动审核
     * @author 周进
     */
    protected function _execute(array $params){
        #php shell php minion --task=UpdateIndustryNumBydate
        try {
            $service_msg = new Service_User_Ucmsg();
            $model = ORM::factory("Zixun_Article");
            $list = $model->where('article_status','=',1)
                    ->and_where('article_addtime','<=',time())
                    ->and_where('article_addtime','!=','')
                    ->find_all();
            foreach ($list as $k=>$v){
                $model = ORM::factory("Zixun_Article",$v->article_id);
                $model->article_status = 2;
                $model->update();

                //send message
/*
                $to_url = URL::mainsite("zixun/".date('Ym')."/".$v->article_id.".shtml");
                $msg_content = "您投稿的文章“<a href='{$to_url}'>{$v->article_name}</a>”已经通过审核";
                $user_service= new Service_User();
                $userinfo= $user_service->getUserInfoById($v->user_id);
                $user_type= $userinfo->user_type;
                if($user_type == 1){
                    $msg_type_name = "company_zixun_true";
                }
                elseif($user_type == 2){
                    $msg_type_name = "person_zixun_true";
                }
                $service_msg->pushMsg($v->user_id, $msg_type_name, $msg_content,$to_url);
*/
            }

            //edit by 许晟玮 专栏自动审核
            $orm = ORM::factory("Zixun_Zxzl");
             $list = $orm->where('zl_status','=',1)
                    ->and_where('zl_shtime','<=',time())
                    ->and_where('zl_shtime','!=','')
                    ->find_all();
             foreach ($list as $k=>$vs){
                 $model = ORM::factory("Zixun_Zxzl",$vs->zl_id);
                 $model->zl_status = 2;
                 $model->update();
             }

        } catch (Exception $e) {
            //
        }
    }
}
?>
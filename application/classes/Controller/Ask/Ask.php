<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 创业问题我知道
 * @author 钟涛
 *
 */
class Controller_Ask_Ask extends Controller_Platform_Template{
	/**
	 * 创业问题我知道>>首页
	 * @钟涛
	 */
	public function action_index2(){
		//做缓存
		$memcache = Cache::instance ( 'memcache' );
		$_cache_get_time = 5;
		$memarr=$memcache->get('index2test');
		if($memarr){
			$retrunlist= 'yes';
			echo $retrunlist;
		}else{
			$retrunlist='not';
			echo $retrunlist;
			$memcache->set('index2test',$retrunlist,$_cache_get_time);
		}
		exit;
	}
    /**
     * 创业问题我知道>>首页
     * @钟涛
     */
    public function action_index(){
        $content = View::factory("news/ask_index");
        $askser=new Service_News_Ask();
        //图片广告
        $content->ad_image = $askser->getAskImage();
        //首页广告问题
        $content->ad_head_info = $askser->getAskHeadInfo();
        //问题总数
        $content->askcount = $askser->getAskCunt();
        //获取浏览量最高的4个问题
        $content->pvcountlist = $askser->getAskPvCountList();
        //获取创业1级问题分类
        $content->askIndustryOneArr = $askser->getAskIndustryOneArr();
        //获取1周内收到名片最多的5个项目
        $content->projectlist = $askser->getTop5ProjectList();
        //获取已解答问题
        $content->isanswerlist = $askser->getIsAnswerList(1);
        //获取待解答问题
        $content->notanswerlist = $askser->getIsAnswerList(0);
        //创业者常用问题
        $content->askusedlist = $askser->getAskUsedList();
        //获取推荐资讯文章
        //$content->askZixunList = $askser->getAskZixunList();
        // 获取热门创业问答
        $content->pvlatest = $askser->getAskPvCountListLatest(10,time()-86400*30*3);
        // 获取登录用户问题总数
        if($this->isLogins()){
        	$content->login_status = true;
    		// 获取用户信息 
    		$user_info = Service_Sso_Client::instance()->getUserInfo(cookie::get('authautologin'));
    		if(isset($user_info->id) && $user_info->id){
    			// 获取我的提问总数
    			$content->title_total = $askser->getMyAskTitleTotal($user_info->id);
    			// 获取我的回答总数
    			$content->answer_total = $askser->getMyAskAnswerTotal($user_info->id);
    		}       	
        }
        $this->content->maincontent = $content;
        //友情链接
        $memcache = Cache::instance ('memcache');
        $friend_link = $memcache->get('friend_cache_wenda');
        if(empty($friend_link)){
            $f_service = new Service_Platform_FriendLink();
            $friend_link = $f_service->getFriendLinkList('wenda');
            $memcache->set('friend_cache_wenda', $friend_link,604800);
        }
        $this->template->friend_link = $friend_link;
        //seo
        $this->template->title = '【一句话创业问答】中国最专业投资创业问答网';
        $this->template->keywords = '投资创业问答，投资创业问答网，一句话创业问答';
        $this->template->description = '一句话创业问答网是中国最好的创业、投资、加盟问答平台！为投资创业人员提供投资创业中各种难题的解答，创业问答网帮助创业者解决代理、加盟、批发、开店、投资、商机、买卖、致富等14个大类数千条创业问题，帮助创业投资者走向创业致富之路！';
    }

}
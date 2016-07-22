<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 搜索
 * @author stone shi 
 */
class Service_Business_Search{
    
    /**
     * 搜索框
     * @author stone shi 
     * @param string $word 
     * @param int $limit
     * @return array
     */
    public function getWordSearch($word, $limit = 3) {
        $memcache = Cache::instance('memcache');
        $keyCache = 'WEN_getWordSearch'.'_'.$word.'_'.$limit;
        $return =  $memcache->get($keyCache);
        if(!$return) {
            if($word) {
                $resultTitle = wensearch::getInstance()->searchWenTitle($word, $limit);
                $resultUser = wensearch::getInstance()->searchWenUser($word, $limit);
                $resultTag = $this->searchTag($word);
                $return = $this->_checkSearchList($resultTitle, $resultUser, $resultTag);
                $memcache->set($keyCache, $return, 120);
            }else{
                $return = array('wen' => array(), 'user' => array(), 'title' => array());
            }
        }
        return $return;
    }
    
    /**
     *  快速搜索下拉框
     */
    public function searchPro($word) {
        $memcache = Cache::instance('memcache');
        $keyCache = 'WEN_searchPro'.'_'.$word;
        $return =  $memcache->get($keyCache);
        if(!$return) {
            $Search = new Service_Api_Search(); 
            $return = $Search->getQucikSearch($word, 0, '', 10);
            $return = $this->_chechSearhPro($return);
            $memcache->set($keyCache, $return, 300);
        }
        return $return;
    }
    /**
     * 标签搜索
     * @author stone shi
     */
    public function searchTag($word, $page =1) {
        $memcache = Cache::instance('memcache');
        $keyCache = 'WEN_searchTag'.'_'.$word.'_'.$page;
        $return =  $memcache->get($keyCache);
        if(!$return) {
            $alk_obj = new Service_Business_Talk();
            $talk_list = $alk_obj->get_talk_list();
            $return = array();
            if($talk_list) {
                foreach($talk_list as $key => $val) {
                    if(strpos(arr::get($val, 'name'), $word) || strpos(arr::get($val, 'name'), $word) === 0) {
                        $val['name'] = str_replace($word, '<var>'.$word.'</var>', $val['name']);
                        $tempTag = $val;
                        $tempTag['num'] = Service_Business_Question::getInstance()->get_talk_question_count(arr::get($val, 'id'));
                        $tempTag['href'] = urlbuilder::business_index(arr::get($val, 'id'));
                        $tempTag['img'] = URL::webstatic('images/syb/huatiimg.png');//http://static.yijuhua-alpha.net/images/syb/huatiimg.png
                        $return[] = $tempTag;
                    }
                }
            }
            $memcache->set($keyCache, $return, 86400);
        }
        
        return $return;
    }
    
    /**
     * 搜索疑问
     */
    public function searchWen($word, $page = 1) {
        $limit = 30;
        $offset = ($page - 1) * $limit;
        $memcache = Cache::instance('memcache');
        $keyCache = 'WEN_searchWen'.'_'.$word.'_'.$page;
        $return =  $memcache->get($keyCache);
        if(!$return) {
            $Search = new Service_Api_Search(); 
            $resultTitle = $Search->getWenSearch($word, $offset, '',$limit);
            $talkSer = new Service_Business_Talk();
            $talkInfo = $talkSer->get_talk_list();
            if(!arr::get($resultTitle, 'matches', '')) {
                $return = array();
            }else{
                if($resultTitle['matches']) {
                    foreach($resultTitle['matches'] as $key => $val) {
                        $tempUser = array();
                        $tempUser = $val['val'];
                        $numArr = Service_Business_Stat::getInstance()->get_question_nice_against_stat($key);
                        $last_answer_user = Service_Business_Answer::getInstance()->get_last_answer_user($key);
                        $tempUser['num'] = arr::get(arr::get($numArr, $key), 'answer_count', 0); 
                        $tempUser['view_count'] = arr::get(arr::get($numArr, $key), 'view_count', 0); 
                        $tempUser['href'] = urlbuilder::business_detail($key);
                        $tempUser['talkId'] = arr::get($tempUser, 'talkid', 0);
                        $tempUser['tagName'] = $tempUser['talkId'] ? arr::get(arr::get($talkInfo, $tempUser['talkId']),'name', '暂无') : '';
                        $userInfo = Service_Business_UserStat::getInstance()->get_user_info(arr::get($tempUser, 'userid', 0));
                        $tempUser['tagHref'] = urlbuilder::business_index($tempUser['talkId']);
                        $tempUser['userName'] = arr::get($last_answer_user, 'user_name', '');
                        $tempUser['userPhoto'] = URL::imgurl(arr::get($userInfo, 'user_photo', ''));
                        $tempUser['userHref'] = urlbuilder::business_userinfo(arr::get($tempUser, 'userid', 0));
                        $tempUser['userLastHref'] = arr::get($last_answer_user, 'user_id', 0) ? urlbuilder::business_userinfo(arr::get($last_answer_user, 'user_id', 0)) : "" ;
                        //URL::webstatic('/images/quickrelease/company_default.png');
                        $return[] = $tempUser;
                        
                    }
                    $memcache->set($keyCache, $return, 60);
                }
                
            }
        }
        return $return;
    }
    /**
     * 搜索资讯
     */
    public function searchZixun($word, $page = 1) {
        $memcache = Cache::instance('memcache');
        $keyCache = 'WEN_searchZixun'.'_'.$word.'_'.$page;
        $return =  $memcache->get($keyCache);
        if(!$return) {
            $limit = 30;
            $offset = ($page - 1) * $limit;
            $Search = new Service_Api_Search(); 
            $returnZx = $Search->getSearchZixunWen($word, $offset, $limit);
            if(!arr::get($returnZx, 'matches', '')) {
                    $return = array();
                }else{
                    if($returnZx['matches']) {
                        $service_zixun = new Service_News_Zixun();
                        foreach($returnZx['matches'] as $key => $val) {
                            $tempUser = array();
                            $articleinfo = $service_zixun->getArticleInfoById($key)->as_array();
                            $tempUser['articleName'] = arr::get($val['val'], 'articleName', '');
                            $tempUser['articleTag'] = arr::get($val['val'], 'articleTag', '');
                            $tempUser['articleContent'] = arr::get($articleinfo, 'article_content', '');
                            $tempUser['articleContent'] = $tempUser['articleContent'] ? (mb_substr((str_replace('\r','',str_replace("\n",'',zixun::setContentReplace($tempUser['articleContent']) ))),0,35,'UTF-8')).'...' : '';
                            $tempUser['articleImg'] = $articleinfo['article_img'] ? URL::imgurl($articleinfo['article_img']) : '';
                            $tempUser['pv'] = $articleinfo['article_onlooker'];
                            $tempUser['articleTag'] = arr::get($val['val'], 'articleTag', '');
                            $tempUser['href'] = ($articleinfo['parent_id'] != 29) ? zxurlbuilder::zixuninfo($key,date("Ym",$articleinfo['article_intime'])) : zxurlbuilder::porjectzixuninfo(zixun::getZxPorjectId($key),$key);
                            $return[] = $tempUser;
                            
                        }
                        $memcache->set($keyCache, $return, 300);
                    }
                }
        }
        return $return;
    }
    
    /**
     * 搜索用户
     * @author stone shi
     */
    public function searchUser($word, $page = 1) {
        $memcache = Cache::instance('memcache');
        $keyCache = 'WEN_searchUser'.'_'.$word.'_'.$page;
        $return =  $memcache->get($keyCache);
        if(!$return) {
            $limit = 30;
            $Search = new Service_Api_Search(); 
            $resultUser = wensearch::getInstance()->searchWenUser($word, $limit, $page);
            if(!arr::get($resultUser, 'matches', '')) {
                $return = array();
            }else{
                if($resultUser['matches']) {
                    foreach($resultUser['matches'] as $key => $val) {
                        $tempUser = array();
                        $tempUser = $val['val'];
                        $tempUser['signature'] = $this->getUserSignatureById($val['id']);
                        $tempUser['href'] = urlbuilder::business_userinfo($val['id']);
                        $tempUser['img'] = arr::get($tempUser,'img', '') ? URL::imgurl(arr::get($tempUser,'img', '')) : URL::webstatic('/images/quickrelease/company_default.png');
                        $tempUser['count'] = Service_Business_UserStat::getInstance()->get_user_answer_count($key);
                        $return[] = $tempUser;

                    }
                }
            }
            $memcache->set($keyCache, $return, 3600);
        }
        return $return;
        
    }

    /**
     * 搜索辅助
     * @author stone shi
     * @return array
     */
    public function _chechSearhPro($list) {
        if(!arr::get($list, 'matches', '')) {
            return array();
        }else {
            $return = array();
           foreach($list['matches'] as $key => $val) {
                    $tempUser = array();
                    $tempUser = array('projecttitle' => $val['val']['projecttitle']);
                    $tempUser['link'] = urlbuilder::qucikProHome($key);
                    $return[] = $tempUser;
                } 
        }
        return $return;
    }
    /**
     * 搜索辅助
     * @author stone shi
     * @return array
     */
    public function _checkSearchList($resultTitle, $resultUser, $resultTag) {
        $return = array('wen' => array(), 'user' => array(), 'title' => $resultTag);
        if(!arr::get($resultTitle, 'matches', '')) {
            $return['wen'] = array();
        }else{
            if($resultTitle['matches']) {
                foreach($resultTitle['matches'] as $key => $val) {
                    $tempUser = array();
                    $tempUser = $val['val'];
                    $numArr = Service_Business_Stat::getInstance()->get_question_nice_against_stat($key);
                    $tempUser['num'] = arr::get(arr::get($numArr, $key), 'answer_count', 0);    
                    $tempUser['href'] = urlbuilder::business_detail($key);
                    $return['wen'][] = $tempUser;
                }
            }
        }
        
        if(!arr::get($resultUser, 'matches', '')) {
            $return['user'] = array();
        }else{
            if($resultUser['matches']) {
                foreach($resultUser['matches'] as $key => $val) {
                    $tempUser = array();
                    $tempUser = $val['val'];
                    $tempUser['signature'] = $this->getUserSignatureById($val['id']);
                    $tempUser['href'] = urlbuilder::business_userinfo($val['id']);
                    $tempUser['img'] = arr::get($tempUser,'img', '') ? URL::imgurl(arr::get($tempUser,'img', '')) : URL::webstatic('/images/quickrelease/company_default.png');
                    $return['user'][] = $tempUser;
                    
                }
            }
        }
        
        return $return;
    }
    /**
     * 用户签名获取
     * @param int $user_id 
     * @author stone shi
     */
    public function getUserSignatureById($user_id) {
        $user_id = intval($user_id);
        if(!$user_id) return FALSE;
        $orm_arr = ORM::factory("UserSignatureApp")->where('user_id', '=', $user_id)->find()->as_array();
        return $orm_arr ? arr::get($orm_arr, 'introduce', '') : '';
    }
    
    /**
     * 用户签名修改
     * @author stone shi
     */
    public function updateUserSignature($user_id, $signature) {
        $user_id = intval($user_id);
        if(!$user_id ) return FALSE;
        $orm_obj = ORM::factory("UserSignatureApp")->where('user_id', '=', $user_id)->find();
        $orm_obj->user_id = $user_id;
        $orm_obj->introduce = $signature;
        $orm_obj->save();
        return $orm_obj->id ? TRUE : FALSE;
    }
            
    
    /**
     * (搜索时）新增生意帮-搜索记录信息
     * @param unknown_type $content：搜索内容
     * @param unknown_type $user_id：用户id
     */
    public function insert($content,$user_id)
    {
    	$search_model=ORM::factory("Business_Search");
    	$search_model->content=$content;
    	$search_model->user_id=$user_id;
    	$search_model->update_time=time();
    	$search_model->create();
    	return $search_model->id;
    }
    
}
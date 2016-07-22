<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 新平台帮助中心页面
 * @author 钟涛
 *
 */

class Controller_Platform_Help extends Controller_Platform_Template{

    /**
     * 帮助中心-关于馒头网
     * @author 钟涛
     */
    public function action_aboutus(){
        $content = View::factory('platform/help/help_about');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '新手投资赚钱项目指导|一句话-投资赚钱好项目，一句话的事';
        $this->template->description = '“一句话网”为投资者专业、快速、精准匹配适合自身投资赚钱的好项目，创业投资赚钱就是这么简单。投资赚钱好项目，一句话的事。';
        $this->template->keywords = '一句话，投资赚钱好项目';
    }

    /**
     * 帮助中心-免责声明
     * @author 钟涛
     */
    public function action_mianze(){
        $content = View::factory('platform/help/help_disclaimer');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '免责声明';
    }

    /**
     * 帮助中心-客户须知
     * @author 钟涛
     */
    public function action_xuzhi(){
        $content = View::factory('platform/help/help_instruction');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '客户须知';
    }
    
    /**
     * 帮助中心-网站特色
     * @author 钟涛
     */
    public function action_tese(){
    	$content = View::factory('platform/help/help_webtese');
    	$this->content->maincontent  = View::factory('platform/help/help_left');
    	$this->content->maincontent->help_maincontent=$content;
    	//记录contrller_方法名
    	$this->content->maincontent->actionmethod = $this->request->action();
    	$this->template->title = '客户须知';
    }

    /**
     * 帮助中心-活跃度指数与会员等级
     * @author 钟涛
     */
    public function action_huoyuedu(){
        $content = View::factory('platform/help/help_huoyuedu');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '活跃度指数与会员等级';
    }

    /**
     * 帮助中心-诚信与安全
     * @author 钟涛
     */
    public function action_chengxin(){
    	$content = View::factory('platform/help/help_security');
    	$this->content->maincontent  = View::factory('platform/help/help_left');
    	$this->content->maincontent->help_maincontent=$content;
    	//记录contrller_方法名
    	$this->content->maincontent->actionmethod = $this->request->action();
    	$this->template->title = '诚信与安全';
    }
    
    
    /**
     * 帮助中心-诚信指数与会员等级
     * @author 钟涛
     */
    public function action_qzhishuji(){
        $content = View::factory('platform/help/help_honesty');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '诚信指数与会员等级';
    }
    
    /**
     * 帮助中心-隐私声明
     * @author 钟涛
     */
    public function action_yinsi(){
        $content = View::factory('platform/help/help_privacy');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '隐私声明';
    }

    /**
     * 帮助中心-用户反馈
     * @author 钟涛
     */
    public function action_fankui(){
        $content = View::factory('platform/help/help_userback');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $ser=new Service_Platform_Help();
        $count=$ser->getliuyanCount(Request::$client_ip);
        if($count>2){
            $this->content->maincontent->help_maincontent->isshowyanzhengma = true;
        }else{
            $this->content->maincontent->help_maincontent->isshowyanzhengma = false;
        }
        $this->template->title = '用户反馈';
    }


    /**
     * 帮助中心-联系方式
     * @author 钟涛
     */
    public function action_lianxi(){
        $content = View::factory('platform/help/help_contact');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '联系方式';
    }

    /**
     * 帮助中心-企业中心-注册与激活
     * @author 钟涛
     */
    public function action_qzhuce(){
        $content = View::factory('platform/help/help_regist_active_comp');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '注册与激活';
    }

    /**
     * 帮助中心-企业中心-忘记密码怎么办
     * @author 钟涛
     */
    public function action_qmima(){
        $content = View::factory('platform/help/help_forgotpass_comp');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '忘记密码';
    }

    /**
     * 帮助中心-企业中心-如何搜索投资者
     * @author 钟涛
     */
    public function action_qsoutouzi(){
        $content = View::factory('platform/help/help_search_invest');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '如何搜索投资者';
    }

    /**
     * 帮助中心-各人中心-忘记密码怎么办
     * @author 钟涛
     */
    public function action_gmima(){
        $content = View::factory('platform/help/help_forgotpass_person');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '忘记密码';
    }


    /**
     * 帮助中心-企业中心-如何发布招商项目
     * @author 钟涛
     */
    public function action_qfabu(){
        $content = View::factory('platform/help/help_releasing');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '如何发布招商项目';
    }
    
    /**
     * 帮助中心-企业中心-如何参加网络展会
     * @author 钟涛
     */
    public function action_qcanzhan(){
    	$content = View::factory('platform/help/help_canzhan');
    	$this->content->maincontent  = View::factory('platform/help/help_left');
    	$this->content->maincontent->help_maincontent=$content;
    	//记录contrller_方法名
    	$this->content->maincontent->actionmethod = $this->request->action();
    	$this->template->title = '如何参加网络展会';
    }
    
    /**
     * 帮助中心-企业中心-如何搜索生意信息
     * @author 钟涛
     */
    public function action_howsearch(){
    	$content = View::factory('platform/help/help_howsearch');
    	$this->content->maincontent  = View::factory('platform/help/help_left');
    	$this->content->maincontent->help_maincontent=$content;
    	//记录contrller_方法名
    	$this->content->maincontent->actionmethod = $this->request->action();
    	$this->template->title = '如何搜索生意信息';
    }
    /**
     * 帮助中心-企业中心-如何搜索生意信息
     * @author 钟涛
     */
    public function action_howpbi(){
    	$content = View::factory('platform/help/help_howpbi');
    	$this->content->maincontent  = View::factory('platform/help/help_left');
    	$this->content->maincontent->help_maincontent=$content;
    	//记录contrller_方法名
    	$this->content->maincontent->actionmethod = $this->request->action();
    	$this->template->title ='如何发布/管理生意信息';
    }
    
    public function action_howpbi2(){
    	$content = View::factory('platform/help/help_howpbi2');
    	$this->content->maincontent  = View::factory('platform/help/help_left');
    	$this->content->maincontent->help_maincontent=$content;
    	//记录contrller_方法名
    	$this->content->maincontent->actionmethod = $this->request->action();
    	$this->template->title ='如何发布/管理生意信息';
    }
    

    /**
     * 帮助中心-个人中心-注册与激活
     * @author 钟涛
     */
    public function action_gzhuce(){
        $content = View::factory('platform/help/help_regist_active_person');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '注册与激活';
    }

    /**
     * 帮助中心-个人中心-如何搜索项目
     * @author 钟涛
     */
    public function action_gsousuo(){
        $content = View::factory('platform/help/help_search_pro');
        $this->content->maincontent  = View::factory('platform/help/help_left');
        $this->content->maincontent->help_maincontent=$content;
        //记录contrller_方法名
        $this->content->maincontent->actionmethod = $this->request->action();
        $this->template->title = '如何搜索项目';
    }

    /**
     * 更新企业基本信息来源
     */
    public function action_updateComSourceByZhongtao(){
        $posts=$this->request->query();
        if(arr::get($posts,'count') && arr::get($posts,'offset')){
            if(arr::get($posts,'offset')==='first'){
                $projectMod = ORM::factory('Project')->where('isrenling_project','=', 0)->where('project_source','!=', 1)->where('project_status','=',2)->group_by('com_id')->limit(200)->find_all();
                //$ormModel = ORM::factory('Companyinfo')->order_by('com_id', 'DESC')->limit(200)->find_all();
                echo count($projectMod->as_array()).'<br>';
                foreach($projectMod as $vv){
                    if($vv->com_id){
                        $orm2Model = ORM::factory('Companyinfo',$vv->com_id);
                        if($orm2Model->com_source_id!=$vv->project_source){
                            echo '之前id:'.$orm2Model->com_source_id.',';
                            $orm2Model->com_source_id=$vv->project_source;
                            $orm2Model->update();
                            echo '修改之后id:'.$orm2Model->com_source_id.'<br>';
                        }
                    }
                }
            }else{
                $projectMod = ORM::factory('Project')->where('isrenling_project','=', 0)->where('project_source','!=', 1)->where('project_status','=',2)->group_by('com_id')->limit(arr::get($posts,'count'))->offset(arr::get($posts,'offset'))->find_all();
                           echo count($projectMod->as_array()).'<br>';
                        foreach($projectMod as $vv){
                            if($vv->com_id){
                                $orm2Model = ORM::factory('Companyinfo',$vv->com_id);
                                if($orm2Model->com_source_id!=$vv->project_source){
                                    echo '之前id:'.$orm2Model->com_source_id.',';
                                    $orm2Model->com_source_id=$vv->project_source;
                                    $orm2Model->update();
                                    echo '修改之后id:'.$orm2Model->com_source_id.'<br>';
                                }
                            }
                        }
            }

            echo '更新数据完毕！';
            exit;
        }else{
            echo '请输入参数';
            exit;
        }
    }

    /**
     * 更新图片路径
     */
    public function action_updateimagepathbyzhongtao(){
        exit;
        $posts=$this->request->query();
        if(arr::get($posts,'count') && arr::get($posts,'offset')){
                if(arr::get($posts,'offset')==='first'){
                    //更新company表的com_logo字段
                    $ormModel = ORM::factory('Companyinfo')->where('com_logo','like','%company_logo/%')->order_by('com_id', 'DESC')->limit(500)->find_all();

                }else{
                    //更新company表的com_logo字段
                    $ormModel = ORM::factory('Companyinfo')->where('com_logo','like','%company_logo/%')->order_by('com_id', 'DESC')->limit(arr::get($posts,'count'))->offset(arr::get($posts,'offset'))->find_all();
                }
            //$ormModel = ORM::factory('Companyinfo')->where('com_logo','like','%poster/html/%')->find_all();
            echo count($ormModel->as_array()).'<br>';
            foreach ($ormModel as $v1){
                $projectMod = ORM::factory('Project')->where('com_id','=', $v1->com_id)->where('project_source','=', 4)->where('outside_id','!=', 0)->find();
                //echo  $v1->com_logo.'<br>';
                if($projectMod->outside_id && stristr($v1->com_logo, 'poster')=== FALSE){
                    echo  $v1->com_logo.'<br>';
                    $v1->com_logo='poster/html/ps_'. $projectMod->outside_id . '/' .$v1->com_logo;
                    $bb=$v1->update();
                    echo $bb->com_logo.'<br>';
                }
            }
            echo '更新数据完毕！';
            exit;
        }else{
            echo '请输入参数';
        }
        //还原更新
//         foreach ($ormModel as $v1){
//             $projectMod = ORM::factory('Project')->where('com_id','=', $v1->com_id)->where('project_source','=', 4)->where('outside_id','!=', 0)->find();
//             if($projectMod->outside_id){
//                 $ps = explode("company_logo", $v1->com_logo);
//                 if(isset( $ps[1])){
//                          $v1->com_logo='company_logo' .$ps[1];
//                      $v1->update();
//                      echo $v1->com_logo.'<br>';
//                 }
//             }
//         }
    }


    public function action_update33(){
        exit;
        $posts=$this->request->query();
        if(arr::get($posts,'count') && arr::get($posts,'offset')){
            if(arr::get($posts,'offset')==='first'){
                //更新项目logo
                $ormprologo = ORM::factory('Project')->where('project_source','=', 4)->where('outside_id','!=', 0)->where('project_logo','like','%project_logo/%')->order_by('project_id', 'DESC')->limit(500)->find_all();
            }else{
                //更新项目logo
                $ormprologo = ORM::factory('Project')->where('project_source','=', 4)->where('outside_id','!=', 0)->where('project_logo','like','%project_logo/%')->order_by('project_id', 'DESC')->limit(arr::get($posts,'count'))->offset(arr::get($posts,'offset'))->find_all();
            }
            //$ormprologo = ORM::factory('Project')->where('project_source','=', 4)->where('outside_id','!=', 0)->where('project_logo','like','%poster/html%')->find_all();
            echo count($ormprologo->as_array()).'<br>';
            foreach ($ormprologo as $v2){
                //echo $v2->project_logo.'<br>';
                $aa= substr($v2->project_logo,0,strlen($v2->project_logo)-3);
                //echo substr($v2->project_logo,-3,0);
                if(substr($v2->project_logo,-3,3)=='jpg'){
                    $v2->project_logo=$aa.'.jpg';
                    $v2->update();
                    echo $v2->project_logo.'<br>';
                }elseif(substr($v2->project_logo,-3,3)=='gif'){
                    $v2->project_logo=$aa.'.gif';
                    $v2->update();
                    echo $v2->project_logo.'<br>';
                }
            }
        }
    }

    public function action_updateimagepathbyzhongtao9(){
        exit;
            $ormprologo = ORM::factory('Project')->where('project_source','=', 4)->where('outside_id','!=', 0)->where('project_logo','like','%project_logo/%')->order_by('project_id', 'DESC')->find_all();

            //$ormprologo = ORM::factory('Project')->where('project_source','=', 4)->where('outside_id','!=', 0)->where('project_logo','like','%poster/html%')->find_all();
            echo count($ormprologo->as_array()).'<br>';
            foreach ($ormprologo as $v2){
                echo $v2->project_logo.'<br>';
            }
    }
    public function action_updateimagepathbyzhongtao2(){
        exit;
        $posts=$this->request->query();
        if(arr::get($posts,'count') && arr::get($posts,'offset')){
            if(arr::get($posts,'offset')==='first'){
                //更新项目logo
                $ormprologo = ORM::factory('Project')->where('project_source','=', 4)->where('outside_id','!=', 0)->where('project_logo','like','%project_logo/%')->order_by('project_id', 'DESC')->limit(500)->find_all();
            }else{
                //更新项目logo
                $ormprologo = ORM::factory('Project')->where('project_source','=', 4)->where('outside_id','!=', 0)->where('project_logo','like','%project_logo/%')->order_by('project_id', 'DESC')->limit(arr::get($posts,'count'))->offset(arr::get($posts,'offset'))->find_all();
            }
            //$ormprologo = ORM::factory('Project')->where('project_source','=', 4)->where('outside_id','!=', 0)->where('project_logo','like','%poster/html%')->find_all();
            echo count($ormprologo->as_array()).'<br>';
            foreach ($ormprologo as $v2){
                if($v2->outside_id && stristr($v2->project_logo, 'poster')=== FALSE){
                    $v2->project_logo='poster/html/ps_'. $v2->outside_id . '/' .$v2->project_logo;
                    $v2->update();
                    echo $v2->project_logo.'<br>';
                }
            }
        }

            exit;
        //还原更新
//         foreach ($ormprologo as $v2){
//             if($v2->outside_id){
//                 $ps = explode("project_logo", $v2->project_logo);
//                 if(isset( $ps[1])){
//                     echo $v2->project_logo.'<br>';
//                     $ps2 = explode(".jpg", $ps[1]);
//                     echo $ps2[0].'<br>';
//                      $v2->project_logo='project_logo' .$ps2[0].'jpg';
//                      $v2->update();
//                      echo $v2->project_logo.'<br>';
//                 }
//             }
//         }
        echo '更新数据完毕！';
        exit;
    }


    /**
     * 查询项目表加盟详情中字段用word文档编辑的
     */
    public function action_selectprojectbyzhongtao(){
        $ormprologo2 = ORM::factory('Project')->where('project_join_conditions','like','%宋体%')->find_all();
        foreach ($ormprologo2 as $v3){
            if($v3->project_join_conditions){
                echo 'project_id:'.$v3->project_id.'----';
                echo 'urladress:<a target="_Blank" href="'.URL::website('').'/project/projectinfo/'.$v3->project_id.'.htm">'.$v3->project_id.'</a><br>';
            }
        }
        echo 'end';
        exit;
    }

    /**
     * ceshi
     */
    public function action_projecttest(){
        //所有通过审核的项目
        $project=ORM::factory('Project')->select('*')->where('project_status','=',2)->find_all();
        $q=1;
        foreach($project as $val){
            //附属表所有通过审核的项目
            $project2=ORM::factory('ProjectSearchCard')->where('project_id','=',$val->project_id)->find();
            if($project2->project_status!=2){
                echo $q.':ProjectSearchCard表错误数据项目id：'.$val->project_id.'；项目名：'.$val->project_brand_name.'<br>';
                if($project2->project_status){
                    $b=$project2->project_status;
                }else{
                    $b='空';
                }
                echo '错误原因：主表是是通过审核的状态为2；但附属表不是通过审核的状态为：'.$b.'<br>';
                echo '<br>';
                $q++;
            }
        }
        echo '----------------------------------------------------------';
        echo '<br>';
        foreach($project as $val){
            //附属表所有通过审核的项目
            $project3=ORM::factory('Projectarea')->where('project_id','=',$val->project_id)->find();
            if($project3->status!=2){
                if($project3->status){
                    $b=$project3->status;
                }else{
                    $b='空';
                }
                echo $q.':ProjectArea表错误数据项目id：'.$val->project_id.'；项目名：'.$val->project_brand_name.'<br>';
                echo '错误原因：主表是是通过审核的状态为2；但附属表不是通过审核的状态为：'.$b.'<br>';
                echo '<br>';
                $q++;
            }
        }

        echo '----------------------------------------------------------';
        echo '<br>';
        foreach($project as $val){
            //附属表所有通过审核的项目
            $project4=ORM::factory('ProjectConnection')->where('project_id','=',$val->project_id)->find();
            if($project4->status!=2){
                echo $q.':czzs_project_connection表错误数据项目id：'.$val->project_id.'；项目名：'.$val->project_brand_name.'<br>';
                $q++;
            }
        }
        echo '----------------------------------------------------------';
        echo '<br>';
        foreach($project as $val){
            //附属表所有通过审核的项目
            $project5=ORM::factory('projectIndustry')->where('project_id','=',$val->project_id)->find();
            if($project5->status!=2){
                echo $q.':czzs_project_industry表错误数据项目id：'.$val->project_id.'；项目名：'.$val->project_brand_name.'<br>';
                $q++;
            }
        }
        echo '----------------------------------------------------------';
        echo '<br>';
        foreach($project as $val){
            //附属表所有通过审核的项目
            $project6=ORM::factory('ProjectModel')->where('project_id','=',$val->project_id)->find();
            if($project6->status!=2){
                echo $q.':czzs_project_model表错误数据项目id：'.$val->project_id.'；项目名：'.$val->project_brand_name.'<br>';
                $q++;
            }
        }
        exit;

    }

    /**
     * ceshi
     */
    public function action_projecttest2(){
        //所有通过审核的项目
        $project=ORM::factory('ProjectSearchCard')->select('*')->where('project_status','=',2)->find_all();
        $q=1;
        foreach($project as $val){
            $project2=ORM::factory('Project')->select('*')->where('project_id','=',$val->project_id)->find();
            if($project2->project_status!=2){
                echo $q.':ProjectSearchCard表错误数据项目id：'.$val->project_id.'；项目名：'.$project2->project_brand_name.'，原因是：';
                echo '项目附属表状态为2，项目主表的状态为：'.$project2->project_status.'<br>';
                $q++;
            }
        }
        echo '----------------------------------------------------------';
        echo '<br>';
        $project22=ORM::factory('Projectarea')->select('*')->where('status','=',2)->find_all();
        foreach($project22 as $val){
            //附属表所有通过审核的项目
            $project3=ORM::factory('Project')->select('*')->where('project_id','=',$val->project_id)->find();
            if($project3->project_status!=2){
                echo $q.':ProjectArea表错误数据项目id：'.$val->project_id.'；项目名：'.$project3->project_brand_name.'<br>';
                $q++;
            }
        }

        echo '----------------------------------------------------------';
        echo '<br>';
        $project44=ORM::factory('ProjectConnection')->select('*')->where('status','=',2)->find();
        foreach($project44 as $val){
            //附属表所有通过审核的项目
            $project4=ORM::factory('Project')->select('*')->where('project_id','=',$val->project_id)->find();
            if($project4->project_status!=2){
                echo $q.':czzs_project_connection表错误数据项目id：'.$val->project_id.'；项目名：'.$project4->project_brand_name.'<br>';
                $q++;
            }
        }
        echo '----------------------------------------------------------';
        echo '<br>';
        $project55=ORM::factory('projectIndustry')->select('*')->where('status','=',2)->find();
        foreach($project55 as $val){
            //附属表所有通过审核的项目
            $project5=ORM::factory('Project')->select('*')->where('project_id','=',$val->project_id)->find();
            if($project5->status!=2){
                echo $q.':czzs_project_industry表错误数据项目id：'.$val->project_id.'；项目名：'.$project5->project_brand_name.'<br>';
                $q++;
            }
        }
        echo '----------------------------------------------------------';
        echo '<br>';
        $project66=ORM::factory('ProjectModel')->select('*')->where('status','=',2)->find();
        foreach($project66 as $val){
            //附属表所有通过审核的项目
            $project6=ORM::factory('Project')->select('*')->where('project_id','=',$val->project_id)->find();
            if($project6->status!=2){
                echo $q.':czzs_project_model表错误数据项目id：'.$val->project_id.'；项目名：'.$project6->project_brand_name.'<br>';
                $q++;
            }
        }
        exit;
    }

    /**
     * 个人宣传服务
     *
     * @author 龚湧
     */
    public function action_grfw(){
        $content = View::factory("user/person/promotion");
        $this->content->maincontent = $content ;
        $this->template->title = '个人服务_投资创业服务政策_一句话';
        $this->template->description = '这里是个人服务页面，一句话个人投资创业服务为所有创业者提供最好的服务。想创业就上一句话投资创业平台，找项目，一句话的事。';
        $this->template->keywords = '个人服务，投资创业服务，一句话';
    }

    /**
     * 企业宣传服务
     *
     * @author 龚湧
     */
    public function action_qyfw(){
        $content = View::factory("user/company/promotion");
        $this->content->maincontent = $content ;
        $this->template->title = '企业服务_企业服务政策一句话';
        $this->template->description = '一句话投资创业平台不仅提供个人服务，还提供企业服务，全面的企业服务政策让个人投资创业者、企业加盟等不再忧虑。完善的体系、安全的模式赢得广大企业的认可。';
        $this->template->keywords = '企业服务，一句话';
    }
}

<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 自己调用api 获取前台标签信息
 * @author 潘宗磊
 *
 */
class Service_Api_Invest extends Service_Api_Basic{

    /**
    * 根据招商会ID 获取浏览次数
    * @author 潘宗磊  update by 花文刚
    * @return array
    */
    public function getBiInvest($id) {
        $post = array('invest_id' => $id);
        $arr= $this->getApiReturn($this->apiUrl['getViewsByInvestId'],$post);
        return $arr;
    }
    
/***start采集875招商会信息接口***/
    /**
     * 采集875招商会信息接口
     * @author周进
     * @param string $url 外采地址
     * @param int $time 起始时间
     * @param int $limit 页容量默认为10
     * @param int $offset 开始位置默认为0
     * @param int $investment_type 外采来源 默认2为来自875
     * @return array()
     */

    public function getInvest($url, $time, $limit = 100, $offset = 0, $investment_type = 2)
    {
        $arr = array();
        $url = $url != "" ? $url : "http://man.875.cn/rest_meeting/postMeetingList";
        $time = $time != "" ? $time : time();
        $post = array(
            'aftertime' => $time,
            'limit' => $limit,
            'offset' => $offset,
        );
        $result = $this->getApiReturn($url, $post);
        $checkcode = $this->checkCode(arr::get($result, 'code'));
        if ($checkcode['status'] == false) {
            return $checkcode;
        }
        $project = new Service_Platform_Project();
        foreach ($result['result'] as $k => $v) {
            //echo 'outside_id: '.$v['pid'].'***project_name: '.$v['pname'].'***investment_start: '.$v['investment_start'].'***investment_area: '.$v['investment_area']."\n";
            $outside = $this->findProjectoutsideByPid($v['pid']);
            if (!$this->findInvestmentByOutid($v['outside_investment_id'])) {
                $projectinfo = $this->getProjectInfoByOutsideID($v['pid']);
                if ($projectinfo == false) { //||$projectinfo->project_source!=$investment_type 来源判断拿掉
                    if ($outside->status == "") {
                        //该外采项目不在我们数据库中，生成日志表数据  pid pname
                        $investmentlog = ORM::factory('Projectoutside');
                        $investmentlog->outside_project_id = $v['pid'];
                        $investmentlog->outside_project_name = $v['pname'];
                        $investmentlog->status = 2; //项目表没数据的
                        $investmentlog->addtime = time();
                        try {
                            $investmentlog->create();
                        } catch (Kohana_Exception $e) {
                            //不做处理
                        }
                    } else {
                        $investmentlog = ORM::factory('Projectoutside', $outside->outside_id);
                        $investmentlog->addtime = time();
                        try {
                            $investmentlog->update();
                        } catch (Kohana_Exception $e) {
                            //不做处理
                        }
                    }
                } else { //数据库已经存在该项目信息，操作招商会信息
                    if ($project->getCompanyByProjectID($projectinfo->project_id) == false) {
                        //公司信息未完善的
                        if ($outside->status == "") {
                            //该外采项目不在我们数据库中，生成日志表数据  pid pname
                            $investmentlog = ORM::factory('Projectoutside');
                            $investmentlog->outside_project_id = $v['pid'];
                            $investmentlog->outside_project_name = $v['pname'];
                            $investmentlog->status = 3; //公司信息未完善的
                            $investmentlog->addtime = time();
                            try {
                                $investmentlog->create();
                            } catch (Kohana_Exception $e) {
                                //不做处理
                            }
                        } else {
                            $investmentlog = ORM::factory('Projectoutside', $outside->outside_id);
                            $investmentlog->addtime = time();
                            try {
                                $investmentlog->update();
                            } catch (Kohana_Exception $e) {
                                //不做处理
                            }
                        }
                    } else {
                        /**/
                        $data = array();

                        $Search = new Service_Api_Search();
                        $participle = $Search->getParticiple($v['investment_address']);
                        //获取省份的id
                        $province = 0;
                        $city = 0;

                        foreach ($participle as $p) {
                            $rs_city = $this->findCityByName($p);
                            if ($rs_city['pro_id'] && $rs_city['pro_id'] != 0) {
                                $province = $rs_city['pro_id'];
                                $city = $rs_city['cit_id'];
                                break;
                            }
                        }

                        //省市字段暂时放0
                        $data['investment_province'] = $province;
                        $data['investment_city'] = $city;
                        //数据库新增两个字段
                        $data['outside_investment_id'] = $v['outside_investment_id'];
                        $data['investment_type'] = 2; //招商会来源(1本站，2表示875，3生意街，4外采)

                        $data['project_id'] = $projectinfo->project_id;
                        $data['com_id'] = $projectinfo->com_id;
                        $data['com_name'] = $v['com_name'];
                        $data['com_phone'] = $v['com_phone'];
                        $data['investment_logo'] = $v['investment_logo']; //暂存图片，之后再批量抓取生成替换
                        $data['investment_name'] = $v['meet_title'];
                        $data['investment_address'] = $v['investment_address'];
                        $data['investment_details'] = $v['investment_details'];
                        $data['investment_agenda'] = '参照招商会详情'; //招商会议流程
                        $data['investment_start'] = strtotime($v['investment_start']);
                        $data['investment_end'] = strtotime($v['investment_end']);
                        $data['putup_type'] = 2;

                        $data['investment_status'] = 1; //招商会申请状态0申请中，1申请通过；2申请失败；3已开始，进行中；4已删除
                        $data['investment_apply'] = 0;
                        $data['investment_isadd'] = 0; //判断招商是编辑还是申请0为申请1为编辑
                        $data['investment_addtime'] = time();

                        //虚拟意向人数
                        $data['virtual_viewer']=rand(200,500);

                        $invest = ORM::factory('Projectinvest');
                        foreach ($data as $h => $j) {
                            if (is_array($j) === false) {
                                $invest->$h = trim($j);
                            }
                        }
                        try {
                            $investment = $invest->create();
                            echo "import success:" . $v['meet_title'] . "\n";
                        } catch (Kohana_Exception $e) {

                        }


                        //更新czzs_project_outside表信息
                        if ($outside->status == "") {
                            //该外采项目不在我们数据库中，生成日志表数据  pid pname
                            $investmentlog = ORM::factory('Projectoutside');
                            $investmentlog->outside_project_id = $v['pid'];
                            $investmentlog->outside_project_name = $v['pname'];
                            $investmentlog->status = 1;
                            $investmentlog->addtime = time();
                            try {
                                $investmentlog->create();
                            } catch (Kohana_Exception $e) {
                                //不做处理
                            }
                        } else {
                            $investmentlog = ORM::factory('Projectoutside', $outside->outside_id);
                            $investmentlog->status = 1;
                            $investmentlog->addtime = time();
                            try {
                                $investmentlog->update();
                            } catch (Kohana_Exception $e) {
                                //不做处理
                            }
                        }
                    }
                }
            }
            //endelse
        }
        //endforeach
        return $arr;
    }
    
    /**
     * 返回状态码匹配
     * @author周进
     */
    public function checkCode($code){
    	$result = array('status'=>false,'msg'=>'未知错误');
    	switch ($code){
    		case '200':
    			$result = array('status'=>true,'msg'=>'请求成功');
    			break;
    		case '400':
    			$result = array('status'=>false,'msg'=>'请求参数错误');
    			break;
    		case '401':
    			$result = array('status'=>false,'msg'=>'请求未授权/未通过身份验证');
    			break;
    		case '403':
    			$result = array('status'=>false,'msg'=>'请求被拒绝（恶意访问、超过配额等）');
    			break;
    		case '404':
    			$result = array('status'=>false,'msg'=>'请求的方法未找到');
    			break;
    		case '500':
    			$result = array('status'=>false,'msg'=>'服务器错误');
    			break;
    	}
    	return $result;
    }
    
    /**
     * 根据名称查找CITY表的数据对应的ID
     * @author 周进
     */
    public function findCityByName($name=''){
    	$city = ORM::factory('City');
    	$result = $city->where('cit_name', 'like', trim($name).'%')->find()->as_array();
    	return $result;
    }

    /**
     * 根据外来项目名返回项目信息
     * @author 
     */
    public function getProjectInfoByName($project_name){
    	$project = ORM::factory('Project')->where('project_brand_name','=',$project_name)->where('project_status','=',2)->where('project_source','<','4')->find();
    	if($project->project_id != null){
    		return $project;
    	}
    	return false;
    }
    /**
     * 根据项目ID返回项目信息
     * @author 周进
     * project_source = 2 确保是来自875的项目 @花文刚
     */
    public function getProjectInfoByOutsideID($outside_id){
    	$project=ORM::factory('Project')->where('outside_id','=',$outside_id)->where('project_status','=',2)->where_open()->where("project_source",'=',6)->or_where('project_source','=','2')->where_close()->find();
    	if($project->project_id != null){
    		return $project;
    	}
    	return false;
    }
    /**
     * 根据外来ID查找是否已经存在对应的外采招商会
     * @author 周进
     */
    public function findInvestmentByOutid($outside_investment_id='0'){
    	$invest = ORM::factory('Projectinvest');	
    	$result = $invest->where('outside_investment_id', '=', $outside_investment_id)->find();
    	if ($result->investment_id!=""){
    		return true;
    	}
    	return false;
    }
    
    /**
     * 根据外来ID判断是否已经存在数据库了
     * @author 周进
     */
    public function findProjectoutsideByPid($pid=0){
    	//该外采项目在不在我们数据库中
    	return ORM::factory('Projectoutside')->where('outside_project_id', '=', $pid)->where('project_type', '=', 1)->find();
    }
    
    /**
     * 查找招商会数据表外采招商会的图片信息，如果图片地址为http://地址 抓取相应的图片到我们服务器 并更新数据库对应图片地址
     * @author周进
     */
    public function checkInvestmentImg(){
    	$invest = ORM::factory('Projectinvest');
    	$result = $invest->where('investment_logo', 'like', 'http%')->where('investment_type','!=','1')->find_all();
    	
    	if (count($result)>0){
    		//抓取图片并通过SOAP上传至图片服务器
    		foreach ($result as $k=>$v){
    			$outside_investment_img = $v->investment_logo;
    			$data = array ();
    			$arr_newData = explode ( "/", $outside_investment_img );
    			$files = array ();
    			$files ['investment_logo'] ['tmp_name'] =  $outside_investment_img;
    			$files ['investment_logo'] ['size'] = '120000';
    			$files ['investment_logo'] ['name'] = end ( $arr_newData );
    			$files ['investment_logo'] ['type'] = 'image/jpeg';
    			$files ['investment_logo'] ['error'] = '0';
    			$result_image = common::uploadPic ( $files, 'investment_logo', array ('w' => 200,'h' => 150));
    			if ($result_image ['error'] == '') { // 添加成功
    				$picurl = Arr::get ( $result_image, 'path' );
    				if ($picurl!=""){
    					$puc_url = explode("/", $picurl,4);
    					$investNew = ORM::factory('Projectinvest',$v->investment_id);
    					$investNew->investment_logo = $puc_url[3];
    					try {
    						$investNew->update();
    					} catch (Kohana_Exception $e) {
    						//不做处理
    					}
    				}
    			}
    		}
    	}
    }
/***end采集875招商会信息接口***/
}

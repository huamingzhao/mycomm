<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人招商会管理
 * @author 潘宗磊
 *
 */
class Service_Platform_Invest{

    /**
     * 获取推荐招商会时间
     * @author 潘宗磊
     */
    public function recomandTime(){
        $now = time();
        $invest = ORM::factory("Projectinvest")->join('project','LEFT')->on('projectinvest.project_id','=','project.project_id')->where('project.project_status','=',2)->where('investment_status','=',1)->where('investment_start', ">=", $now)->order_by('investment_status','desc')->order_by('investment_start','asc')->limit(1)->find();
        return $invest->investment_start;
    }


    /**
     * 前台搜索招商会
     * @author 潘宗磊
     * @add param $type=calendar日历monthly月
     * @return array
     */
    public function searchPlatformInvestment($form,$type="calendar"){
    	$type = $type==""?"calendar":$type;
        $model = DB::select()->from('project_investment');
        $time = time();
        if(arr::get($form,'parent_id') != ''){//行业Projectindustry
        	$model->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id');
        	$model->and_where('project_industry.industry_id', '=', arr::get($form,'parent_id'));
        }
        if(arr::get($form,'areaid') != ''){
             $model->and_where_open()->where('investment_province', '=', arr::get($form,'areaid'))->or_where('investment_city', '=', arr::get($form,'areaid'))->and_where_close();
        }
        if(arr::get($form,'investment_start') != ''&&$type=="calendar"){
            $model->where('investment_start', '=', arr::get($form,'investment_start'));
        }
        if(arr::get($form,'investment_start') != ''&&$type=="monthly"){
            $model->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($form,'investment_start').'))'));
            $model->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($form,'investment_start').'))'));
        }
        $model->where('investment_start','>=',$time)->where('investment_status','=',1)->order_by('investment_start','asc');

        $count = $model->execute()->count();
        $key = isset ( $_GET ['p'] ) ? 'p' : 'page';
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 10,
       			'current_page' => array('source' => 'touzikaocha', 'key' => $key)
        ));
        $array=array();

        $array['list'] = $model->limit($page->items_per_page)->offset($page->offset)->execute()->as_array();
        $array['page']= $page;
        $array['count']= $count;
        return $array;
    }

    /**
     * 前台推荐招商会(新增市级不存在推荐到省级的功能)
     * @author 周进
     * @add param $type=calendar日历monthly月
     * @return array
     */
    public function recomandInvest($search,$where,$type="calendar"){
    	$type = $type==""?"calendar":$type;
        $public = new Service_Public();
        //先判断参数个数
        //---------------------start三个参数的情况
        if (arr::get($search, 'areaid')!=""&&arr::get($search, 'investment_start')!=""&&arr::get($search, 'parent_id')!=""){
            //两个条件并列查询 城市&&时间
            if ($type=="monthly"){
            	$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
            	->where('project_status','=','2')
            	->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
            	->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
            	->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
            	->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
            	->execute()->as_array();
            }
            else{
	            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	            ->where('project_status','=','2')
	            ->and_where('investment_start','=',arr::get($search, 'investment_start'))
	            ->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
	            ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
	            ->execute()->as_array();
            }
            if ($sum[0]['sum']>0){
                unset($search['parent_id']);
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                $result = $this->searchPlatformInvestment($search,"monthly");
                return array($result,$where);
            }else{
                //先处理推荐时间与市关联的上一级省的查找 start
            	if ($type=="monthly"){
            		$area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
            		$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
            		->where('project_status','=','2')
            		->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
            		->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
            		->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
            		->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
            		->execute()->as_array();
            	}else{
	                $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
	                $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	                ->where('project_status','=','2')->and_where('investment_start','=',arr::get($search, 'investment_start'))->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
	                ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
	                ->execute()->as_array();
            	}
                if ($sum[0]['sum']>0){
                    unset($search['parent_id']);
                    $search['areaid'] = $area_id;
                    $where['indust'] = array('in_id'=>'','in_name'=>'');
                    $where['area'] = $this->getAreaName($area_id);
                    $result = $this->searchPlatformInvestment($search,"monthly");
                    return array($result,$where);
                }
                //end
                //两个条件并列查询 城市&&类型
                $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
                ->where('project_status','=','2')->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
                ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))
                ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
                ->execute()->as_array();
                if ($sum[0]['sum']>0){
                    unset($search['investment_start']);
                    $result = $this->searchPlatformInvestment($search,"monthly");
                    $where['time'] = "";
                    return array($result,$where);
                }
                else{
                    //先处理推荐时间与市关联的上一级省的查找 start
                    $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
                    $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                    ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
                    ->where('project_status','=','2')->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))
                    ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
                    ->execute()->as_array();
                    if ($sum[0]['sum']>0){
                        unset($search['investment_start']);
                        $search['areaid'] = $area_id;
                        $result = $this->searchPlatformInvestment($search,"monthly");
                        $where['area'] = $this->getAreaName($area_id);
                        $where['time'] = "";
                        return array($result,$where);
                    }
                    //end
                    //两个条件并列查询 时间&&类型
                    if ($type=="monthly"){
                    	$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                    	->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
                    	->where('project_status','=','2')
						->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
            			->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
                    	->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
                    	->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->execute()->as_array();
                    }else{
	                    $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	                    ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
	                    ->where('project_status','=','2')->and_where('investment_start','=',arr::get($search, 'investment_start'))->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
	                    ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->execute()->as_array();
                    }
                    if ($sum[0]['sum']>0){
                        unset($search['areaid']);
                        $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                        $result = $this->searchPlatformInvestment($search,"monthly");
                        return array($result,$where);
                    }
                    else{
                        //三种情况下对应的两两推荐都不存在的情况 执行时间推荐
                        unset($search['areaid']);
                        unset($search['parent_id']);
                        $search['investment_start'] = $this->recomandTime();
                        $result = $this->searchPlatformInvestment($search,"monthly");
                        $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                        $where['indust'] = array('in_id'=>'','in_name'=>'');
                        $where['time'] = date('Y-m',$search['investment_start']);
                        return array($result,$where);
                    }
                }
            }
        }
        //---------------------end三个参数的情况
        //---------------------start两参数的情况
        //两个条件并列查询 城市&&时间
        if (arr::get($search, 'areaid')!=""&&arr::get($search, 'investment_start')!=""){
        	if ($type=="monthly"){
        		$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
        		->where('project_status','=','2')
        		->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
				->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
        		->execute()->as_array();
        	}else{
	            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	            ->where('project_status','=','2')->and_where('investment_start','=',arr::get($search, 'investment_start'))
	            ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
	            ->execute()->as_array();
        	}
            if ($sum[0]['sum']>0){
                unset($search['parent_id']);
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                $result = $this->searchPlatformInvestment($search,"monthly");
                return array($result,$where);
            }else{
                //先处理推荐时间与市关联的上一级省的查找 start
                $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
                $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                ->where('project_status','=','2')->and_where('investment_start','=',arr::get($search, 'investment_start'))
                ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
                ->execute()->as_array();
                if ($sum[0]['sum']>0){
                    unset($search['parent_id']);
                    $search['areaid'] = $area_id;
                    $where['indust'] = array('in_id'=>'','in_name'=>'');
                    $where['area'] = $this->getAreaName($area_id);
                    $result = $this->searchPlatformInvestment($search,"monthly");
                    return array($result,$where);
                }
                //end
                //两个并不满足的 直接推荐时间
                unset($search['areaid']);
                unset($search['investment_start']);
                $search['investment_start'] = $this->recomandTime();
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //两个条件并列查询 城市&&类型
        elseif (arr::get($search, 'areaid')!=""&&arr::get($search, 'parent_id')!=""){
            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
            ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
            ->where('project_status','=','2')->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))
            ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
            ->execute()->as_array();
            if ($sum[0]['sum']>0){
                unset($search['investment_start']);
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['time'] ='';
                return array($result,$where);
            }else {
                //先处理推荐时间与市关联的上一级省的查找 start
                $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
                $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
                ->where('project_status','=','2')->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
                ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))
                ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
                ->execute()->as_array();
                if ($sum[0]['sum']>0){
                    unset($search['investment_start']);
                    $search['areaid'] = $area_id;
                    $result = $this->searchPlatformInvestment($search,"monthly");
                    $where['area'] = $this->getAreaName($area_id);
                    $where['time'] ='';
                    return array($result,$where);
                }
                //end
                //两个并不满足的 直接推荐时间
                unset($search['areaid']);
                unset($search['parent_id']);
                $search['investment_start'] = $this->recomandTime();
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //两个条件并列查询 时间&&类型
        elseif (arr::get($search, 'investment_start')!=""&&arr::get($search, 'parent_id')!=""){
        	if ($type=="monthly"){
        		$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
        		->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
        		->where('project_status','=','2')
        		->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
				->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
        		->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->execute()->as_array();
        	}else{
	            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	            ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
	            ->where('project_status','=','2')
	            ->and_where('investment_start','=',arr::get($search, 'investment_start'))
	            ->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)
	            ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->execute()->as_array();
        	}
            if ($sum[0]['sum']>0){
                unset($search['areaid']);
                unset($search['investment_province']);
                unset($search['investment_city']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $result = $this->searchPlatformInvestment($search,"monthly");
                return array($result,$where);
            }else { //两个并不满足的 直接推荐时间
                unset($search['investment_start']);
                unset($search['parent_id']);
                $search['investment_start'] = $this->recomandTime();
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //---------------------end两参数的情况
        //---------------------start一个参数的情况
        //单个条件查询 城市
        if (arr::get($search, 'areaid')!=""){
            $rows['areaid'] = ORM::factory('Projectinvest')->join('project','LEFT')->on('project.project_id','=','projectinvest.project_id')
            ->where('project_status','=','2')->and_where('investment_start','>=',time())->where('investment_status','=',1)
            ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()->count_all();
            if ($rows['areaid']>0){
                unset($search['investment_start']);
                unset($search['parent_id']);
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                $where['time'] = '';
                return array($result,$where);
            }else{
                //先处理推荐时间与市关联的上一级省的查找 start
                $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
                $rows['areaid'] = ORM::factory('Projectinvest')->join('project','LEFT')->on('project.project_id','=','projectinvest.project_id')
            ->where('project_status','=','2')->and_where('investment_start','>=',time())->where('investment_status','=',1)
                ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()->count_all();
                if ($rows['areaid']>0){
                    unset($search['investment_start']);
                    unset($search['parent_id']);
                    $search['areaid'] = $area_id;
                    $result = $this->searchPlatformInvestment($search,"monthly");
                    $where['indust'] = array('in_id'=>'','in_name'=>'');
                    $where['time'] = '';
                    $where['area'] = $this->getAreaName($area_id);
                    return array($result,$where);
                }
                //end
                unset($search['parent_id']);
                unset($search['areaid']);
                unset($search['investment_province']);
                unset($search['investment_city']);
                unset($search['investment_start']);
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['time'] = '';
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');

                return array($result,$where);
            }
        }
        //单个条件查询 时间
        elseif (arr::get($search, 'investment_start')!=""){
        	if ($type=="monthly"){
        		$rows['time'] = ORM::factory('Projectinvest')->join('project','LEFT')->on('project.project_id','=','projectinvest.project_id')
        		->where('project_status','=','2')->and_where('investment_start', '>=', time())->where('investment_status','=',1)
        		->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
				->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->count_all();
        	}else{
	            $rows['time'] = ORM::factory('Projectinvest')->join('project','LEFT')->on('project.project_id','=','projectinvest.project_id')
	            ->where('project_status','=','2')->and_where('investment_start', '>=', time())->where('investment_status','=',1)
	            ->where('investment_start', '=', arr::get($search, "investment_start"))
	            ->count_all();
        	}
            if($rows['time']>0){
                unset($search['areaid']);
                unset($search['parent_id']);
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }else{
                unset($search['parent_id']);
                unset($search['areaid']);
                $search['investment_start'] = $this->recomandTime();
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //单个条件查询 类型
        elseif (arr::get($search, 'parent_id')!=""){
            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
            ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
            ->where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->and_where('project_status','=','2')->and_where('investment_start', '>=', time())->and_where('investment_status','=',1)->execute()->as_array();
            $rows['industry'] = $sum[0]['sum'];
            if($rows['industry']>0){
                unset($search['areaid']);
                unset($search['investment_province']);
                unset($search['investment_city']);
                unset($search['investment_start']);
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['time'] = '';
                return array($result,$where);
            }else{
                unset($search['parent_id']);
                unset($search['areaid']);
                unset($search['investment_province']);
                unset($search['investment_city']);
                $search['investment_start'] = $this->recomandTime();
                $result = $this->searchPlatformInvestment($search,"monthly");
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //---------------------end一个参数的情况

        //其他任何情况直接推荐时间
        $search['investment_start'] = $this->recomandTime();
        $result = $this->searchPlatformInvestment($search,"monthly");
        $where['time'] = date('Y-m',$search['investment_start']);
        $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
        $where['indust'] = array('in_id'=>'','in_name'=>'');
        /*****end*********/
        return array($result,$where);
    }
    /**
     * 返回所需要的格式输出
     * @author 潘宗磊
     */
    public function getResaultList($result,$com_id){
        $resault_array = array();
        if(isset($result)){
        foreach ($result as $k=>$r){
            if($r['project_id']){
                $now=time();
                $province=ORM::factory('City',$r['investment_province'])->cit_name;
                $city=ORM::factory('City',$r['investment_city'])->cit_name;
                if ($r['investment_type']==2&&$r['outside_investment_id']!="")
                    $resault_array[$k]['investment_address'] = $r['investment_address'];
                else
                    $resault_array[$k]['investment_address'] = $province.$city.$r['investment_address'];
                $resault_array[$k]['investment_start'] = date('Y.m.d',$r['investment_start']);
                $resault_array[$k]['investment_end'] = date('Y.m.d',$r['investment_end']);
                $spantime=floor(($r['investment_start']+24*60*60-$now)/(24*3600));

                $resault_array[$k]['investment_id'] = $r['investment_id'];
                $resault_array[$k]['project_id'] = $r['project_id'];
                $resault_array[$k]['investment_name'] = $r['investment_name'];
                $resault_array[$k]['investment_name_original'] = $r['investment_name'];
                $resault_array[$k]['investment_logo'] = URL::imgurl($r['investment_logo']);
                $string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars($r['investment_details'], 0)));
                $resault_array[$k]['investment_details'] = mb_strimwidth(strip_tags($string), 0, 40, "......");
                $resault_array[$k]['spantime'] = $spantime;

                //招商会统计浏览次数
                $service = new Service_User_Company_Project();
                $view_num = $service->getInvestmentHaveWatch($r['investment_id']);


                //招商会意向人数 new = 虚拟+真实
                $resault_array[$k]['visit_num'] = $view_num + $r['virtual_viewer'];

                $bakup = ORM::factory("Investbakup",$r['investment_id']);
                if($bakup->invest_id && $r['com_id'] == $com_id){
                    $bak_content = unserialize($bakup->content);

                    $resault_array[$k]['investment_logo']=URL::imgurl($bak_content['investment_logo']);
                    $resault_array[$k]['investment_name']=$bak_content['investment_name'];
                    $resault_array[$k]['investment_start']=date('Y.m.d',$bak_content['investment_start']);
                    $resault_array[$k]['investment_end']=date('Y.m.d',$bak_content['investment_end']);
                    $province=ORM::factory('City',$bak_content['investment_province'])->cit_name;
                    $city=ORM::factory('City',$bak_content['investment_city'])->cit_name;
                    if ($r['investment_type']==2&&$r['outside_investment_id']!="")
                        $resault_array[$k]['investment_address'] = $bak_content['investment_address'];
                    else
                        $resault_array[$k]['investment_address'] = $province.$city.$bak_content['investment_address'];

                    $spantime=floor(($bak_content['investment_start']+24*60*60-$now)/(24*3600));
                    $resault_array[$k]['spantime'] = $spantime;

                }


            }
            }
        }
        return $resault_array;
    }

    /**
     * 读取省级地区列表
     * @author 潘宗磊
     */
    public function getArea($pro_id=0){
        $msg = array();
        $areas=ORM::factory("City")->where("pro_id","=",$pro_id)->find_all();
        foreach ($areas as $k=>$v){
            $msg[$k]['cit_id']=$v->cit_id;
            $msg[$k]['cit_name']=$v->cit_name;
        }
        return $msg;
    }

    /**
     * 读取地区信息
     * @author 潘宗磊
     */
    public function getAreaName($area_id){
        $msg = array();
        $areas=ORM::factory("City",$area_id);
        $msg['cit_id']=$areas->cit_id;
        $msg['cit_name']=$areas->cit_name;
        return $msg;
    }

    /**
     * 读取行业信息
     * @author 潘宗磊
     */
    public function getIndustryName($in_id){
        $msg = array();
        $industry=ORM::factory("Industry",$in_id);
        $msg['in_id']=$industry->industry_id;
        $msg['in_name']=$industry->industry_name;
        return $msg;
    }

    /**
     * 读取即将开始的招商会
     * @author 潘宗磊
     */
    public function getInvestNum(){
        $model = DB::select()->from('project_investment');
        $model->where('investment_start','>=',time())->where('investment_status','=',1);
        $num = $model->execute()->count();
        return $num;
    }

    /**
     * 读取即将结束的招商会
     * @author 潘宗磊
     */
    public function getHistoryInvestNum(){
        $now = time();
        $num =  ORM::factory("Projectinvest")->where('investment_status','=',1)->where('investment_start', "<", $now)->count_all();
        return $num;
    }

    /**
     * 日历获取当月招商会数据 用于JSON数据
     * @author周进
     * @param $date date 2013-07-17
     */
    public function getIndustryNumBydate($date=0,$date_end=0){
        if ($date_end==0)
            $result = DB::select('investment_num',array(DB::expr('FROM_UNIXTIME(investment_date)'),'investment_date'))->from('investment_count')
                ->and_where('investment_date','=',$date)->execute()
                ->as_array();
        else{
            $begin_time = strtotime($date_end.'-01');
            $end_time = strtotime(date('Y-m-d', mktime(23, 59, 59, date('m', strtotime($date_end))+1, 00)));
            $result = DB::select('investment_num',array(DB::expr('FROM_UNIXTIME(investment_date)'),'investment_date'))->from('investment_count')
                ->and_where('investment_date', '>=', $begin_time)
                ->and_where('investment_date', '<=', $end_time)
                ->execute()
                ->as_array();
        }
        return $result;
    }

    /**
     * 更新日历对应的每天的统计数据
     * @author 花文刚
     */
    public function updateIndustryNumBydate(){
        $invest_count = ORM::factory('Investcount')->order_by('investment_date','DESC')->find()->as_array();
        $today = strtotime("today");
        if($invest_count['investment_date']){
            $last = $invest_count['investment_date'];
        }
        else{
            $last = $today;
        }
        $deadline = $today<$last?$today:$last;

        //查询
        $query=DB::select(array(DB::expr('COUNT(investment_id)'),'sum'),array('investment_province','investment_city'),array('investment_start','investment_start'))
            ->from('project_investment')
            ->and_where('investment_status','=','1')
            ->and_where('investment_start','>=',$deadline)
            ->group_by('investment_start');
        //echo $query;  //可查看执行的sql
        $query = $query->execute()->as_array();
        foreach ($query as $k=>$v){
            //查询investment_count表存在更新,不存在加入
            $count = ORM::factory('Investcount')
                ->where('investment_date','=',$v['investment_start'])
                ->find()->as_array();
            if ($count['count_id']>0){
                $Investmentcount = ORM::factory('Investcount',$count['count_id']);
                $Investmentcount->investment_date = $v['investment_start'];
                $Investmentcount->investment_city = $v['investment_city'];
                $Investmentcount->investment_industry = 0;
                $Investmentcount->investment_num = $v['sum'];
                $Investmentcount->update();
            }
            else{
                $Investmentcount = ORM::factory('Investcount');
                $Investmentcount->investment_date = $v['investment_start'];
                $Investmentcount->investment_city = $v['investment_city'];
                $Investmentcount->investment_industry = 0;
                $Investmentcount->investment_num = $v['sum'];
                $Investmentcount->create();
            }
        }
    }

    /**
     * 获取有效招商会id
     * @author 潘宗磊
     */
    public function getProjectInvestId(){
        $id=array();
        $invest = ORM::factory("Projectinvest")->join('project','LEFT')->on('projectinvest.project_id','=','project.project_id')->where('project.project_status','=',2)->where('investment_status','=',1)->find_all();
        foreach ($invest as $v){
            $id[]=$v->investment_id;
        }
        return $id;
    }
/*******************--------------------------搜索历史招商会-----------------------------*******************/
    /**
     * 前台搜索历史招商会
     * @author 周进
     * @param $data array
     * @return array
     */
    public function searchHistoryInvestment($date=array(),$type="calendar",$com_id){
        $result = DB::select()->from('project_investment')
        //->join('project_search_card','LEFT')->on('project_search_card.project_id','=','project_investment.project_id')
        ->join('project','left')->on('project.project_id','=','project_investment.project_id');
        if(arr::get($date,'parent_id') != ''){//行业Projectindustry
        	$result->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id');
        	$result->and_where('project_industry.industry_id', '=', arr::get($date,'parent_id'));
        }
        if(arr::get($date,'areaid') != ''){//城市
            $result->and_where_open()->where('investment_province', '=', arr::get($date,'areaid'))->or_where('investment_city', '=', arr::get($date,'areaid'))->and_where_close();
        }
        if(arr::get($date,'investment_start') != ''&&$type=="calendar"){//年份月份日期
            $result->and_where('investment_start', '=', arr::get($date,'investment_start'));
        }
        if(arr::get($date,'investment_start') != ''&&$type=="monthly"){//年份月份
        	$result->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($date,'investment_start').'))'));
        	$result->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($date,'investment_start').'))'));
        }
        $result->where('investment_status','=',1)->and_where('investment_start','<',time())->and_where('project.project_status','=',2);
        $result->order_by('investment_start','desc');//->group_by("investment_id")
        $count = $result->execute()->count();
        if($date){
	        $page = Pagination::factory(array(
	                'total_items'    => $count,
	                'items_per_page' => 10, 
	        ));
        }else{
	        $page = Pagination::factory(array(
	                'total_items'    => $count,
	                'items_per_page' => 10,
	        		'current_page' => array('source' => 'lishitouzikaocha', 'key' => 'page')
	        ));
        }        
        $array=array();
        $list = $result->limit($page->items_per_page)->offset($page->offset)->execute()->as_array();
        $resault_array = array();
        foreach ($list  as $k=>$r){
            $province=ORM::factory('City',$r['investment_province'])->cit_name;
            $city=ORM::factory('City',$r['investment_city'])->cit_name;
            $resault_array[$k]['investment_id'] = $r['investment_id'];
            $resault_array[$k]['project_id'] = $r['project_id'];
            $resault_array[$k]['investment_start'] = $r['investment_start'];
            $resault_array[$k]['investment_end'] = $r['investment_end'];
            $resault_array[$k]['investment_name'] = $r['investment_name'];
            $resault_array[$k]['investment_logo'] = URL::imgurl($r['investment_logo']);
            $string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars($r['investment_details'], 0)));
            $string = str_replace('&nbsp;','',$string);
            $resault_array[$k]['investment_details'] = mb_strimwidth(strip_tags($string), 0, 40, "......");
            $resault_array[$k]['bobao_sign'] = 11;
            if ($r['investment_type']==2&&$r['outside_investment_id']!="")
            	$resault_array[$k]['investment_address'] = $r['investment_address'];
            else
            	$resault_array[$k]['investment_address'] = $province.$city.$r['investment_address'];
            //判断招商会是否播报
            $bobao = ORM::factory("Bobao",$r['investment_id']);
            if($bobao->bobao_status==2){
                $resault_array[$k]['investment_sign']=floor($bobao->bobao_sign/$bobao->bobao_num*100);
            }

            //招商会统计浏览次数
            $service = new Service_User_Company_Project();
            $view_num = $service->getInvestmentHaveWatch($r['investment_id']);

            //招商会意向人数 new = 虚拟+真实
            $resault_array[$k]['visit_num'] = $view_num + $r['virtual_viewer'];

            //招商会报名人数
            $apply_real = ORM::factory('Applyinvest')->where('invest_id','=',$r['investment_id'])->count_all();
            $apply_virtual = $r['investment_virtualapply'];
            $apply_num = $r['com_id'] == $com_id ? $apply_real : ($apply_real+$apply_virtual);
            $resault_array[$k]['investment_apply'] = $apply_num;

        }
        $array['list']=$resault_array;
        $array['page']= $page;
        $array['count']= $count;
        return $array;
    }

    /**
     * 获取推荐历史招商会时最近时间
     */
    public function recomandHistoryTime(){
        $now = time();
        $invest = ORM::factory("Projectinvest")->join('project','LEFT')->on('projectinvest.project_id','=','project.project_id')->where('project.project_status','=',2)->and_where('investment_status','=',1)->and_where('investment_start', "<", $now)->order_by('investment_start','desc')->limit(1)->find();
        return $invest->investment_start;
    }

    /**
     * 前台推荐历史招商会
     * @author 周进
     * @param array $search array('areaid'=>'城市ID','parent_id'=>'类型ID','investment_start'=>'时间')
     * @param array $where array(
     * 						'indust' => array('in_id'=>'','in_name'=>''),推荐类型
     * 						'area'   => $this->getAreaName($area_id),推荐地区
     *						'time'   => $this->recomandHistoryTime(),推荐月份 离今天最近的历史招商会
     * 					)
     * @return array
     */
    public function recomandHistoryInvest($search,$where,$type="calendar",$com_id){
        $public = new Service_Public();
        //先判断参数个数
        //---------------------start三个参数的情况
        if (arr::get($search, 'areaid')!=""&&arr::get($search, 'investment_start')!=""&&arr::get($search, 'parent_id')!=""){
            //两个条件并列查询 城市&&时间
        	if ($type=="monthly"){
        		$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
        		->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
        		->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
        		->execute()->as_array();
        	}else{
	            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	            ->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
	            ->and_where('investment_start', '=', arr::get($search,'investment_start'))
	            ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
	            ->execute()->as_array();
        	}
            if ($sum[0]['sum']>0){
                unset($search['parent_id']);
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                return array($result,$where);
            }else{
                //先处理推荐时间与市关联的上一级省的查找 start
                $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
                if ($type=="monthly"){
                	$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                	->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
                	->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
                	->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
                	->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
                	->execute()->as_array();
                }else{
	                $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	                ->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
	                ->and_where('investment_start', '=', arr::get($search,'investment_start'))
	                ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
	                ->execute()->as_array();
                }
                if ($sum[0]['sum']>0){
                    unset($search['parent_id']);
                    $search['areaid'] = $area_id;
                    $where['indust'] = array('in_id'=>'','in_name'=>'');
                    $where['area'] = $this->getAreaName($area_id);
                    $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                    return array($result,$where);
                }
                //end
                //两个条件并列查询 城市&&类型
                $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
                ->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
                ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))
                ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
                ->execute()->as_array();
                if ($sum[0]['sum']>0){
                    unset($search['investment_start']);
                    $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                    $where['time'] = "";
                    return array($result,$where);
                }
                else{
                    //先处理推荐时间与市关联的上一级省的查找 start
                    $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
                    $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                    ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
                    ->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
                    ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))
                    ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
                    ->execute()->as_array();
                    if ($sum[0]['sum']>0){
                        unset($search['investment_start']);
                        $search['areaid'] = $area_id;
                        $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                        $where['area'] = $this->getAreaName($area_id);
                        $where['time'] = "";
                        return array($result,$where);
                    }
                    //end
                    //两个条件并列查询 时间&&类型
                    if ($type=="monthly"){
                    	$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                    	->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
                    	->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
                    	->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
                    	->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
                    	->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->execute()->as_array();
                    }else{
	                    $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	                    ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
	                    ->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
	                    ->and_where('investment_start', '=', arr::get($search,'investment_start'))
	                    ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->execute()->as_array();
                    }
                    if ($sum[0]['sum']>0){
                        unset($search['areaid']);
                        $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                        $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                        return array($result,$where);
                    }
                    else{
                        //三种情况下对应的两两推荐都不存在的情况 执行时间推荐
                        unset($search['areaid']);
                        unset($search['parent_id']);
                        $search['investment_start'] = $this->recomandHistoryTime();
                        $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                        $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                        $where['indust'] = array('in_id'=>'','in_name'=>'');
                        $where['time'] = date('Y-m',$search['investment_start']);
                        return array($result,$where);
                    }
                }
            }
        }
        //---------------------end三个参数的情况
        //---------------------start两参数的情况
        //两个条件并列查询 城市&&时间
        if (arr::get($search, 'areaid')!=""&&arr::get($search, 'investment_start')!=""){
        	if ($type=="monthly"){
        		$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
        		->where('project_status','=','2')->and_where('investment_start','<',time())
        		->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
        		->execute()->as_array();
        	}else{
	            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	            ->where('project_status','=','2')->and_where('investment_start','<',time())
	            ->and_where('investment_start', '=', arr::get($search,'investment_start'))
	            ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
	            ->execute()->as_array();
        	}
            if ($sum[0]['sum']>0){
                unset($search['parent_id']);
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                return array($result,$where);
            }else{
                //先处理推荐时间与市关联的上一级省的查找 start
                $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
                if ($type=="monthly"){
                	$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                	->where('project_status','=','2')->and_where('investment_start','<',time())->and_where('investment_status','=',1)
                	->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
                	->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
                	->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
                	->execute()->as_array();
                }else{
	                $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	                ->where('project_status','=','2')->and_where('investment_start','<',time())->and_where('investment_status','=',1)
	                ->and_where('investment_start', '=', arr::get($search,'investment_start'))
	                ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
	                ->execute()->as_array();
                }
                if ($sum[0]['sum']>0){
                    unset($search['parent_id']);
                    $search['areaid'] = $area_id;
                    $where['indust'] = array('in_id'=>'','in_name'=>'');
                    $where['area'] = $this->getAreaName($area_id);
                    $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                    return array($result,$where);
                }
                //end
                //两个并不满足的 直接推荐时间
                unset($search['areaid']);
                unset($search['investment_start']);
                $search['investment_start'] = $this->recomandHistoryTime();
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //两个条件并列查询 城市&&类型
        elseif (arr::get($search, 'areaid')!=""&&arr::get($search, 'parent_id')!=""){
            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
            ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
            ->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
            ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))
            ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()
            ->execute()->as_array();
            if ($sum[0]['sum']>0){
                unset($search['investment_start']);
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['time'] ='';
                return array($result,$where);
            }else {
                //先处理推荐时间与市关联的上一级省的查找 start
                $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
                $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
                ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
                ->where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)
                ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))
                ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()
                ->execute()->as_array();
                if ($sum[0]['sum']>0){
                    unset($search['investment_start']);
                    $search['areaid'] = $area_id;
                    $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                    $where['area'] = $this->getAreaName($area_id);
                    $where['time'] ='';
                    return array($result,$where);
                }
                //end
                //两个并不满足的 直接推荐时间
                unset($search['areaid']);
                unset($search['parent_id']);
                $search['investment_start'] = $this->recomandHistoryTime();
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //两个条件并列查询 时间&&类型
        elseif (arr::get($search, 'investment_start')!=""&&arr::get($search, 'parent_id')!=""){
        	if ($type=="monthly"){
        		$sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
        		->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
        		->where('project_status','=','2')->and_where('investment_start','<',time())->and_where('investment_status','=',1)
        		->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->execute()->as_array();
        	}else{
	            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
	            ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
	            ->where('project_status','=','2')->and_where('investment_start','<',time())->and_where('investment_status','=',1)
	            ->and_where('investment_start', '=', arr::get($search,'investment_start'))
	            ->and_where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->execute()->as_array();
        	}
            if ($sum[0]['sum']>0){
                unset($search['areaid']);
                unset($search['investment_province']);
                unset($search['investment_city']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                return array($result,$where);
            }else { //两个并不满足的 直接推荐时间
                unset($search['investment_start']);
                unset($search['parent_id']);
                $search['investment_start'] = $this->recomandHistoryTime();
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //---------------------end两参数的情况
        //---------------------start一个参数的情况
        //单个条件查询 城市
        if (arr::get($search, 'areaid')!=""){
            $rows['areaid'] = ORM::factory('Projectinvest')->join('project_investment','LEFT')->on('project.project_id','=','projectinvest.project_id')
            ->where('project_status','=','2')->and_where('investment_start','<',time())->where('investment_status','=',1)
            ->and_where_open()->where('investment_province', '=', arr::get($search, 'areaid'))->or_where('investment_city','=',arr::get($search, 'areaid'))->and_where_close()->count_all();
            if ($rows['areaid']>0){
                unset($search['investment_start']);
                unset($search['parent_id']);
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                $where['time'] = '';
                return array($result,$where);
            }else{
                //先处理推荐时间与市关联的上一级省的查找 start
                $area_id = $public->getProidByCityid(arr::get($search, 'areaid'));
                $rows['areaid'] = ORM::factory('Projectinvest')->join('project_investment','LEFT')->on('project.project_id','=','projectinvest.project_id')
                ->where('project_status','=','2')->and_where('investment_start','<',time())->where('investment_status','=',1)
                ->and_where_open()->where('investment_province', '=', $area_id)->or_where('investment_city','=',$area_id)->and_where_close()->count_all();
                if ($rows['areaid']>0){
                    unset($search['investment_start']);
                    unset($search['parent_id']);
                    $search['areaid'] = $area_id;
                    $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                    $where['indust'] = array('in_id'=>'','in_name'=>'');
                    $where['time'] = '';
                    $where['area'] = $this->getAreaName($area_id);
                    return array($result,$where);
                }
                //end
                unset($search['parent_id']);
                unset($search['areaid']);
                unset($search['investment_province']);
                unset($search['investment_city']);
                unset($search['investment_start']);
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['time'] = '';
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //单个条件查询 时间
        elseif (arr::get($search, 'investment_start')!=""){
        	if ($type=="monthly"){
        		$rows['time'] = ORM::factory('Projectinvest')->join('project','LEFT')->on('project.project_id','=','projectinvest.project_id')
        		->where('project_status','=','2')->and_where('investment_start', '<', time())->where('investment_status','=',1)
        		->and_where(DB::expr('year(from_unixtime(investment_start))'), '=', DB::expr('year(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->and_where(DB::expr('month(from_unixtime(investment_start))'), '=', DB::expr('month(from_unixtime('.arr::get($search,'investment_start').'))'))
        		->count_all();
        	}else{
	            $rows['time'] = ORM::factory('Projectinvest')->join('project','LEFT')->on('project.project_id','=','projectinvest.project_id')
	            ->where('project_status','=','2')->and_where('investment_start', '<', time())->where('investment_status','=',1)
	            ->and_where('investment_start', '=', arr::get($search,'investment_start'))
	            ->count_all();
        	}
            if($rows['time']>0){
                unset($search['areaid']);
                unset($search['parent_id']);
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }else{
                unset($search['parent_id']);
                unset($search['areaid']);
                $search['investment_start'] = $this->recomandHistoryTime();
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //单个条件查询 类型
        elseif (arr::get($search, 'parent_id')!=""){
            $sum = DB::select(array(DB::expr('count(investment_id)'),'sum'))->from('project_investment')->join('project','LEFT')->on('project.project_id','=','project_investment.project_id')
            ->join('project_industry','left')->on('project_industry.project_id','=','project_investment.project_id')
            ->where('project_industry.industry_id','=',arr::get($search, 'parent_id'))->and_where('project_status','=','2')->and_where('investment_start', '<', time())->and_where('investment_status','=',1)->execute()->as_array();
            $rows['industry'] = $sum[0]['sum'];
            if($rows['industry']>0){
                unset($search['areaid']);
                unset($search['investment_province']);
                unset($search['investment_city']);
                unset($search['investment_start']);
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['time'] = '';
                return array($result,$where);
            }else{
                unset($search['parent_id']);
                unset($search['areaid']);
                unset($search['investment_province']);
                unset($search['investment_city']);
                $search['investment_start'] = $this->recomandHistoryTime();
                $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
                $where['time'] = date('Y-m',$search['investment_start']);
                $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
                $where['indust'] = array('in_id'=>'','in_name'=>'');
                return array($result,$where);
            }
        }
        //---------------------end一个参数的情况
        //其他任何情况直接推荐时间
        $search['investment_start'] = $this->recomandHistoryTime();
        $result = $this->searchHistoryInvestment($search,'monthly',$com_id);
        $where['time'] = date('Y-m',$search['investment_start']);
        $where['area'] = array('cit_id'=>88,'cit_name'=>'全国');
        $where['indust'] = array('in_id'=>'','in_name'=>'');
        /*****end*********/
        return array($result,$where);
    }


    /**
     * 获取今天正在召开的最后一次招商会
     * @author 花文刚
     */
    public function getTodayLastInvest($project_id){
        $today = strtotime("today");
        $rs =  ORM::factory("Projectinvest")->where('project_id','=',$project_id)->where('investment_status','=',1)->where('investment_start', "=", $today)->order_by('investment_end','DESC')->find()->as_array();
        return $rs;
    }

    /**
     * 设置导入项目和招商会的邮件内容
     * @author 花文刚
     */
    public function getMailContentOfImportLog(){
        $str = "";

        $today = strtotime("today");
        $invest =  ORM::factory("Projectinvest")->where('investment_addtime', ">=", $today)->where('investment_addtime', "<=", $today+24*60*60)->where('investment_type', "=", 2)->find_all();

        $invest_table = "<table>
                             <thead>
                                <tr>
                                    <th>招商会ID</th>
                                    <th>项目ID</th>
                                    <th>项目名称</th>
                                    <th>招商会名称</th>
                                    <th>企业会员ID</th>
                                    <th>导入时间</th>
                                </tr>
                            </thead>
                            <tbody>";
        $num = count($invest);
        foreach($invest as $v){
            $project=ORM::factory('Project',$v->project_id);
            $addtime = date('Y.m.d H:i:s',$v->investment_addtime);
            $invest_table .= " <tr>
                                    <td>$v->investment_id</td>
                                    <td>$v->project_id</td>
                                    <td>$project->project_brand_name</td>
                                    <td>$v->investment_name</td>
                                    <td>$v->com_id</td>
                                    <td>$addtime</td>
                                </tr>";
        }
        $invest_table .= "</tbody>
                        </table>";


        $str = date("Y年m月d日")." 从875成功导入的投资考察会有 $num 条<br/>";
        if($num>0){
            $str .= $invest_table;
        }

        return $str;
    }

}
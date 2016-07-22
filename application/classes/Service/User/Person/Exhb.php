<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 网络展会
 * @author 郁政
 *
 */
class Service_User_Person_Exhb{
	/**
	 * 显示红包列表
	 * @author 郁政
	 *
	 */
	public function getHongBaoList($user_id,$type = 1,$cond = array()){
		//print_r($cond);exit;
		$return = array();
		$res = array();						
		$type = Arr::get($cond, 'type',1);
		$name = Arr::get($cond, 'name','');
		$status = Arr::get($cond, 'status',0);		
		if($type == 1){			
			$c_om = ORM::factory('Exhibition')
					->join('exhb_hongbao_fetch','left')
					->on('exhibition.exhibition_id','=','exhb_hongbao_fetch.exhibition_id')
					->where('exhibition.exhibition_status','=',1)
					->where('exhb_hongbao_fetch.person_id','=',$user_id);
					if($name == '' && $status == 0){
						$count = $c_om->count_all();
					}elseif($name == '' && $status == 1){
						$count = $c_om->where('exhibition.exhibition_start','>',time())
										->count_all();	
					}elseif($name == '' && $status == 2){
						$count = $c_om->where('exhibition.exhibition_start','<=',time())
										->where('exhibition.exhibition_end','>=',time())
										->count_all();	
					}elseif($name == '' && $status == 3){
						$count = $c_om->where('exhibition.exhibition_end','<',time())
										->count_all();	
					}elseif($name != '' && $status == 0){
						$count = $c_om->where('exhibition.exhibition_name','=',$name)
								->count_all();	
					}elseif($name != '' && $status == 1){
						$count = $c_om->where('exhibition.exhibition_name','=',$name)
										->where('exhibition.exhibition_start','>',time())
										->count_all();
					}elseif($name != '' && $status == 2){
						$count = $c_om->where('exhibition.exhibition_name','=',$name)
										->where('exhibition.exhibition_start','<=',time())
										->where('exhibition.exhibition_end','>=',time())
										->count_all();
					}elseif($name != '' && $status == 3){
						$count = $c_om->where('exhibition.exhibition_name','=',$name)
										->where('exhibition.exhibition_end','<',time())									
										->count_all();
					}			
			$page = Pagination::factory ( array (
                'total_items' => $count,
                'items_per_page' => 12
        	));
        	$om = ORM::factory('Exhibition')
        			->join('exhb_hongbao_fetch','left')
        			->on('exhibition.exhibition_id','=','exhb_hongbao_fetch.exhibition_id')
        			->where('exhibition.exhibition_status','=',1)
        			->where('exhb_hongbao_fetch.person_id','=',$user_id);
        			if($name == '' && $status == 0){
        				$hongbao = $om->limit ( $page->items_per_page )
				        			->offset ( $page->offset )
				        			->find_all()
				        			->as_array();   
        			}elseif($name == '' && $status == 1){
        				$hongbao = $om->where('exhibition.exhibition_start','>',time())
        							->limit ( $page->items_per_page )
				        			->offset ( $page->offset )
				        			->find_all()
				        			->as_array();   
        			}elseif($name == '' && $status == 2){
        				$hongbao = $om->where('exhibition.exhibition_start','<=',time())
									->where('exhibition.exhibition_end','>=',time())
        							->limit ( $page->items_per_page )
				        			->offset ( $page->offset )
				        			->find_all()
				        			->as_array();  
        			}elseif($name == '' && $status == 3){
        				$hongbao = $om->where('exhibition.exhibition_end','<',time())									
        							->limit ( $page->items_per_page )
				        			->offset ( $page->offset )
				        			->find_all()
				        			->as_array();  
        			}elseif($name != '' && $status == 0){
        				$hongbao = $om->where('exhibition.exhibition_name','=',$name)									
        							->limit ( $page->items_per_page )
				        			->offset ( $page->offset )
				        			->find_all()
				        			->as_array();  
        			}elseif($name != '' && $status == 1){
        				$hongbao = $om->where('exhibition.exhibition_name','=',$name)
        							->where('exhibition.exhibition_start','>',time())									
        							->limit ( $page->items_per_page )
				        			->offset ( $page->offset )
				        			->find_all()
				        			->as_array();  
        			}elseif($name != '' && $status == 2){
        				$hongbao = $om->where('exhibition.exhibition_name','=',$name)
        							->where('exhibition.exhibition_start','<=',time())
									->where('exhibition.exhibition_end','>=',time())									
        							->limit ( $page->items_per_page )
				        			->offset ( $page->offset )
				        			->find_all()
				        			->as_array();  
        			}elseif($name != '' && $status == 3){
        				$hongbao = $om->where('exhibition.exhibition_name','=',$name)
        							->where('exhibition.exhibition_end','<',time())									
        							->limit ( $page->items_per_page )
				        			->offset ( $page->offset )
				        			->find_all()
				        			->as_array();  
        			}
        			     	
        	if(count($hongbao) > 0){
        		foreach($hongbao as $k => $v){
        			$res[$k]['jine'] = $v->exhibition_hongbao;
        			$res[$k]['type'] = '展会红包';
        			$res[$k]['name'] = $v->exhibition_name;
        			if($v->exhibition_start > time()){
						$res[$k]['status'] = '即将开展';
					}elseif($v->exhibition_start <= time() && $v->exhibition_end >= time()){
						$res[$k]['status'] = '开展中';
					}else{
						$res[$k]['status'] = '已结束';
					}
					$res[$k]['time'] = date('Y-m-d',$v->exhibition_start).'至'.date('m-d',$v->exhibition_end);
					$res[$k]['validity'] = '无限制';
        		}
        	}        	
		}elseif($type == 2){
			$c_om = ORM::factory('ExhbCoupon')
					->join('exhb_project','left')
					->on('exhb_project.project_id','=','exhbcoupon.project_id')
					->join('exhb_exhibition','left')
					->on('exhb_exhibition.exhibition_id','=','exhbcoupon.exhibition_id')
					->where('exhb_exhibition.exhibition_status','=',1)
					->where('exhb_project.project_status','=',1)
					->where('exhbcoupon.person_id','=',$user_id);
					if($name == '' && $status == 0){
						$count = $c_om->count_all();	
					}elseif($name == '' && $status == 1){
						$count = $c_om->where('exhb_exhibition.exhibition_start','>',time())
								->count_all();
					}elseif($name == '' && $status == 2){
						$count = $c_om->where('exhb_exhibition.exhibition_start','<=',time())
								->where('exhb_exhibition.exhibition_end','>=',time())
								->count_all();
					}elseif($name == '' && $status == 3){
						$count = $c_om->where('exhb_exhibition.exhibition_end','<',time())
								->count_all();
					}elseif($name != '' && $status == 0){
						$count = $c_om->where('exhb_project.project_brand_name','=',$name)
								->count_all();
					}elseif($name != '' && $status == 1){
						$count = $c_om->where('exhb_project.project_brand_name','=',$name)
								->where('exhibition.exhibition_start','>',time())
								->count_all();
					}elseif($name != '' && $status == 2){
						$count = $c_om->where('exhb_project.project_brand_name','=',$name)
								->where('exhb_exhibition.exhibition_start','<=',time())
								->where('exhb_exhibition.exhibition_end','>=',time())
								->count_all();
					}elseif($name != '' && $status == 3){	
						$count = $c_om->where('exhb_project.project_brand_name','=',$name)
								->where('exhb_exhibition.exhibition_end','<',time())
								->count_all();
					}				
			$page = Pagination::factory ( array (
                'total_items' => $count,
                'items_per_page' => 12
        	));
        	$om = DB::select('exhb_project.project_id','exhb_project.project_coupon','exhb_project.project_brand_name','exhb_project.coupon_deadline','exhb_exhibition.exhibition_start','exhb_exhibition.exhibition_end')
        				->from('exhb_coupon_fetch')
        				->join('exhb_exhibition','left')
        				->on('exhb_coupon_fetch.exhibition_id','=','exhb_exhibition.exhibition_id')
        				->join('exhb_project','left')
        				->on('exhb_coupon_fetch.project_id','=','exhb_project.project_id')
        				->where('exhb_exhibition.exhibition_status','=',1)
						->where('exhb_project.project_status','=',1)
        				->where('exhb_coupon_fetch.person_id','=',$user_id);
        				if($name == '' && $status == 0){
        					$youhuijuan = $om->limit ( $page->items_per_page )
					        				->offset ( $page->offset )
					        				->execute()
					        				->as_array();
        				}elseif($name == '' && $status == 1){
        					$youhuijuan = $om->where('exhb_exhibition.exhibition_start','>',time())
        									->limit ( $page->items_per_page )
					        				->offset ( $page->offset )
					        				->execute()
					        				->as_array();
        				}elseif($name == '' && $status == 2){
        					$youhuijuan = $om->where('exhb_exhibition.exhibition_start','<=',time())
											->where('exhb_exhibition.exhibition_end','>=',time())
        									->limit ( $page->items_per_page )
					        				->offset ( $page->offset )
					        				->execute()
					        				->as_array();
        				}elseif($name == '' && $status == 3){
        					$youhuijuan = $om->where('exhb_exhibition.exhibition_end','<',time())
        									->limit ( $page->items_per_page )
					        				->offset ( $page->offset )
					        				->execute()
					        				->as_array();
        				}elseif($name != '' && $status == 0){
        					$youhuijuan = $om->where('exhb_project.project_brand_name','=',$name)        									
        									->limit ( $page->items_per_page )
					        				->offset ( $page->offset )
					        				->execute()
					        				->as_array();
        				}elseif($name != '' && $status == 1){  
        					$youhuijuan = $om->where('exhb_project.project_brand_name','=',$name)
        									->where('exhb_exhibition.exhibition_start','>',time())
        									->limit ( $page->items_per_page )
					        				->offset ( $page->offset )
					        				->execute()
					        				->as_array();
        				}elseif($name != '' && $status == 2){ 
        					$youhuijuan = $om->where('exhb_project.project_brand_name','=',$name)
        									->where('exhb_exhibition.exhibition_start','<=',time())
											->where('exhb_exhibition.exhibition_end','>=',time())
        									->limit ( $page->items_per_page )
					        				->offset ( $page->offset )
					        				->execute()
					        				->as_array();
        				}elseif($name != '' && $status == 3){ 
        					$youhuijuan = $om->where('exhb_project.project_brand_name','=',$name)
        									->where('exhb_exhibition.exhibition_end','<',time())
        									->limit ( $page->items_per_page )
					        				->offset ( $page->offset )
					        				->execute()
					        				->as_array();
        				}      				
        	if($youhuijuan){
        		foreach($youhuijuan as $k => $v){
        			$res[$k]['project_id'] = $v['project_id'];
        			$res[$k]['jine'] = $v['project_coupon'];
        			$res[$k]['type'] = '项目优惠劵';
        			$res[$k]['name'] = $v['project_brand_name'];
        			if($v['exhibition_start'] > time()){
						$res[$k]['status'] = '即将开展';
					}elseif($v['exhibition_start'] <= time() && $v['exhibition_end'] >= time()){
						$res[$k]['status'] = '开展中';
					}else{
						$res[$k]['status'] = '已结束';
					}
					$res[$k]['time'] = date('Y-m-d',$v['exhibition_start']).'至'.date('m-d',$v['exhibition_end']);
					$res[$k]['validity'] = $v['coupon_deadline'] ? date('Y-m-d',$v['coupon_deadline']) : '无';
        		}
        	}       
		}
		$return['list'] = $res;
		$return['page'] = $page;
		return $return;
	}
	
}
?>
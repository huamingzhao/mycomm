<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 项目向导
 * @author 郁政
 *
 */
class Controller_Sapi_Platform_ProjectGuide extends Controller_Sapi_Basic{
	/**
     * 获得项目向导排行榜
     * @author 郁政
     *       
     */
	public function action_getRankingList(){
		$post = $this->request->post();
		$service = new Service_Platform_ProjectGuide();
		$top = intval($post['top']);
		$time = intval($post['time']);
		$rankingtype = intval($post['rankingtype']);
		$list = array();		
		try {
			if($rankingtype == 1){
			$approing = $service->getApproingRanking($top,$time,3);
			$list = $service->showNeedRanking($approing);
			}elseif($rankingtype == 2){
				$watch = $service->getWatchRanking($top,$time,3);
				$list = $service->showNeedRanking($watch);
			}elseif($rankingtype == 3){
				$statistics = $service->getClickRanking($top,$time,3);
				$list = $service->showNeedRanking($statistics);
			}else{				
				$approing = $service->getApproingRanking($top,$time,3);				
				$list = $service->showNeedRanking($approing);	
			}			
		} catch (Kohana_Exception $e) {
			 $this->setApiReturn('500', '服务器错误');
		}	
		$this->setApiReturn('200', 'ok', $list);	
		
	}
    /**
     * 获取投资人群的前五条数据
     * @author 嵇烨
     */
    public function action_getProjectListByCrowdId(){
        $tag_id = intval($this->request->post('tag_id'));
        $nums = intval($this->request->post('nums'));
        $server =  new Service_Platform_ProjectGuide();
        #获取数据
        try{
            $list = $server->getProjectListByCrowdId($tag_id,$nums);
        }catch (Kohana_Exception $e){
            return $this->setApiReturn('500', '服务器错误');
        }
            return $this->setApiReturn('200', 'ok', $list);
    }
}
?>

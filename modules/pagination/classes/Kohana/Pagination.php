<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Pagination links generator.
 *
 * @package    Kohana/Pagination
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_Pagination {

    // Merged configuration settings
    protected $config = array(
        'current_page'      => array('source' => 'query_string', 'key' => 'page'),
        'total_items'       => 0,
        'items_per_page'    => 10,
        'view'              => 'pagination/common',
        'auto_hide'         => TRUE,
        'first_page_in_url' => FALSE,
    );
    // Current page number
    protected $current_page;

    // Total item count
    protected $total_items;

    // How many items to show per page
    protected $items_per_page;

    // Total page count
    protected $total_pages;

    // Item offset for the first item displayed on the current page
    protected $current_first_item;

    // Item offset for the last item displayed on the current page
    protected $current_last_item;

    // Previous page number; FALSE if the current page is the first one
    protected $previous_page;

    // Next page number; FALSE if the current page is the last one
    protected $next_page;

    // First page number; FALSE if the current page is the first one
    protected $first_page;

    // Last page number; FALSE if the current page is the last one
    protected $last_page;

    // Query offset
    protected $offset;
    
    /**
     * Creates a new Pagination object.
     *
     * @param   array  configuration
     * @return  Pagination
     */
    public static function factory(array $config = array())
    {
        return new Pagination($config);
    }

    /**
     * Creates a new Pagination object.
     *
     * @param   array  configuration
     * @return  void
     */
    public function __construct(array $config = array())
    {
    	
        // Overwrite system defaults with application defaults
        $this->config = $this->config_group() + $this->config;
        // Pagination setup
        $this->setup($config);
    }

    /**
     * Retrieves a pagination config group from the config file. One config group can
     * refer to another as its parent, which will be recursively loaded.
     *
     * @param   string  pagination config group; "default" if none given
     * @return  array   config settings
     */
    public function config_group($group = 'default')
    {
        // Load the pagination config file
        //$config_file = Kohana::config('pagination');

        // Initialize the $config array
        $config['group'] = (string) $group;

        // Recursively load requested config groups
        while (isset($config['group']) AND isset($config_file->$config['group']))
        {
            // Temporarily store config group name
            $group = $config['group'];
            unset($config['group']);

            // Add config group values, not overwriting existing keys
            $config += $config_file->$group;
        }

        // Get rid of possible stray config group names
        unset($config['group']);

        // Return the merged config group settings
        return $config;
    }

    /**
     * Loads configuration settings into the object and (re)calculates pagination if needed.
     * Allows you to update config settings after a Pagination object has been constructed.
     *
     * @param   array   configuration
     * @return  object  Pagination
     */
    public function setup(array $config = array())
    {
        if (isset($config['group']))
        {
            // Recursively load requested config groups
            $config += $this->config_group($config['group']);
        }

        // Overwrite the current config settings
        $this->config = $config + $this->config;

        // Only (re)calculate pagination when needed
        if ($this->current_page === NULL
            OR isset($config['current_page'])
            OR isset($config['total_items'])
            OR isset($config['items_per_page']))
        {
            // Retrieve the current page number
            if ( ! empty($this->config['current_page']['page']))
            {
                // The current page number has been set manually
                $this->current_page = (int) $this->config['current_page']['page'];
            }
            else
            {
            	//echo $this->config['current_page']['source'];exit;
                switch ($this->config['current_page']['source'])
                {
                    case 'query_string':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                        break;

                    case 'route':
                        $this->current_page = (int) Request::current()->param($this->config['current_page']['key'], 1);
                        break;
                    case 'siteMap':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                        break;
                   case 'zixun':
                            $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                            break;
                    case 'hyxw':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                        break;
                    case 'hyxw_xm':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                        break;
                   case 'search':
                               $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                            break;
                   case 'fenlei':
                               $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                            break;
                   case 'people':
                               $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                            break;
                   case 'diqu':
                               $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                            break;
                   case 'zhaotouzi':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                        ? (int) $_GET[$this->config['current_page']['key']]
                        : 1;
                        break;
                  case 'wendafirst1':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                        ? (int) $_GET[$this->config['current_page']['key']]
                        : 1;
                        break;
                  case 'wendafirst2':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                        ? (int) $_GET[$this->config['current_page']['key']]
                        : 1;
                        break;
                   case 'wendafirst3':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                        ? (int) $_GET[$this->config['current_page']['key']]
                        : 1;
                        break;
                    case 'wendasec1':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                        ? (int) $_GET[$this->config['current_page']['key']]
                        : 1;
                        break;
                    case 'wendasec2':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                        ? (int) $_GET[$this->config['current_page']['key']]
                        : 1;
                        break;
                    case 'wendasec3':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                        ? (int) $_GET[$this->config['current_page']['key']]
                        : 1;
                        break;
                  case 'wendasec':
                        $this->current_page = isset($_GET[$this->config['current_page']['key']])
                        ? (int) $_GET[$this->config['current_page']['key']]
                        : 1;
                        break;
                   case 'touzikaocha':
                               $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                            break;
                   case 'lishitouzikaocha':
                               $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                            break;
                   case 'zhuanlan':
                            $this->current_page = isset($_GET[$this->config['current_page']['key']])
                            ? (int) $_GET[$this->config['current_page']['key']]
                            : 1;
                            break;
                    case 'projectzixun':
                                $this->current_page = isset($_GET[$this->config['current_page']['key']])
                                ? (int) $_GET[$this->config['current_page']['key']]
                                : 1;
                                break;
                    case 'accountlist':
                               $this->current_page = isset($_GET[$this->config['current_page']['key']])
                                ? (int) $_GET[$this->config['current_page']['key']]
                                : 1;
                                break;
                    case 'wenda_user':
                    	$this->current_page = isset($_GET[$this->config['current_page']['key']]) ? (int) $_GET[$this->config['current_page']['key']] : 1;
                    	break;
                    case 'waterfall':
                    	$this->current_page = isset($_GET[$this->config['current_page']['key']]) ? (int) $_GET[$this->config['current_page']['key']] : 1;
                    case 'waterfallorder':
                    	$this->current_page = isset($_GET[$this->config['current_page']['key']]) ? (int) $_GET[$this->config['current_page']['key']] : 1;
                    break;
                    case 'hangyeNewsTag':
                    	$this->current_page = isset($_GET[$this->config['current_page']['key']]) ? (int) $_GET[$this->config['current_page']['key']] : 1;
                	break;
                	case 'quickxiangdao':
                    	$this->current_page = isset($_GET[$this->config['current_page']['key']]) ? (int) $_GET[$this->config['current_page']['key']] : 1;
                	break;
                	case 'quicksearch':
                		$this->current_page = isset($_GET[$this->config['current_page']['key']]) ? (int) $_GET[$this->config['current_page']['key']] : 1;
                	break;
                }
            }

            // Calculate and clean all pagination variables
            $this->total_items        = (int) max(0, $this->config['total_items']);
            $this->items_per_page     = (int) max(1, $this->config['items_per_page']);
            $this->total_pages        = (int) ceil($this->total_items / $this->items_per_page);
            $this->current_page       = (int) min(max(1, $this->current_page), max(1, $this->total_pages));
            $this->current_first_item = (int) min((($this->current_page - 1) * $this->items_per_page) + 1, $this->total_items);
            $this->current_last_item  = (int) min($this->current_first_item + $this->items_per_page - 1, $this->total_items);
            $this->previous_page      = ($this->current_page > 1) ? $this->current_page - 1 : FALSE;
            $this->next_page          = ($this->current_page < $this->total_pages) ? $this->current_page + 1 : FALSE;
            $this->first_page         = ($this->current_page === 1) ? FALSE : 1;
            $this->last_page          = ($this->current_page >= $this->total_pages) ? FALSE : $this->total_pages;
            $this->offset             = (int) (($this->current_page - 1) * $this->items_per_page);
        }

        // Chainable method
        return $this;
    }

    /**
     * Generates the full URL for a certain page.
     *
     * @param   integer  page number
     * @return  string   page URL
     */
    public function url($page = 1)
    {
        // Clean the page number
        $page = max(1, (int) $page);

        // No page number in URLs to first page
        if ($page === 1 AND ! $this->config['first_page_in_url'])
        {
            $page = NULL;
        }
        switch ($this->config['current_page']['source'])
        {
            case 'query_string':
                //echo $request=Request::detect_uri();exit;
                //return URL::site(Request::current()->uri).URL::query(array($this->config['current_page']['key'] => $page));
                return URL::site(Request::detect_uri()).URL::query(array($this->config['current_page']['key'] => $page));				
            case 'route':
                return URL::site(Request::current()->uri(array($this->config['current_page']['key'] => $page))).URL::query();
            case 'siteMap':
                $get = $_GET;
                $type = arr::get($get, 'type', 'A');
                $page = $page ? $page : 1;
                return urlbuilder::siteMap($type, $page);
            case 'zixun':
                $get = $_GET;
                $parent = arr::get($get, 'parent');
                $page = $page ? $page : 1;
                return zxurlbuilder::siteMapPage($parent, $page);
            case 'hyxw':
                $get = $_GET;
                $industry_id = arr::get($get, 'industry_id');
                $page = $page ? $page : 1;
                return zxurlbuilder::siteIndustry($industry_id,$page);
            case 'hyxw_xm':
                $get = $_GET;
                $project_id = arr::get($get, 'projectid');
                $page = $page ? $page : 1;
                return zxurlbuilder::siteIndustryProject($project_id,$page);
            case 'search':
                $get = $_GET;
                $w = Arr::get($get, 'w' , '');
                $per_area_id = Arr::get($get, 'per_area_id' , '');
                $parent_id = Arr::get($get, 'parent_id' , '');
                $per_amount = Arr::get($get, 'per_amount' , '');
                $page = $page ? $page : 1;
                if($per_area_id == "" && $parent_id == "" && $per_amount == ""){
                	return urlbuilder::search($w,$page);
                }else{
                	return urlbuilder::search2($per_area_id,$parent_id,$per_amount,$page);
                }
            case 'zhaotouzi'://找投资者
                $get = $_GET;
                $w = Arr::get($get, 'w' , '');
                $per_area_id = Arr::get($get, 'per_area_id' , '');
                $parent_id = Arr::get($get, 'parent_id' , '');
                $per_amount = Arr::get($get, 'per_amount' , '');
                $page = $page ? $page : 1;
                return urlbuilder::zhaotouzi('zhaotouzi',$w,$page,$per_area_id,$parent_id,$per_amount);
           case 'wendafirst1'://问答模块一级分页【所有】
                $get = $_GET;
                $oneid = Arr::get($get, 'id' , '1');//一级行业id
                $oneid=arr::get(Service_News_Ask::getAskIndustryOneArrById(),$oneid);
                $type = 1;//类型 1：所有 2.已解决 3.待解决
                $page = $page ? $page : 1;
                return urlbuilder::wenda($oneid,0,$page,$type);
            case 'wendafirst2'://问答模块一级分页【已解决】
                $get = $_GET;
                $oneid = Arr::get($get, 'id' , '1');//一级行业id
                $oneid=arr::get(Service_News_Ask::getAskIndustryOneArrById(),$oneid);
                $type = 2;//类型 1：所有 2.已解决 3.待解决
                $page = $page ? $page : 1;
            	return urlbuilder::wenda($oneid,0,$page,$type);
            case 'wendafirst3'://问答模块一级分页【待解决】
            	$get = $_GET;
            	$oneid = Arr::get($get, 'id' , '1');//一级行业id
            	$oneid=arr::get(Service_News_Ask::getAskIndustryOneArrById(),$oneid);
            	$type = 3;//类型 1：所有 2.已解决 3.待解决
            	$page = $page ? $page : 1;
            	return urlbuilder::wenda($oneid,0,$page,$type);
            	
            case 'wendasec1'://问答模块二级分页【所有】
            	$get = $_GET;
            	$oneid = Arr::get($get, 'oneid' , '1');//一级行业id
            	$oneid=arr::get(Service_News_Ask::getAskIndustryOneArrById(),$oneid);
            	$twoid = Arr::get($get, 'id' , '0');//二级行业id
            	$type = 1;//类型 1：所有 2.已解决 3.待解决
            	$page = $page ? $page : 1;
            	return urlbuilder::wenda($oneid,$twoid,$page,$type);
            case 'wendasec2'://问答模块一级分页【已解决】
            	$get = $_GET;
            	$oneid = Arr::get($get, 'oneid' , '1');//一级行业id
            	$oneid=arr::get(Service_News_Ask::getAskIndustryOneArrById(),$oneid);
            	$twoid = Arr::get($get, 'id' , '0');//二级行业id
            	$type = 2;//类型 1：所有 2.已解决 3.待解决
            	$page = $page ? $page : 1;
            	return urlbuilder::wenda($oneid,$twoid,$page,$type);
            case 'wendasec3'://问答模块一级分页【待解决】
            	$get = $_GET;
            	$oneid = Arr::get($get, 'oneid' , '1');//一级行业id
            	$oneid=arr::get(Service_News_Ask::getAskIndustryOneArrById(),$oneid);
            	$twoid = Arr::get($get, 'id' , '0');//二级行业id
            	$type = 3;//类型 1：所有 2.已解决 3.待解决
            	$page = $page ? $page : 1;
            	return urlbuilder::wenda($oneid,$twoid,$page,$type);
            case 'wenda_user':
            		return URL::site(Request::detect_uri()).'?'.$this->config['current_page']['key'].'='.$page;            		
            case 'fenlei':
                $get = $_GET;
                $hy = Arr::get($get, 'pid' , '');
                $zhy = Arr::get($get, 'inid' , '');
                $m = Arr::get($get, 'atype' , '');
                $xs = Arr::get($get, 'pmodel' , '');
                $page = $page ? $page : 1;
                $type = Arr::get($get, 'type' , '');
                $order = Arr::get($get, 'order' , '');
                if($hy == '' && $zhy == '' && $m == '' && $xs == '' && $type == '' && $order == ''){
                    return urlbuilder::fenleiCond(array(),array(),$page);
                }elseif($hy == '' && $zhy == '' && $m == '' && $xs == '' && $type == 1 && $order == 1){
                	return urlbuilder::fenleiOrder(1,$page);
        		}elseif($hy == '' && $zhy == '' && $m == '' && $xs == '' && $type == 1 && $order == 2){
                	return urlbuilder::fenleiOrder(2,$page);
        		}elseif($hy == '' && $zhy == '' && $m == '' && $xs == '' && $type == 2 && $order == 1){
                	return urlbuilder::fenleiOrder(3,$page);
        		}elseif($hy == '' && $zhy == '' && $m == '' && $xs == '' && $type == 2 && $order == 2){
                	return urlbuilder::fenleiOrder(4,$page);
        		}else{
                    return urlbuilder::fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('pid'=>$hy,'inid'=>$zhy,'atype'=>$m,'pmodel'=>$xs),$page);
                }
            case 'people':
                $get = $_GET;
                $type = Arr::get($get, 'type' , '');
                $page = $page ? $page : 1;
                return urlbuilder::guideCorwd($type,$page);
               case 'diqu':
                $get = $_GET;
                $areaid = Arr::get($get, 'areaid' , '');
                $page = $page ? $page : 1;
                return urlbuilder::guideArea($areaid,$page);
               case 'touzikaocha':
                   $get = $_GET;
                   $time = Arr::get($get, 'time' , '');
                   $calendar = Arr::get($get, 'calendar' , '');
                   $from = Arr::get($get, 'from' , '');
                   $in_id = Arr::get($get, 'in_id' , '');
                   $areaid = Arr::get($get, 'areaid' , '');
                   $monthly = Arr::get($get, 'monthly' , '');
                   $cond = array('time'=>$time,'calendar'=>$calendar,'from'=>$from,'in_id'=>$in_id,'areaid'=>$areaid,'monthly'=>$monthly);
                   $page = $page ? $page : 1;
                   return urlbuilder::touzikaocha($page,$cond);
               case 'lishitouzikaocha':               	   
               	   $page = $page ? $page : 1;
               	   return urlbuilder::lishizhaoshang($page);
               case 'zhuanlan':
                   $get = $_GET;
                   $id = Arr::get($get, 'id' , '');
                   $page = $page ? $page : 1;
                   return zxurlbuilder::siteZhuanlan('hot', $page, $id);
               case "projectzixun":
                   $get = $_GET;
                   $page = $page ? $page : 1;
                   $projectid= Arr::get($get, 'projectid' , 0);
                   return zxurlbuilder::siteProjectZixun( $projectid,$page );
               case "accountlist":
                   	$get = $_GET;
                   	$page = $page ? $page : 1;
                   	return urlbuilder::siteaccountlist($page);
               case "waterfall":
                   	$get = $_GET;
                   	$page = $page ? $page : 1;
    				$int_exhibition_id =  intval(arr::get($get, "exhibition_id"));
    				$catalog_id = arr::get($get, "catalog_id");
    				if($int_exhibition_id && empty($catalog_id)){
    					return urlbuilder::exhibitionid($int_exhibition_id, null,$page);
    				}
    				if($catalog_id){
    					return urlbuilder::catalogid($int_exhibition_id,$catalog_id, null,$page);
    				}
    			case "waterfallorder":
    				$get = $_GET;
    				$page = $page ? $page : 1;
    				$int_exhibition_id =  intval(arr::get($get, "exhibition_id"));
    				$catalog_id = arr::get($get, "catalog_id");
    				$int_type = arr::get($get, "type");
    				if($int_exhibition_id && empty($catalog_id)){
    					return urlbuilder::exhibitionid($int_exhibition_id,$int_type,$page);
    				}
    				if($catalog_id){
    					return urlbuilder::catalogid($int_exhibition_id,$catalog_id,$int_type,$page);
    				}
    			case "hangyeNewsTag":
    				$get = $_GET;
    				$page = $page ? $page : 1;
    				$w = arr::get($get, "w");     							
    				return zxurlbuilder::hyxwtag($w,$page);
    			case "quickxiangdao":
    				$get = $_GET;    			
    				$arrPingY = array_flip(common::getAreaPinYin());	
    				$area = arr::get($arrPingY , secure::secureInput(arr::get($_GET, 'area_id', '')),0);			
	                $hy = Arr::get($get, 'industry_id' , '');
	                $m = Arr::get($get, 'atype' , '');
	                $xs = Arr::get($get, 'pmodel' , '');
	                $page = $page ? $page : 1;
        			if($area == '' && $hy == '' && $m == '' && $xs == ''){
	                    return urlbuilder::quickSearchCond(array(),array(),$page);
	                }else{
	                    return urlbuilder::quickSearchCond(array('area_id'=>$area,'industry_id'=>$hy,'atype'=>$m,'pmodel'=>$xs),array('area_id'=>$area,'industry_id'=>$hy,'atype'=>$m,'pmodel'=>$xs),$page);
	                }  
    			case "quicksearch":
    				$get = $_GET; 
    				$w = Arr::get($get, 'w' , '');
    				$page = $page ? $page : 1;
    				return urlbuilder::quickSearchWord($w,$page);
        }
        return '#';
    }

    /**
     * Checks whether the given page number exists.
     *
     * @param   integer  page number
     * @return  boolean
     * @since   3.0.7
     */
    public function valid_page($page)
    {
        // Page number has to be a clean integer
        if ( ! Validate::digit($page))
            return FALSE;

        return $page > 0 AND $page <= $this->total_pages;
    }

    /**
     * Renders the pagination links.
     *
     * @param   mixed   string of the view to use, or a Kohana_View object
     * @return  string  pagination output (HTML)
     */
    public function render($view = NULL)
    {
        // Automatically hide pagination whenever it is superfluous
        if ($this->config['auto_hide'] === TRUE AND $this->total_pages <= 1)
            return '';

        if ($view === NULL)
        {
            // Use the view from config
            $view = $this->config['view'];
        }

        if ( ! $view instanceof Kohana_View)
        {
            // Load the view file
            $view = View::factory($view);
        }

        // Pass on the whole Pagination object
        return $view->set(get_object_vars($this))->set('page', $this)->render();
    }

    /**
     * Renders the pagination links.
     *
     * @return  string  pagination output (HTML)
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Returns a Pagination property.
     *
     * @param   string  URI of the request
     * @return  mixed   Pagination property; NULL if not found
     */
    public function __get($key)
    {
        return isset($this->$key) ? $this->$key : NULL;
    }

    /**
     * Updates a single config setting, and recalculates pagination if needed.
     *
     * @param   string  config key
     * @param   mixed   config value
     * @return  void
     */
    public function __set($key, $value)
    {
        $this->setup(array($key => $value));
    }

    /**
     * @see get limit for mysql use
     *
     */
    public function limit()
    {
        return $this->offset .','.$this->items_per_page;
    }

} // End Pagination

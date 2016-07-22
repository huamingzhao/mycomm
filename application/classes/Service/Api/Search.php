<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 调用搜索引擎的接口
 * @author 施磊
 *
 */
class Service_Api_Search extends Service_Api_Basic {

    /**
     * 项目向导
     * @author 施磊
     */
    public function getGuideNumb($cond) {
        $modProject = new Service_Platform_Project ();
        $return = array('allCond' => array(), 'noIn' => array(), 'noPm' => array(), 'noAt' => array(), 'count' => 0);
        $query = '';
        $pmodel = arr::get($cond, 'pmodel', 0);
        $atype = arr::get($cond, 'atype', 0);
        $inid = arr::get($cond, 'industry_id', 0);
        $inType = !arr::get($cond, 'pid', 0) ? 'industryPidSearch' : 'industrySearch';
        if ($inid) {
            $query[] = "{$inType}:" . urlencode($modProject->getQuestCont(6, $inid));
        }
        if ($pmodel) {
            $query[] = 'pmodel:' . urlencode($modProject->getQuestCont(1, $pmodel));
        }
        if ($atype) {
            $query[] = 'projectAmountType:' . urlencode($modProject->getQuestCont(7, $atype));
        }
        $condQuery = $query;
        $query = $query ? implode('+AND+', $query) : '*:*';
        $post = array('q' => $query, 'wt' => 'json', 'facet' => 'true');
        $allCond = $this->_getGuideNumbByCond($post);
        $param = '';
        if (arr::get($cond, 'pid', 0)) {
            $condQuery[] = "industryPidSearch:" . urlencode($modProject->getQuestCont(6, arr::get($cond, 'pid', 0)));
        } elseif ($inid) {
            $condQuery[] = "industryPidSearch:" . urlencode($modProject->getQuestCont(6, arr::get($cond, 'industry_id', 0)));
        }
        $condQuery = $condQuery ? implode('+AND+', $condQuery) : '*:*';
        $post = array('q' => $condQuery, 'wt' => 'json', 'facet' => 'true');
        $noCond = $this->_getGuideNumbByCond($post);
        if ($post) {
            foreach ($post as $key => $val) {
                $param .= $key . '=' . $val . '&';
            }
        }
        $allCondPost = json_decode(file_get_contents($this->apiUrl['getSearch'] . '?' . $param), true);
        $count = isset($allCondPost['response']['numFound']) ? $allCondPost['response']['numFound'] : 0;
        if (!$inid) {
            $noIn = $allCond;
        } else {
            $query = '';
            if ($pmodel) {
                $query[] = 'pmodel:' . urlencode($modProject->getQuestCont(1, $pmodel));
            }
            if ($atype) {
                $query[] = 'projectAmountType:' . urlencode($modProject->getQuestCont(7, $atype));
            }
            $query = $query ? implode('+AND+', $query) : '*:*';
            $post = array('q' => $query, 'wt' => 'json', 'facet' => 'true');
            $noIn = $this->_getGuideNumbByCond($post);
        }
        $inPid = array();
        $query = '';
        if (arr::get($cond, 'pid', 0)) {
            $query[] = "industryPidSearch:" . urlencode($modProject->getQuestCont(6, arr::get($cond, 'pid', 0)));
        } else {
            $query[] = "industryPidSearch:" . urlencode($modProject->getQuestCont(6, arr::get($cond, 'industry_id', 0)));
        }
        if ($pmodel) {
            $query[] = 'pmodel:' . urlencode($modProject->getQuestCont(1, $pmodel));
        }
        if ($atype) {
            $query[] = 'projectAmountType:' . urlencode($modProject->getQuestCont(7, $atype));
        }
        $query = $query ? implode('+AND+', $query) : '*:*';
        $post = array('q' => $query, 'wt' => 'json', 'facet' => 'true');
        $inPid = $this->_getGuideNumbByCond($post);
        if (!$atype) {
            $noAt = $noCond;
        } else {
            $query = '';
            if ($pmodel) {
                $query[] = 'pmodel:' . urlencode($modProject->getQuestCont(1, $pmodel));
            }
            if ($inid) {
                $query[] = "{$inType}:" . urlencode($modProject->getQuestCont(6, $inid));
            }
            if (arr::get($cond, 'pid', 0)) {
                $query[] = "industryPidSearch:" . urlencode($modProject->getQuestCont(6, arr::get($cond, 'pid', 0)));
            }
            $query = $query ? implode('+AND+', $query) : '*:*';
            $post = array('q' => $query, 'wt' => 'json', 'facet' => 'true');
            $noAt = $this->_getGuideNumbByCond($post);
        }

        if (!$pmodel) {
            $noPm = $noCond;
        } else {
            $query = '';
            if ($inid) {
                $query[] = "{$inType}:" . urlencode($modProject->getQuestCont(6, $inid));
            }
            if ($atype) {
                $query[] = 'projectAmountType:' . urlencode($modProject->getQuestCont(7, $atype));
            }
            if (arr::get($cond, 'pid', 0)) {
                $query[] = "industryPidSearch:" . urlencode($modProject->getQuestCont(6, arr::get($cond, 'pid', 0)));
            }
            $query = $query ? implode('+AND+', $query) : '*:*';
            $post = array('q' => $query, 'wt' => 'json', 'facet' => 'true');
            $noPm = $this->_getGuideNumbByCond($post);
        }
        $return = array('allCond' => $allCond, 'inPid' => $inPid, 'noIn' => $noIn, 'noPm' => $noPm, 'noAt' => $noAt, 'count' => $count);
        return $return;
    }

    /**
     *
     * @param 施磊
     * 楼上的辅助方法 私有的 别用
     */
    private function _getGuideNumbByCond($post, $p = 0) {
        //$return = $this->getApiReturnByGet($this->apiUrl['getSearch'], $post);
        $param = '';
        if ($post) {
            foreach ($post as $key => $val) {
                $param .= $key . '=' . $val . '&';
            }
        }
        $return = json_decode(file_get_contents($this->apiUrl['getSearch'] . '?' . $param), true);
        $allNum = array();
        if (isset($return['facet_counts']['facet_fields'])) {
            foreach ($return['facet_counts']['facet_fields'] as $val) {
                if ($val) {
                    for ($i = 0; $i < count($val);) {
                        $allNum[$val[$i]] = $val[$i + 1];
                        $i += 2;
                    }
                }
            }
        }
        if ($p) {
            var_dump($this->apiUrl['getSearch'] . '?' . $param, $return);
        }
        return $allNum;
    }

    /**
     * 自动补全接口
     * @author 施磊
     */
    public function getSuggest($word) {
        $return = array();
        $post = array('q' => $word, 'wt' => 'json');
        $return = $this->getApiReturnByGet($this->apiUrl['getSuggest'], $post);
        $returnText = array();
        $allSuggestion = array();
        if (isset($return['spellcheck']['suggestions'][1]['suggestion'])) {
            foreach ($return['spellcheck']['suggestions'][1]['suggestion'] as $val) {
                $temp_val = explode('    ', $val);
                $val = count($temp_val) > 1 ? arr::get($temp_val, 1, '') : $val;
                if (!in_array($val, $allSuggestion)) {
                    $returnText[] = array('name' => $val);
                    if (count($returnText) > 15) {
                        break;
                    }
                    $allSuggestion[] = $val;
                }
                continue;
            }
        };
        return $returnText;
    }

    /**
     * 分词接口
     * @author 施磊
     */
    public function getParticiple($word) {
        $return = array();
        $post = array('q' => $word, 'analysis.fieldtype' => 'text_ik', 'wt' => 'json');
        $return = $this->getApiReturnByGet($this->apiUrl['getSearchtParticiple'], $post);
        $returnText = array();
        if (isset($return['analysis']['field_types']['text_ik']['query'][1])) {
            foreach ($return['analysis']['field_types']['text_ik']['query'][1] as $val) {
                $returnText[] = $val['text'];
            }
        };

        $returnText = array_unique($returnText);
        return $returnText;
    }
    /**
     * 快速搜索 按条件
     * @author stone
     */
    public function getQuickSearchByCond($word, $offset = 0, $sort = null, $rows = 30) {
        $return = array();
        $explordArr = array('#');
        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        $post = array('q' => $word, 'wt' => 'json', 'start' => $offset, 'rows' => $rows, 'sort' => $sort, 'hl' => 'true');
        if ($debug)
            var_dump(1, $post);
        $return = $this->getApiReturnByGet($this->apiUrl['getQucikSearchCorrect'], $post);
        $highlighting = arr::get($return, 'highlighting', '');
        $returnId = array();
        $returnText = array();
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[$val['projectid']] = array('id' => $val['projectid'], 'val' => $val);
                if (arr::get($highlighting, $val['projectid'], array())) {
                    foreach (arr::get($highlighting, $val['projectid'], array()) as $keyT => $valT) {
                        $returnId[$val['projectid']]['val'][$keyT] = arr::get($valT, 0, '');
                    }
                }
            }
        };
        #兼容老方法的写法
        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        return $returnText;
    }
    /**
     * 快速搜索接口
     * @author stone shi
     */
    public function getQucikSearch($word, $offset = 0, $sort = null, $rows = 30) {
        $return = array();
        $explordArr = array('#');
        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        $wordTemp = $word;
        foreach ($explordArr as $valEx) {
            $wordTemp = str_replace($valEx, " ", $wordTemp);
        }
        $wordTempArr = explode(' ', $wordTemp);
        $wordExplord = arr::get($wordTempArr, '0', ' ');
        $post = array('q' => $wordExplord, 'wt' => 'json', 'start' => $offset, 'rows' => $rows, 'sort' => $sort, 'hl' => 'true');
        if ($debug)
            var_dump(1, $post);

        $wordTempExArr = array();
        if (count($wordTempArr) > 1) {
            $post['fq'] = arr::get($wordTempArr, '1', ' ');
            foreach ($wordTempArr as $keyWTA => $valWTA) {
                if ($keyWTA > 1) {
                    $wordTempExArr[] = 'fq=' . $valWTA;
                }
            }
        }
        if ($wordTempExArr)
            $post['fq'] = $post['fq'] . '&' . implode('&', $wordTempExArr);
        $return = $this->getApiReturnByGet($this->apiUrl['getQucikSearchCorrect'], $post);
        $highlighting = arr::get($return, 'highlighting', '');
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        if ($debug)
            var_dump(2, $post);
        $returnId = array();
        $returnText = array();
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[$val['projectid']] = array('id' => $val['projectid'], 'val' => $val);
                if (arr::get($highlighting, $val['projectid'], array())) {
                    foreach (arr::get($highlighting, $val['projectid'], array()) as $keyT => $valT) {
                        $returnId[$val['projectid']]['val'][$keyT] = arr::get($valT, 0, '');
                    }
                }
            }
        };
        #兼容老方法的写法
        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        return $returnText;
    }
    /**
     * 生意帮 搜索
     * @author stone shi
     */
    public function getWenSearch($word, $offset = 0, $sort = '', $rows = 10) {
        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        $post = array('q' => $word, 'wt' => 'json', 'start' => $offset, 'rows' => $rows, 'sort' => $sort, 'hl' => 'true');
        if ($debug)
                var_dump(1, $post);
        $return = $this->getApiReturnByGet($this->apiUrl['solrQuestion'], $post);
        $highlighting = arr::get($return, 'highlighting', '');
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        $returnId = array();
        $returnText = array();
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[$val['id']] = array('id' => $val['id'], 'val' => $val);
                if (arr::get($highlighting, $val['id'], array())) {
                    foreach (arr::get($highlighting, $val['id'], array()) as $keyT => $valT) {
                        $returnId[$val['id']]['val'][$keyT] = arr::get($valT, 0, '');
                    }
                }
            }
        };
        #兼容老方法的写法
        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        return $returnText;
    }

    /**
     * 
     */
    public function getWenUserSearch($word, $offset = 0, $sort = '', $rows = 10) {
        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        $post = array('q' => $word, 'wt' => 'json', 'start' => $offset, 'rows' => $rows, 'sort' => $sort, 'hl' => 'true');
        if ($debug)
                var_dump(1, $post);
        $return = $this->getApiReturnByGet($this->apiUrl['solrUser'], $post);
        $highlighting = arr::get($return, 'highlighting', '');
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        $returnId = array();
        $returnText = array();
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[$val['id']] = array('id' => $val['id'], 'val' => $val);
                if (arr::get($highlighting, $val['id'], array())) {
                    foreach (arr::get($highlighting, $val['id'], array()) as $keyT => $valT) {
                        $returnId[$val['id']]['val'][$keyT] = arr::get($valT, 0, '');
                    }
                }
            }
        };
        #兼容老方法的写法
        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        return $returnText;
    }
    /**
     * 直搜接口
     * @author 施磊
     */
    public function getSearch($word, $areaNewArr, $offset = 0, $sort = '', $source = null, $rows = 10) {
        $return = array();
        $explordArr = array('#');
        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;

        $wordTemp = $word;
        foreach ($explordArr as $valEx) {
            $wordTemp = str_replace($valEx, " ", $wordTemp);
        }
        $wordTempArr = explode(' ', $wordTemp);
        $wordExplord = arr::get($wordTempArr, '0', ' ');


        $post = array('q' => $wordExplord, 'wt' => 'json', 'start' => $offset, 'rows' => $rows, 'sort' => $sort, 'hl' => 'true');

        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        if ($debug)
            var_dump(1, $post);

        $wordTempExArr = array();
        if (count($wordTempArr) > 1) {
            $post['fq'] = arr::get($wordTempArr, '1', ' ');
            foreach ($wordTempArr as $keyWTA => $valWTA) {
                if ($keyWTA > 1) {
                    $wordTempExArr[] = 'fq=' . $valWTA;
                }
            }
        }
        if ($wordTempExArr)
            $post['fq'] = $post['fq'] . '&' . implode('&', $wordTempExArr);
        if ($source)
            $post['fq'] = 'projectSource:' . $source;
        /*
          $post = array('w' => $word);
          $returnUrl = $this->getApiReturnByGet($this->apiUrl['getSearchWord'].'='.  str_replace('+', '%20',urlencode($word)), array(), false);
          if($debug) var_dump($this->apiUrl['getSearchWord'].'='. str_replace('+', '%20', urlencode($word)), $word);
          $post = array('q' => $returnUrl, 'wt' => 'json', 'start' => $offset, 'rows' => 10, 'sort' => $sort , 'hl' => 'true');
         * 
         */
        if ($debug)
            var_dump(1, $post);
        $return = $this->getApiReturnByGet($this->apiUrl['getSearch'], $post);
        $highlighting = arr::get($return, 'highlighting', '');
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        if ($debug)
            var_dump(2, $post);
        $returnId = array();
        $returnText = array();
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[$val['projectId']] = array('id' => $val['projectId'], 'val' => $val);
                if (arr::get($highlighting, $val['projectId'], array())) {
                    foreach (arr::get($highlighting, $val['projectId'], array()) as $keyT => $valT) {
                        $returnId[$val['projectId']]['val'][$keyT] = arr::get($valT, 0, '');
                    }
                }
            }
        };
        #兼容老方法的写法
        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        return $returnText;
    }

    /**
     * 直搜接口（搜索纠错）
     * @author 郁政
     */
    public function getCorrectWord($word) {
        $return = array();
        $correctWord = array();
        $post = array('spellcheck.q' => $word, 'wt' => 'json', 'spellcheck' => 'true', 'spellcheck.dictionary' => 'file', 'spellcheck.collate' => 'true', 'spellcheck.maxCollations' => 0);
        $return = $this->getApiReturnByGet($this->apiUrl['getSearchCorrect'], $post);
        $returnText = array();
        if (isset($return['spellcheck']['suggestions'][1]['suggestion'])) {
            $correctWord = $return['spellcheck']['suggestions'][1]['suggestion'];
        }
        return $correctWord;
    }
    /**
     * 资讯搜索
     * @author stone shi
     */
    public function getSearchZixunWen($word, $start = 0, $rows = 10, $sort = '') {
         $debug = isset($_GET['debug']) ? intval($_GET['debug']) : 0;
        $post = array('q' => $word, 'wt' => 'json', 'start' => $start, 'rows' => $rows, 'sort' => $sort, 'hl' => 'true');
        if ($debug)
                var_dump(1, $post);
       $return = $this->getApiReturnByGet($this->apiUrl['getSearchZixun'], $post);
        $highlighting = arr::get($return, 'highlighting', '');
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        $returnId = array();
        $returnText = array();
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[$val['articleId']] = array('id' => $val['articleId'], 'val' => $val);
                if (arr::get($highlighting, $val['articleId'], array())) {
                    foreach (arr::get($highlighting, $val['articleId'], array()) as $keyT => $valT) {
                        $returnId[$val['articleId']]['val'][$keyT] = arr::get($valT, 0, '');
                    }
                }
            }
        };
        #兼容老方法的写法
        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        return $returnText;
    }
    /**
     * 资讯搜索
     * @author 龚湧
     */
    public function getSearchZixun($word, $start = 0, $rows = 10, $sort = '') {
        $return = array();
        $get = array('q' => $word, 'wt' => 'json', 'start' => $start, 'rows' => $rows, 'sort' => $sort);
        $return = $this->getApiReturnByGet($this->apiUrl['getSearchZixun'], $get);
        $returnText = array();
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        $returnId = array();
        $returnText = array();
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[] = $val['articleId'];
            }
        };
        //高亮显示
        $highlighting = array();
        if ($count) {
            $highlighting = Arr::get($return, "highlighting");
        }

        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        $returnText['highlighting'] = $highlighting;
        return $returnText;
    }

    /**
     * 行业新闻选择标签搜索
     * @author 郁政
     */
    public function getHangYeNewsTagList($word, $start = 0, $rows = 10) {
        $return = array();
        $get = array('q' => $word, 'wt' => 'json', 'start' => $start, 'rows' => $rows);
        $return = $this->getApiReturnByGet($this->apiUrl['getHangYeTagList'], $get);
        //echo "<pre>";print_r($return);exit;
        $returnText = array();
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        $returnId = array();
        $returnText = array();
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[] = $val['articleId'];
            }
        };
        //高亮显示
        $highlighting = array();
        if ($count) {
            $highlighting = Arr::get($return, "highlighting");
        }

        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        $returnText['highlighting'] = $highlighting;
        return $returnText;
    }

    /**
     * 资讯分词
     * @param unknown $words
     * @author 龚湧
     */
    public function getSpecialColumnParticiple($word) {
        $explode = array(
            'q' => $word,
            'analysis.fieldtype' => 'text_ik2',
            'wt' => "json"
        );
        $word_explode = $this->getApiReturnByGet($this->apiUrl['getSearchSpecialColumn'], $explode);
        //拆字组查询语句
        $words = isset($word_explode['analysis']['field_types']['text_ik2']['query'][1]) ? $word_explode['analysis']['field_types']['text_ik2']['query'][1] : '';
        //过滤一个字的和拼接搜索字符串
        $keyword = array();
        if ($words) {
            foreach ($words as $wd) {
                if (mb_strlen($wd['text']) > 1) {
                    $keyword[] = $wd['text'];
                }
            }
        }
        return $keyword;
    }

    /**
     * 资讯搜索专栏文章推荐
     *
     * @author 龚湧
     */
    public function getSearchSpecialColumn($tags, $word, $start = 0, $rows = 10, $sort = '') {
        //分词调用
        $keyword = $this->getSpecialColumnParticiple($word);
        //articleTag:"风险投资","投资预算"+or+(articleTag:风险投资,投资预算^0.5+and+如何获得风险投资)
        $array_tag = explode(",", $tags);
        $s1 = '"' . implode('","', $array_tag) . '"';
        $s2 = implode(",", $array_tag);
        $word = 'articleTag:' . $s1 . "+or+(articleTag:{$s2}^0.5+and+{$word}";

        $return = array();
        $get = array('q' => $word, 'wt' => 'json', 'start' => $start, 'rows' => $rows, 'sort' => $sort);
        $return = $this->getApiReturnByGet($this->apiUrl['getSearchZixun'], $get);
        $returnText = array();
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        $returnId = array();
        $returnText = array();
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[] = $val['articleId'];
            }
        };
        //高亮显示
        $highlighting = array();
        if ($count) {
            $highlighting = Arr::get($return, "highlighting");
        }

        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        $returnText['highlighting'] = $highlighting;
        return $returnText;
    }

    /**
     * 分词接口[搜索投资者]
     * @author 钟涛
     */
    public function getParticipleByInvestor($word) {
        $return = array();
        $post = array('q' => $word, 'analysis.fieldtype' => 'text_ik', 'wt' => 'json');
        $return = $this->getApiReturnByGet($this->apiUrl['getSearchInvestorAnalysis'], $post);
        $returnText = array();
        if (isset($return['analysis']['field_types']['text_ik']['query'][1])) {
            foreach ($return['analysis']['field_types']['text_ik']['query'][1] as $val) {
                $returnText[] = $val['text'];
            }
        };
        $returnText = array_unique($returnText);
        return $returnText;
    }

    /**
     * 直搜接口[搜索投资者]
     * @author 钟涛
     */
    public function getSearchByInvestor($word, $areaNewArr, $offset = 0, $sort = '', $type = 1) {
        if (!$word) {
            $word = '*:*';
            $sort = 'lastLogintime desc'; //最新登录时间排序
        }
        $return = array();
        $post = array('q' => $word, 'wt' => 'json', 'start' => $offset, 'rows' => 10, 'sort' => $sort);
        if ($type == 2) {//特殊取20行数据
            $post['rows'] = 20;
        }
        if ($areaNewArr)
            $post['area_pid'] = $areaNewArr;
        $return = $this->getApiReturnByGet($this->apiUrl['getSearchInvestor'], $post);
        $count = isset($return['response']['numFound']) ? $return['response']['numFound'] : 0;
        $returnId = array();
        $returnText = array();
        if (isset($return['response']['docs'])) {
            foreach ($return['response']['docs'] as $val) {
                $returnId[] = $val['perUserId'];
            }
        };
        #兼容老方法的写法
        $returnText['matches'] = $returnId;
        $returnText['total'] = $count;
        return $returnText;
    }
    /**
     * 更新索引
     */
    public function reflashIndex($project_id) {
        $project_id = intval($project_id);
        if(!$project_id) return false;
        $post = array('projectid' => $project_id.'');
        $return = $this->getApiReturn($this->apiUrl['reflashIndex'], $post);
        return $return;
    }
	/**
     * 删除索引
     */
    public function delSearchIndex($project_id) {
        $project_id = intval($project_id);
        if(!$project_id) return false;
        $post = array('id' => $project_id.'');
        $return = $this->getApiReturn($this->apiUrl['delSearchIndex'], $post);
        return $return;
    }
    
    /**
     * 更新生意帮索引
     * @author stone shi
     */
    public function updateWenIndex($id) {
        $id = intval($id);
        if(!$id) return false;
        $post = array('id' => $id.'');
        $return = $this->getApiReturn($this->apiUrl['updateWenIndex'], $post);
        return $return;
    }
    
    /**
     * 删除生意帮索引
     * @author stone shi
     */
    public function delWenIndex($id) {
        $id = intval($id);
        if(!$id) return false;
        $post = array('id' => $id.'');
        $return = $this->getApiReturn($this->apiUrl['delWenIndex'], $post);
        return $return;
    }
}

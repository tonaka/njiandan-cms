<?php defined('SYSPATH') OR die('No direct access allowed.');

class SearchTag {

    public static function all() {
        // 如果搜索总记录小于5000条, 则直接使用like, 大于则直接使用Google搜索
        $db = new Database;
        $intput = new input();
        $keyword = text::strip_html($intput->get('q'));
        $find = array(',', '，', ' ', '+');
        $keywords = str_replace($find, '%', $keyword);
        $keywords = preg_replace('/%+/', '%', $keywords);

        if (empty($keyword)) {
            return array();
        }

        $count = $db->from('googles')->select('count(id) as count')->get()->as_array();
        $count = $count[0]->count;
        if ($count <= 3000) {
            // 先查询Title的
            $title_results = $db->from('googles')->like('title', '%' . $keywords . '%', FALSE)->orderby('date', 'desc')->get()->as_array();
            $id_list = array();
            foreach($title_results as $title) {
                $id_list[] = $title->id;
            }
            // 再查询内容
            $content_results = array();
            if (!empty($id_list)) {
                $content_results = $db->from('googles')->notin('id', $id_list)->like('content', $keyword)->orderby('date', 'desc')->get()->as_array();
            }
            // 合并结果
            $results = array_merge($title_results, $content_results);
            return $results;
        } else {
            // 使用Googleajax搜索
        }
    }

    public static function posts() {
    
    }

    public static function pages() {
    
    }

    
}

<?php 

namespace Home\Model\BU;

use Components\helper\UrlHelper;
use Components\helper\PaginationHelper;

class BUCommon
{
    
    /**
    * 生成分页
    * @param string $baseUrl 基础url
    * @param string $firstUrl 第一页url
    * @param int $total 总记录数
    * @param int $page 当前页码
    * @param int $pagesize 每页显示记录数
    */
    public static function getPages($baseUrl, $firstUrl, $total, $page, $pagesize)
    {
        $config = array(
            'base_url' => $baseUrl,
            'first_url' => $firstUrl,
            'total_rows' => $total,
            'list_rows' => $pagesize,
            'num_links' => 5,
            'cur_page' => $page,
            
            'full_tag_open' => '<div class="pages-block">',
            'full_tag_close' => '</div>',
            'first_tag_open' => '',
            'first_tag_close' => '',
            
            'cur_tag_open' => '<span class="curr">',
            'cur_tag_close' => '</span>',
            
            'num_tag_open' => '',
            'num_tag_close' => '',
            
            'prev_tag_open' => '',
            'prev_tag_close' => '',
            
            'next_tag_open' => '',
            'next_tag_close' => '',
            
            'last_tag_open' => '',
            'last_tag_close' => '',
            
            'first_link' => '<<',
            'last_link' => '>>',
            'prev_link' => '<',
            'next_link' => '>',
        );
        $pagination = new PaginationHelper($config);
    
        return $pagination->createLinks();   
    }    
    
}
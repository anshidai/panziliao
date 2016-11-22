<?php 

namespace Components\helper;


/**
* url处理类
*/
class UrlHelper
{
    /**
    * url规则
    * 格式：控制器名_方法 => 映射url
    * 域名常量使用方法：
        格式: {常量名},  如: {DOMAIN}
    * 动态参数使用方法：
        格式: %s 或 %d, 如: {DOMAIN}/detail-%d.html
    */
    protected static $rules = array(
        'index' => '{DOMAIN}',
        'share_home' => '{DOMAIN}/home-%d.html',
        'share_detail' => '{DOMAIN}/detail/%d.html',
    );
    
    /**
    * 输出url
    * @param string $name url映射键名
    * @param array $arguments 动态参数
    * @param string $domain 域名
    */
    public static function url($name, $arguments = array(), $domain = '')
    {
        $name = strtolower($name);
        if(!isset(self::$rules[$name])) return '';
        
        if(!is_array($arguments)) {
            $arguments = array($arguments);
        }
        $url = self::$rules[$name];
        if(preg_match('/\{(.*)\}/', $url, $match)) {
            $url = str_replace($match[0], '', $url);
            if(defined($match[1])) {
                $url = constant($match[1]).$url;
            }
        }
        if(!empty($arguments)) {
            $url = call_user_func_array('sprintf', array_merge(array($url), $arguments));
        }
        if($domain && strpos($url, 'http://') === false) {
            $domain = strpos($domain, 'http://') === false? 'http://'.$domain: $domain;
            $url = rtrim($domain, '/').'/'.ltrim($url, '/');
        }

        return $url;
    }
    
    
    
}


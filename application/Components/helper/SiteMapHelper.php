<?php 

namespace Components\helper;

/**
* sitemap网站地图操作类

使用：


//生成sitemap索引入口文件
$config = array(
	'xmlDirPath' => 'xxxx/xxx',
	'xmlHeader' => "<?xml version='1.0' encoding='UTF-8'?>\r\n<sitemapindex>\r\n",
	'xmlFooter' => "</sitemapindex>\r\n",
);
$sitemap = new SiteMapHelper($config);

$urls = array(
	'loc' => 'xxx.xml',
	'lastmod' => date('Y-m-d H:i:s'),
);
$sitemap->addUrl($urls);
$sitemap->createSitemapIndexFiles();



//生成单个sitemap 具体数据
$config = array(
	'prefix' => '',
	'xmlDirPath' => 'xxxx/xxx',
	'xmlName' => 'sitemap',
	'maxUrl' => 50000,
	'xmlHeader' => 'xxx',
	'xmlFooter' => 'xxx',
);
$sitemap = new SiteMapHelper($config);

$urls = array(
	'loc' => 'xxx.html',
	'priority' => '0.9',
	'lastmod' => date('Y-m-d H:i:s'),
	'changefreq' => 'Always'
);
$sitemap->addUrl($urls);
$sitemap->createSitemapXmlFiles();



*/
class SiteMapHelper
{	
	//url容器
	private $urls = array();

	private $config = array(
	
		//url前缀
		'prefix' => '',
		
		//xml文件存放目录
		'xmlDirPath' => '',
		
		//xml文件名
		'xmlName' => 'sitemap',

		//xml文件最大url数量
		'maxUrl' => 50000,
		
		//xml头信息
		'xmlHeader' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n",
		
		//xml结尾
		'xmlFooter' => "</urlset>\r\n",
	);
	
	/**
	* 构造函数
	*/
	public function __construct($config = array())
	{
		if(is_array($config) && !empty($config)) {
			$this->config = array_merge($this->config, $config);
		}
		if(empty($this->config['xmlDirPath'])) {
			$this->config['xmlDirPath'] = __DIR__;
		}
		
		if(!file_exists($this->config['xmlDirPath'])) {
			if(!mkdir($this->config['xmlDirPath'], 0775)) {
				die('Do not write XML directory.');
			}
		}
	}
	
	/**
	* 增加url元素
	* @param array|string $url 
	* $url = array(
		'loc' => 'xxx',
		'lastmod' => 'xxx',
		'changefreq' => 'xxx',
		'priority' => 'xxx',
	)
	*/
	public function addUrl($url) 
	{
		if(is_array($url)) {
			$this->urls[] = $url;
		}else {
			if(is_string($url)) {
				$this->urls[] = array('loc'=>$url);
			}else {
				//throw new Exception('addUrl expects an array or string.');
			}
		}
	}
	
	/**
	* 生成sitemap index文件
	*/
	public function createSitemapIndexFiles()
	{
		if($this->urls) {
			$handle = fopen(rtrim($this->config['xmlDirPath'], '/') . '/sitemaps.xml', 'w+');
			fwrite($handle, $this->config['xmlHeader']);
			for($i = 0; $i< count($this->urls); $i++) {
				fwrite($handle, $this->xmlIndexUrl($this->urls[$i]));
			}
			fwrite($handle, $this->config['xmlFooter']);
			fclose($handle);
		}
		
		$this->urls = array(); //清空数据
	}
	
	/**
	* 生成sitemap xml文件
	*/
	public function createSitemapXmlFiles()
	{
		$total = count($this->urls);
		if(!$total) {
			return false;
		}
	
		$fileNumber = 0;
		$maxNumber = ceil($total/$this->config['maxUrl']); //最大文件序号
		while($fileNumber < $maxNumber) {
			$fileNumber++; //默认序号从第一页开始创建
			$start = ($fileNumber - 1) * $this->config['maxUrl']; //开始计数值
			$handle = fopen(rtrim($this->config['xmlDirPath'], '/') . '/' . $this->config['xmlName']. '_' . $fileNumber . '.xml', 'w+');
			fwrite($handle, $this->config['xmlHeader']);
			for($i = $start; $i < $start + $this->config['maxUrl']; $i++) {
				if($i > $total - 1) {
					break;
				}
				fwrite($handle, $this->xmlUrl($this->urls[$i]));
				//echo $i."\n";
			}
			fwrite($handle, $this->config['xmlFooter']);
			fclose($handle);
		}
	
		$this->urls = array(); //清空数据
	}
	
	/**
	* 通过方法设置更改属性值
	*/
	public function setConfig($name, $value)
	{
		if(isset($this->config[$name])) {
			$this->config[$name] = $value;
		}
	}
	
	/**
	* 写入文件
	* @param string $fname 保存的文件名
	* @param array|string $data 保存的数据
	*/
	public static function saveToFile($fname, $data)
	{
		if(empty($data)) {
			return false;
		}
		$handle = fopen($fname, 'w+');
        if($handle === false) {
			return false;
		}
        fwrite($handle, $data);
        fclose($handle);
	}
	
	/**
	* 拼接xml index数据
	* @param array $url url元素
	*/
	protected function xmlIndexUrl($url)
	{
		$xml = '';
		
		if(empty($url)) {
			return $xml;
		}
		$xml .= '<sitemap>'."\r\n";
		
		$keys = array_keys($url);
		for($i=0; $i<count($keys); $i++) {
			if($keys[$i] == 'loc') {
				$xml .= "<{$keys[$i]}>".$this->setUrlPrefix($url[$keys[$i]])."</{$keys[$i]}>\r\n";
			}else {
				$xml .= "<{$keys[$i]}>{$url[$keys[$i]]}</{$keys[$i]}>\r\n";
			}
		}
		$xml .= '</sitemap>'."\r\n";
		
		return $xml;
	}
	
	
	/**
	* 拼接xml数据
	* @param array $url url元素
	*/
	protected function xmlUrl($url)
	{
		$xml = '';
		
		if(empty($url)) {
			return $xml;
		}
		$xml .= '<url>'."\r\n";
		
		$keys = array_keys($url);
		for($i=0; $i<count($keys); $i++) {
			if($keys[$i] == 'loc') {
				$xml .= "<{$keys[$i]}>".$this->setUrlPrefix($url[$keys[$i]])."</{$keys[$i]}>\r\n";
			}else {
				$xml .= "<{$keys[$i]}>{$url[$keys[$i]]}</{$keys[$i]}>\r\n";
			}
		}
		$xml .= '</url>'."\r\n";
		
		return $xml;
	}
	
	/**
	* 设置url前缀或域名
	* @param string $url url
	*/
	protected function setUrlPrefix($url)
	{
		if($this->config['prefix']) {
			$url = $this->config['prefix'] . $url;
		}
		
		return $url;
	}
	
	public function __destruct()
	{
		$this->urls = array(); //清空数据
	}
	
	
	
}

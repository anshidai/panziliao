<?php 

namespace Console\Controller;

use Think\Controller;
use Components\helper\SiteMapHelper;
use Components\helper\UrlHelper;

/**
* 执行脚本 php index.php Console/Sitemap/xxx 
*/
class SitemapController extends Controller
{
    private static $userModel = null;
    private static $detailModel = null;
	
	private $sitemapDir = '/home/wwwroot/panziliao.com/sitemap';
	
	private $pagesize = 1000;
	private $maxUrl = 50000;
	
	protected function _initialize()
    {
        self::$userModel = D('ResUser');
        self::$detailModel = D('ResDetail');
    }
	
	public function run()
	{
		$this->createDetailXml();
		$this->createUserXml();
		$this->createXmlIndex();
	}
	
	/**
	* 创建sitemap index文件
	*/
	public function createXmlIndex()
	{
		$config = array(
			'xmlDirPath' => $this->sitemapDir,
		);
		$sitemap = new SiteMapHelper($config);
		
		//详情index xml
		$total = self::$detailModel->where(array('status'=>2))->count();
		if($total) {
			$pageMax = ceil($total/$this->maxUrl);
			for($page = 1; $page < $pageMax; $page++) {
				$urls = array(
					'loc' => "http://www.panziliao.com/sitemap/detail/detail_{$page}.xml",
					'lastmod' => date('Y-m-d H:i:s'),
				);
				$sitemap->addUrl($urls);
			}
		}
		
		//用户主页index xml
		$total = self::$userModel->where(array('status'=>2))->count();
		if($total) {
			$pageMax = ceil($total/$this->maxUrl);
			for($page = 1; $page < $pageMax; $page++) {
				$urls = array(
					'loc' => "http://www.panziliao.com/sitemap/user/detail_{$page}.xml",
					'lastmod' => date('Y-m-d H:i:s'),
				);
				$sitemap->addUrl($urls);
			}
		}
		$sitemap->createSitemapIndexFiles();
		
		echo "createXmlIndex complete\n";
	}
	
	
	/**
	* 创建详情页xml
	*/
	public function createDetailXml()
	{
		$config = array(
			'xmlDirPath' => $this->sitemapDir.'/detail',
			'xmlName' => 'detail_',
			'maxUrl' => $this->maxUrl,
		);
		$sitemap = new SiteMapHelper($config);
		
		$map = array('status'=>2);
		$total = self::$detailModel->where($map)->count();
		$pageMax = ceil($total/$this->pagesize);
		for($page = 1; $page <= $pageMax; $page++) {
			$list = self::$detailModel->field('id')->where($map)->order('id asc')->limit($this->pagesize)->select();
			if($list) {
				foreach($list as $val) {
					$urls = array(
						'loc' => UrlHelper::url('share_detail', $val['id']),
						'priority' => '0.8',
						'lastmod' => date('Y-m-d H:i:s'),
						'changefreq' => 'daily'
					);
					$sitemap->addUrl($urls);
				}
			}
		}
		$sitemap->createSitemapXmlFiles();
		
		echo "createShareResXml complete\n";
	}
	
	/**
	* 创建用户主页xml
	*/
	public function createUserXml()
	{
		$config = array(
			'xmlDirPath' => $this->sitemapDir.'/user',
			'xmlName' => 'user_',
			'maxUrl' => $this->maxUrl,
		);
		$sitemap = new SiteMapHelper($config);
		
		$map = array('status'=>2);
		$total = self::$userModel->where($map)->count();
		$pageMax = ceil($total/$this->pagesize);
		for($page = 1; $page <= $pageMax; $page++) {
			$list = self::$userModel->field('id')->where($map)->order('id asc')->limit($this->pagesize)->select();
			if($list) {
				foreach($list as $val) {
					$urls = array(
						'loc' => UrlHelper::url('share_home', $val['id']),
						'priority' => '0.8',
						'lastmod' => date('Y-m-d H:i:s'),
						'changefreq' => 'daily'
					);
					$sitemap->addUrl($urls);
				}
			}
		}
		$sitemap->createSitemapXmlFiles();
		
		echo "createUserXml complete\n";
	}
	
	
}
<?php 
use Components\helper\UrlHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$data['detail']['title']}网盘资料分享 - {$Think.SITE_NAME}</title>
	<meta name="keywords" content="{$data['detail']['title']}" />
    <meta name="description" content="{$Think.SITE_NAME}为大家收集提供百度网盘学习资料{$data['detail']['title']},每天更新大量百度网盘的资源分享{$data['detail']['title']}" />
    
    <link rel="stylesheet" href="__CSS__/common.css">
    <link rel="stylesheet" href="__CSS__/style.css">
</head>
<body>
    <include file="./template/default/Common/header.tpl" />
    
    <span class="blank15"></span>
    <div class="main-block">
        <div class="postion-menu">
            <p>当前位置：<a href="{$Think.DOMAIN}">首页</a> &gt; <strong class="postitle">百度网盘资料</strong> &gt; <strong class="postitle">{$data['detail']['title']}</strong></p>
        </div>
        <span class="blank15"></span>
        <div class="share-detail-main">
            <div class="main-left">
                <div class="user-block">
                    <div class="avatar"><img src="{$data['userinfo']['avatar']}" width="80" height="80" alt="头像"></div>
                    <div class="user-info clearfix">
                        <h3 class="user-name"><a href="{:UrlHelper::url('share_home',$data['userinfo']['id'])}">{$data['userinfo']['username']}</a></h3>
                        <a href="{:UrlHelper::url('share_home',$data['userinfo']['id'])}" class="linkhome">进入主页</a>    
                    </div>
                    <div class="clear"></div>
                    <div class="user-params clearfix">
                        <span><b>分享</b><br>{$data['userinfo']['share_count']}</span>
                        <span><b>粉丝</b><br>{$data['userinfo']['fans_count']}</span>
                        <span><b>关注</b><br>{$data['userinfo']['follow_count']}</span>
                        <span class="no-border"><b>浏览</b><br>{$data['userinfo']['hits']}</span>
                    </div>
                </div>
                <!-- user-block end -->
                <span class="blank10"></span>    
                <div class="blue-block clearfix">
                    <div class="htitle"><h3 class="name">{$data['userinfo']['username']}分享资源</h3></div>
                    <div class="blue-list">
                        <ul>
                            <foreach name="data['list']" item="val">
                            <li><a href="{:UrlHelper::url('share_detail',$val['id'])}" target="_blank">{$val['title']}</a></li>
                            </foreach>
                        </ul>
                    </div>
                </div>
                <!-- blue-block end -->
                <span class="blank10"></span>
                <div class="ads-block">
                    <img src="__IMAGE__/ad_01.jpg" alt="">
                </div>
            </div>
            <!-- main-left end -->

            <div class="main-right">
                <div class="htittop">
                    <h1 class="title">{$data['detail']['title']}</h1>
                    <!--<p class="tags">标签：<a href="">互联网产品</a><a href="">产品运营</a></p>-->
                </div>
                <span class="blank10"></span>
                <div class="ads-block">
                    <img src="__IMAGE__/ad_02.jpg" alt="">
                </div>
                <dl class="detail-params">
                    <dd>
                        <span>文件大小：{$data['detail']['filesize']}</span>
                        <span>扩展名：{$data['detail']['filetype']}</span>
                    </dd>
                    <dd>
                        <span>文件类型：文件/文件夹</span>
                    </dd>
                    <dd>
                        <span>下载次数：{$data['detail']['down_num']}</span>
                        <span>浏览次数：{$data['detail']['hits']}</span>
                    </dd>
                    <dd>
                        <span>分享时间：{$data['detail']['sharetime']|date="Y-m-d",###}</span>
                    </dd>    
                    <dt>
                        <a href="{$data['detail']['dynamicurl']}" rel="nofollow" class="down-btn" target="_blank">百度网盘下载</a>
                        <a href="{:UrlHelper::url('share_home',$data['userinfo']['id'])}" class="share-btn">Ta的分享资源</a>
                    </dt>
                </dl>
                <span class="blank5"></span>
                <div class="ads-block">
                    <img src="__IMAGE__/ad_03.jpg" alt="">
                </div>
                <span class="blank5"></span>
                <div class="htitle "><h3 class="name purple-bg">百度网盘推荐达人</h3></div>
                <span class="blank5"></span>
                <div class="daren-block purple-bg clearfix">
                    <ul class="userlist">
                        <li><a href=""><img src="__IMAGE__/u_01.jpg" alt=""></a></li>
                        <li><a href=""><img src="__IMAGE__/u_02.jpg" alt=""></a></li>
                        <li><a href=""><img src="__IMAGE__/u_03.jpg" alt=""></a></li>
                        <li><a href=""><img src="__IMAGE__/u_01.jpg" alt=""></a></li>
                        <li><a href=""><img src="__IMAGE__/u_01.jpg" alt=""></a></li>
                        <li><a href=""><img src="__IMAGE__/u_02.jpg" alt=""></a></li>
                        <li><a href=""><img src="__IMAGE__/u_03.jpg" alt=""></a></li>
                        <li><a href=""><img src="__IMAGE__/u_04.jpg" alt=""></a></li>
                        <li><a href=""><img src="__IMAGE__/u_01.jpg" alt=""></a></li>
                    </ul>
                </div>
                <!-- daren-block end -->

                <span class="blank10"></span>
                <div class="ads-block">
                    <img src="__IMAGE__/ad_04.jpg" alt="">
                </div>

                <span class="blank10"></span>
                <div class="blue-block related-detail clearfix">
                    <div class="htitle"><h3 class="name">{$data['detail']['title']}相关资源</h3></div>
                    <div class="blue-list">
                        <ul>
                            <li><a href="">CF一键领取道具.rar</a></li>
                            <li><a href="">冲上云霄电影版.1.9GB.【搜百度盘】</a></li>
                            <li><a href="">诱惑女孩写真 - 来自天空的女孩（sm）</a></li>
                            <li><a href="">枪林弹雨恶魔辅助.rar</a></li>
                            <li><a href="">酷派8702D刷机</a></li>
                            <li><a href="">打工吧！魔王大人 9.epub</a></li>
                            <li><a href="">我的青春恋爱喜剧果然有问题 06.epub</a></li>
                            <li><a href="">[和ヶ原聡司].打工吧！魔王大</a></li>
                            <li><a href="">[电影天堂www.dy2018.com]战狼HD中英双</a></li>
                            <li><a href="">昆宝出拳.rmvb</a></li>
                            <li><a href="">CF一键领取道具.rar</a></li>
                            <li><a href="">冲上云霄电影版.1.9GB.【搜百度盘】</a></li>
                            <li><a href="">诱惑女孩写真 - 来自天空的女孩（sm）</a></li>
                            <li><a href="">枪林弹雨恶魔辅助.rar</a></li>
                            <li><a href="">酷派8702D刷机</a></li>
                            <li><a href="">打工吧！魔王大人 9.epub</a></li>
                            <li><a href="">我的青春恋爱喜剧果然有问题 06.epub</a></li>
                            <li><a href="">[和ヶ原聡司].打工吧！魔王大</a></li>
                            <li><a href="">[电影天堂www.dy2018.com]战狼HD中英双</a></li>
                            <li><a href="">昆宝出拳.rmvb</a></li>
                        </ul>
                    </div>
                </div>
                <!-- blue-block end -->

            </div>
            <!-- main-right end -->

        </div>
        <!-- share-detail-main end -->

    </div>
    <!-- main-block end -->
    

    <span class="blank20"></span>
    <include file="./template/default/Common/footer.tpl" />

</body>
</html>
<?php 
use Components\helper\UrlHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>最新分享百度云学习资料,百度网盘资源 - {$Think.SITE_NAME}</title>
    <meta name="Keywords" content="最新分享百度云学习资料,百度网盘资源">
    <meta name="description" content="盘资料提供最新分享网盘学习资料,最新各类视频、影视资料、文档资料、图片图集等百度云盘资源分享链接,盘资料打造自己的百度资料分享.">
    <link rel="stylesheet" href="__CSS__/common.css">
    <link rel="stylesheet" href="__CSS__/style.css">
</head>
<body>
    <include file="./template/default/Common/header.tpl" />
    
    <span class="blank15"></span>    
    <div class="main-block">
        <div class="postion-menu">
            <p>当前位置：<a href="{$Think.DOMAIN}">首页</a> &gt; <strong class="postitle">分享资源</strong></p>
        </div>
        <span class="blank10"></span>
        <div class="ads-block"><img src="__IMAGE__/ad_06.jpg" alt=""></div>
        <span class="blank10"></span>
        <div class="share-user-list">
            <div class="htitle"><h3 class="name blue-bg">最新分享资源</h3></div>
            <span class="blank10"></span>    
            <div class="list-block">
                <ul class="ulist latests">
                    <foreach name="data['list']" item="vo">
                    <li>
                        <a href="{:UrlHelper::url('share_detail', $vo['id'])}" target="_blank">{$vo['title']}</a>
                        <span>文件大小：16.5M</span>
                        <span>分享用户：{$vo['username']}</span>
                        <span class="time">[{$vo['sharetime']|date="Y-m-d",###}]</span>
                    </li>
                    </foreach>
                </ul>
            </div>
            <span class="blank15"></span>
        </div>
        <!-- share-user-list end -->
    </div>
    <!-- main-block end -->


    <span class="blank20"></span>
    <include file="./template/default/Common/footer.tpl" />

</body>
</html>
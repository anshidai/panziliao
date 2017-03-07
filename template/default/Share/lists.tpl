<?php 
use Components\helper\UrlHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$data['catname']}资料分享,百度网盘{$data['catname']}资源搜索 <if condition="$data['page'] gt 1">-第{$data['page']}页</if>- {$Think.SITE_NAME}</title>
    <meta name="Keywords" content="{$data['catname']}资料分享,百度网盘{$data['catname']}资源搜索">
    <meta name="description" content="{$Think.SITE_NAME}提供百度网盘各种{$data['catname']}学习资料,每天收集大量百度网盘{$data['catname']}资料,更新各类{$data['catname']}等百度云盘资源分享链接.">
    <link rel="stylesheet" href="__CSS__/common.css">
    <link rel="stylesheet" href="__CSS__/style.css">
</head>
<body>
    <include file="./template/default/Common/header.tpl" />
    
    <span class="blank15"></span>    
    <div class="main-block">
        <div class="postion-menu">
            <p>当前位置：<a href="{$Think.DOMAIN}">首页</a> &gt; <strong class="postitle">{$data['catname']}学习资料</strong></p>
        </div>
        <span class="blank10"></span>
        <div class="ads-block"><img src="__IMAGE__/ad_06.jpg" alt=""></div>
        <span class="blank10"></span>
        <div class="share-user-list">
            <div class="htitle"><h3 class="name blue-bg">{$data['catname']}网盘资料分享</h3></div>
            <span class="blank10"></span>    
            <div class="list-block">
                <ul class="ulist">
                    <foreach name="data['list']" item="vo">
                    <li>
                        <a href="{:UrlHelper::url('share_detail',$vo['id'])}" target="_blank">{$vo['title']}</a>
                        <p>
                            <span>文件大小：16.5M</span>
                            <span>扩展名：{$vo['filetype']}</span>
                            <span>浏览次数：{$vo['hits']}</span>
                            <span>分享时间：{$vo['sharetime']|date="Y-m-d",###}</span>
                            <span>分享用户：{$vo['username']}</span>
                            <br>
                            <span>其他：{$vo['down_num']}次下载/{$vo['save_num']}保存</span>
                        </p>
                    </li>
                    </foreach>
                </ul>
            </div>
            <span class="blank15"></span>
            <notempty name="data['pages']">{$data['pages']}</notempty>

        </div>
        <!-- share-user-list end -->
    </div>
    <!-- main-block end -->


    <span class="blank20"></span>
    <include file="./template/default/Common/footer.tpl" />

</body>
</html>
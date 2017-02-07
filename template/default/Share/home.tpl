<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$data['userinfo']['username']}的百度云盘资源下载和分享 - {$Think.SITE_NAME}</title>
	<meta name="keywords" content="{$data['userinfo']['username']}的百度网盘资源,{$data['userinfo']['username']}的百度云盘资源" />
    <meta name="description" content="{$data['userinfo']['username']}，网盘搜索神器，收录百度云盘资源。每天更新各类视频，种子，小说，壁纸，音乐等优质网盘资源。是您发现和下载好资源的利器。好资源，尽在{$data['userinfo']['username']}。用网盘，是种生活." />
    <link rel="stylesheet" href="__CSS__/common.css">
    <link rel="stylesheet" href="__CSS__/style.css">
</head>
<body>
    <include file="./template/default/Common/header.tpl" />
    
    <span class="blank15"></span>    
    <div class="main-block">
        <div class="postion-menu">
            <p>当前位置：<a href="{$Think.DOMAIN}">首页</a> &gt; <strong class="postitle">百度网盘资料</strong> &gt; <strong class="postitle">{$data['userinfo']['username']}</strong></p>
        </div>
        <span class="blank10"></span>
        <div class="user-head clearfix">
            <div class="uleft-block">
                <div class="ads-block"><img src="__IMAGE__/ad_05.jpg" alt=""></div>
            </div>
            <div class="user-home-block">
                <div class="user-info clearfix">
                    <h3 class="user-name">{$data['userinfo']['username']}分享的百度网盘资源</h3>    
                </div>
                <div class="avatar"><img src="{$data['userinfo']['avatar']}" width="80" height="80" alt="{$data['userinfo']['username']}"></div>
                <div class="wpan-link">
                    <a href="http://pan.baidu.com/share/home?uk={$data['userinfo']['userid']}" target="_blank" rel="nofollow" class="wplink">百度云盘主页</a>
                    <span class="blank15"></span>
                    <span class="share-time">分享时间：{$data['userinfo']['addtime']|date="Y-m-d",###}</span>                    
                </div>
                <div class="clear"></div>
                <div class="user-params clearfix">
                    <span><b>分享</b><br>{$data['userinfo']['share_count']}</span>
                    <span><b>粉丝</b><br>{$data['userinfo']['fans_count']}</span>
                    <span><b>关注</b><br>{$data['userinfo']['follow_count']}</span>
                    <span class="no-border"><b>浏览</b><br>{$data['userinfo']['hits']}</span>
                </div>
            </div>
            <!-- user-home-block end -->
            <div class="uright-block">
                <div class="ads-block"><img src="__IMAGE__/ad_05.jpg" alt=""></div>
            </div>
        </div>
        <!-- user-head end -->
        <span class="blank10"></span>
        <div class="ads-block"><img src="__IMAGE__/ad_06.jpg" alt=""></div>
        <span class="blank10"></span>
        <div class="share-user-list">
            <div class="htitle"><h3 class="name blue-bg">{$data['userinfo']['username']}分享资料</h3></div>
            <span class="blank10"></span>    
            <div class="list-block">
                <ul class="ulist">
                    <foreach name="data['list']" item="val">
                    <li>
                        <a href="{$val['linkurl']}" target="_blank">{$val['title']}</a>
                        <p>
                            <span>文件大小：{$val['filesize']}</span>
                            <span>扩展名：{$val['filetype']}</span>
                            <span>浏览次数：{$val['hits']}</span>
                            <span>分享时间：{$val['sharetime']|date="Y-m-d",###}</span>
                            <span>分类：影视资料</span>
                            <br>
                            <span>其他：{$val['down_num']}次下载/{$val['save_num']}保存</span>
                        </p>
                    </li>
                    </foreach>
                </ul>
            </div>

        </div>
        <!-- share-user-list end -->



    </div>
    <!-- main-block end -->


    <span class="blank20"></span>
    <include file="./template/default/Common/footer.tpl" />

</body>
</html>
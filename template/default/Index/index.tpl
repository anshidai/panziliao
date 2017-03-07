<?php 
use Components\helper\UrlHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>百度云盘学习资料,百度网盘资源分享,百度网盘资料搜索就上{$Think.SITE_NAME}</title>
    <meta name="Keywords" content="百度云盘学习资料,百度网盘资料搜索,百度网盘下载,百度资源分享,百度网盘云盘下载">
    <meta name="description" content="{$Think.SITE_NAME}提供百度网盘各种学习资料,每天收集大量百度网盘资料,更新各类视频、影视资料、文档资料、图片图集等百度云盘资源分享链接,{$Think.SITE_NAME}打造自己的百度资料分享.">
    <link rel="stylesheet" href="__CSS__/common.css">
    <link rel="stylesheet" href="__CSS__/style.css">
</head>
<body>
    
    <include file="./template/default/Common/header.tpl" />
    
    <span class="blank10"></span>
    <div class="main-block">
        <div class="daren-block clearfix">
            <ul class="userlist">
                <foreach name="data['bestUser']" item="val">
                <li><a href="{:UrlHelper::url('share_home', $val['id'])}" target="_blank"><img src="{$val['avatar']}" width="60" height="60" alt="{$val['username']}"></a></li>
                </foreach>
            </ul>
        </div>
        <!-- daren-block end -->
        <span class="blank10"></span>
        
        <div class="ziliao-block">
            <div class="htitle"><h3 class="name">最新百度盘资料</h3></div>
            <span class="blank10"></span>
            <div class="tabs-block">
                <ul class="tabs-li clearfix">
                    <li><a href="javascript:;" class="curr" rel="nofollow">影视资料</a></li>
                    <li><a href="#c2" rel="nofollow">音乐资料</a></li>
                    <li><a href="#c3" rel="nofollow">图片|图集</a></li>
                    <li><a href="#c4" rel="nofollow">专辑</a></li>
                    <li><a href="#c5" rel="nofollow">综合</a></li>
                </ul>
                <span class="blank10"></span>
                <div class="table-block">
                
                    <foreach name="data['bestVideo']" item="val">
                    <div class="topblock">
                        <ul>
                            <foreach name="val" item="item"> 
                            <li><a href="{:UrlHelper::url('share_detail', $val['id'])}" target="_blank">{$item['title']}</a></li>
                            </foreach>
                        </ul>
                    </div>
                    <!-- topblock end -->
                    </foreach>
                
                </div>
                <!-- table-block end -->
            </div>
            <!--  tabs-block end -->
            
            <span class="blank20"></span>
            <div class="htitle" id="c2"><h3 class="name blue-bg">云盘音乐资料</h3></div>
            <span class="blank10"></span>
            <div class="table-block">
                <foreach name="data['bestDocument']" item="val">
                <div class="topblock point">
                    <ul>
                        <foreach name="val" item="item"> 
                        <li><a href="{:UrlHelper::url('share_detail', $val['id'])}" target="_blank">{$item['title']}</a></li>
                        </foreach>
                    </ul>
                </div>
                <!-- topblock end -->
                </foreach>
            </div>
            <!-- table-block end -->
            
            <span class="blank20"></span>
            <div class="htitle" id="c3"><h3 class="name purple-bg">百度盘图片资料</h3></div>
            <span class="blank10"></span>
            <div class="table-block">
                <foreach name="data['bestPicture']" item="val">
                <div class="topblock point">
                    <ul>
                        <foreach name="val" item="item"> 
                        <li><a href="{:UrlHelper::url('share_detail', $val['id'])}" target="_blank">{$item['title']}</a></li>
                        </foreach>
                    </ul>
                </div>
                <!-- topblock end -->
                </foreach>
            </div>
            <!-- table-block end -->
            
            <notempty name="data['bestSpecial']">
            <span class="blank20"></span>
            <div class="htitle" id="c4"><h3 class="name purple-bg">专辑学习资料</h3></div>
            <span class="blank10"></span>
            <div class="table-block">
                <foreach name="data['bestSpecial']" item="val">
                <div class="topblock point">
                    <ul>
                        <foreach name="val" item="item"> 
                        <li><a href="{:UrlHelper::url('share_detail', $val['id'])}" target="_blank">{$item['title']}</a></li>
                        </foreach>
                    </ul>
                </div>
                <!-- topblock end -->
                </foreach>
            </div>
            <!-- table-block end -->
            </notempty>
            
            <span class="blank20"></span>
            <div class="htitle" id="c5"><h3 class="name purple-bg">网盘资料分享</h3></div>
            <span class="blank10"></span>
            <div class="table-block">
                <foreach name="data['bestOther']" item="val">
                <div class="topblock point">
                    <ul>
                        <foreach name="val" item="item"> 
                        <li><a href="{:UrlHelper::url('share_detail', $val['id'])}" target="_blank">{$item['title']}</a></li>
                        </foreach>
                    </ul>
                </div>
                <!-- topblock end -->
                </foreach>
            </div>
            <!-- table-block end -->

        </div>
        <!-- ziliao-block end -->
    </div>
    <span class="blank20"></span>
    <include file="./template/default/Common/footer.tpl" />
</body>
</html>
<div class="main-block clearfix">
    <div class="header-block">
        <div class="logo"><a href=""><img src="__IMG__/logo.png" alt=""></a></div>
        <div class="search-block">
            <div class="search-tips">已收录<span>2018</span>万个资源 | <span>88</span>万位分享达人，今日已更新<span>8632</span>个资源</div>
            <div class="search-form overflow">
                <input type="text" name="k" class="input-keyword" value="">
                <input type="button" class="input-submit" value="搜索">
            </div>
        </div>
    </div>
    <!-- header-block end -->    
</div>

<div class="outer-nav-block bg-red">
    <div class="main-block">
        <ul class="nav">
            <li><a href="{$Think.DOMAIN}" <if condition="CONTROLLER_NAME eq 'Index'">class="curr"</if>>首页</a></li>
            <li><a href="{:UrlHelper::url('category_list',1)}" <if condition="$_GET['cid'] eq 1">class="curr"</if>>影视资料</a></li>
            <li><a href="{:UrlHelper::url('category_list',2)}" <if condition="$_GET['cid'] eq 2">class="curr"</if>>音乐资料</a></li>
            <li><a href="{:UrlHelper::url('category_list',3)}" <if condition="$_GET['cid'] eq 3">class="curr"</if>>图片|图集</a></li>
            <li><a href="{:UrlHelper::url('category_list',4)}" <if condition="$_GET['cid'] eq 4">class="curr"</if>>专辑|软件</a></li>
            <li><a href="{:UrlHelper::url('category_list',5)}" <if condition="$_GET['cid'] eq 5">class="curr"</if>>综合资源</a></li>
            <li><a href="{:UrlHelper::url('latest_detail')}">最新分享</a></li>
        </ul>
    </div>
</div>
<!-- outer-nav-block end -->
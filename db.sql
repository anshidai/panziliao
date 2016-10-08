CREATE TABLE `pzl_counters` (
  `_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '自增的表名',
  `sequence_value` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '自增值',
  PRIMARY KEY (`_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='自增id维护表';

CREATE TABLE `pzl_index_url` (
  `_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '采集url',
  `resid` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '资源id 如：用户id 资源详情id',
  `source` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '采集网站 panduoduo:盘多多',
  `status` tinyint(1) NOT NULL DEFAULT '-1' COMMENT '采集状态 -1:只采集网址 0:采集内容成功 >0采集错误(状态200除外)',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '分类 user:用户 detail:详情',
  `extend` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '扩展字段',
  PRIMARY KEY (`_id`),
  KEY `idx_url` (`url`),
  KEY `idx_status` (`status`),
  KEY `idx_source_type` (`source`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='网址索引表';


CREATE TABLE `pzl_config` (
  `_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '配置名',
  `value` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '配置值',
  `desc` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`_id`),
  KEY `idx_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='配置表';


CREATE TABLE `pzl_category` (
  `_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `catname` varchar(100) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `aliasname` varchar(30) NOT NULL DEFAULT '' COMMENT '别名',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `seotitle` varchar(255) NOT NULL DEFAULT '' COMMENT 'seo标题',
  `keyword` varchar(255) NOT NULL DEFAULT '' COMMENT 'seo关键词',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'seo描述',
  `thumb` varchar(200) DEFAULT NULL COMMENT '栏目图片',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '栏目排序 值越小越靠前',
  `total` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当前栏目文章总数',
  PRIMARY KEY (`_id`),
  KEY `catname` (`catname`),
  KEY `aliasname` (`aliasname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';

CREATE TABLE `pzl_res_detail` (
  `_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '标题',
  `catid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属分类',
  `hits` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `filetype` char(10) NOT NULL DEFAULT '' COMMENT '文件类型 mp3 pdf flv doc等等',
  `filesize` varchar(20) NOT NULL DEFAULT '' COMMENT '文件大小',
  `source` char(10) NOT NULL DEFAULT '' COMMENT '来源 baidu weipan',
  `userid` bigint(13) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `username` bigint(13) unsigned NOT NULL DEFAULT '0' COMMENT '用户名称',
  `shorturl` char(11) NOT NULL DEFAULT '' COMMENT '短hash url标识',
  `source_id` bigint(13) unsigned NOT NULL DEFAULT '0' COMMENT '分享id',
  `fs_id` bigint(13) unsigned NOT NULL DEFAULT '0' COMMENT '分享fid',
  `sharetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分享时间',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `data` text NOT NULL COMMENT '保存数据集合',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '采集url',
  `url_index` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '采集url表_id',
  PRIMARY KEY (`_id`),
  KEY `catid` (`catid`),
  KEY `userid` (`userid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源详情表';

CREATE TABLE `pzl_res_user` (
  `_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `userid`` bigint(13) NOT NULL DEFAULT '0' COMMENT '资源用户id',
  `username` varchar(200) NOT NULL DEFAULT '' COMMENT '用户名称',
  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '用户头像',
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `source` char(10) NOT NULL DEFAULT '' COMMENT '来源 baidu weipan',
  `share_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '资源总数',
  `fans_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `follow_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `hits` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `data` text NOT NULL COMMENT '保存数据集合',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '采集url',
  `url_index` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '采集url表_id',
  PRIMARY KEY (`_id`),
  KEY `addtime` (`addtime`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源用户信息表';
/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : panziliao

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2016-06-14 13:12:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `pre_category`
-- ----------------------------
DROP TABLE IF EXISTS `pre_category`;
CREATE TABLE `pre_category` (
  `catid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `catname` varchar(100) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `aliasname` varchar(30) NOT NULL DEFAULT '' COMMENT '别名',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `seotitle` varchar(255) NOT NULL DEFAULT '' COMMENT 'seo标题',
  `keyword` varchar(255) NOT NULL DEFAULT '' COMMENT 'seo关键词',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'seo描述',
  `thumb` varchar(200) DEFAULT NULL COMMENT '栏目图片',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '栏目排序 值越小越靠前',
  `total` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当前栏目文章总数',
  PRIMARY KEY (`catid`),
  KEY `catname` (`catname`),
  KEY `aliasname` (`aliasname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of pre_category
-- ----------------------------

-- ----------------------------
-- Table structure for `pre_resource`
-- ----------------------------
DROP TABLE IF EXISTS `pre_resource`;
CREATE TABLE `pre_resource` (
  `resid` bigint(13) unsigned NOT NULL AUTO_INCREMENT COMMENT '资源id',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '标题',
  `catid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属分类',
  `hits` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `filetype` char(10) NOT NULL DEFAULT '' COMMENT '文件类型 mp3 pdf flv doc等等',
  `filesize` varchar(20) NOT NULL DEFAULT '' COMMENT '文件大小',
  `source` char(10) NOT NULL DEFAULT '' COMMENT '来源 baidu weipan',
  `uid` bigint(13) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `shorturl` char(11) NOT NULL DEFAULT '' COMMENT '短hash url标识',
  `source_id` bigint(13) unsigned NOT NULL DEFAULT '0' COMMENT '分享id',
  `fs_id` bigint(13) unsigned NOT NULL DEFAULT '0' COMMENT '分享fid',
  `sharetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分享时间',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `data` text NOT NULL COMMENT '保存数据集合',
  PRIMARY KEY (`resid`),
  KEY `catid` (`catid`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源表';

-- ----------------------------
-- Records of pre_resource
-- ----------------------------

-- ----------------------------
-- Table structure for `pre_resource_user`
-- ----------------------------
DROP TABLE IF EXISTS `pre_resource_user`;
CREATE TABLE `pre_resource_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `uid` bigint(13) NOT NULL DEFAULT '0' COMMENT '资源用户id',
  `uname` varchar(200) NOT NULL DEFAULT '' COMMENT '用户名称',
  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '用户头像',
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `source` char(10) NOT NULL DEFAULT '' COMMENT '来源 baidu weipan',
  `share_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '资源总数',
  `fans_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `follow_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '关注数',
  `hits` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`),
  KEY `uid` (`uid`),
  KEY `uname` (`uname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源用户信息表';

-- ----------------------------
-- Records of pre_resource_user
-- ----------------------------

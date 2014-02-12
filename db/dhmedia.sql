-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 02 月 06 日 09:24
-- 服务器版本: 5.0.95
-- PHP 版本: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `dhmedia`
--

-- --------------------------------------------------------

--
-- 表的结构 `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `id` int(11) NOT NULL auto_increment,
  `updatetime` datetime default '0000-00-00 00:00:00',
  `rss` smallint(4) default '1' COMMENT '1:rss >1000:自行脚本爬取',
  `rate` int(11) NOT NULL default '0',
  `name` varchar(20) default NULL,
  `meta` varchar(8) default NULL,
  `url` varchar(128) NOT NULL,
  `rssurl` varchar(128) NOT NULL,
  `failtimes` int(11) NOT NULL default '0',
  `cmovietype` varchar(256) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:电影大片 2:电视剧集',
  `cmoviecountry` varchar(256) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:华语影视 2:欧美大片 3:日韩风格 4:其他国家',
  `clinkquality` varchar(128) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->清晰度：0:'''' 1:TC抢先 2:TC修正 3:DVD清晰 4:BD普清 5:HD高清 6:3D片源',
  `clinktype` varchar(256) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:影视资讯 2:热门评论 3:影视资源 4:购票链接 5:影视资料',
  `clinkway` varchar(128) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:网站 2:预告 3:花絮 4:在线 5:下载 6:综合',
  `clinkonlinetype` varchar(128) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:优酷在线 2:搜狐影视 3:百度影音 4:PPlive 5:风行 6:土豆 7:爱稀奇 8:PPS',
  `clinkdowntype` varchar(128) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:迅雷资源 2:FTP资源 3:BT资源 4:磁力链接 5:电驴资源 6:其他资源',
  `ctitle` varchar(128) default NULL COMMENT '影片名字的提取方法,g是按照模式抽取,f是按照模式剔除',
  `csrclink` varchar(128) default NULL COMMENT '爬取srclink的方法',
  `clinkproperty` varchar(128) default NULL COMMENT '下载链接属性：0:未知资源 1:直接下载 2:跳转下载 3:登陆下载 4:积分下载',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `rssurl` (`rssurl`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- 表的结构 `celebrity`
--

CREATE TABLE IF NOT EXISTS `celebrity` (
  `id` varchar(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `pic` varchar(50) NOT NULL,
  `fail` int(3) NOT NULL default '0',
  `rate` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `pageid` varchar(11) default NULL,
  `author` varchar(20) NOT NULL,
  `title` varchar(128) default NULL,
  `link` varchar(128) NOT NULL default '',
  `srclink` varchar(256) default NULL,
  `cat` varchar(32) default NULL,
  `linkquality` tinyint(1) default '0' COMMENT '清晰度：0:'''' 1:TC抢先 2:TC修正 3:DVD清晰 4:BD普清 5:HD高清 6:3D片源',
  `linkway` tinyint(1) default '0' COMMENT '如果链接是影视资源，子类型：0:'''' 1:网站 2:预告 3:花絮 4:在线 5:下载 6:综合',
  `linktype` tinyint(1) default '0' COMMENT '链接类型：0:'''' 1:影视资讯 2:热门评论 3:影视资源 4:购票链接 5:影视资料',
  `linkonlinetype` tinyint(1) default '0' COMMENT '如果是在线，子类型：0:'''' 1:优酷在线 2:搜狐影视 3:百度影音 4:PPlive 5:风行 6:土豆 7:爱稀奇 8:PPS',
  `linkdowntype` tinyint(1) default '0' COMMENT '如果是下载，子类型：0:'''' 1:迅雷资源 2:FTP资源 3:BT资源 4:磁力链接 5:电驴资源 6:其他资源',
  `linkproperty` tinyint(1) default '0' COMMENT '下载链接属性：0:'''' 1:链接下载 2:种子文件 3:登陆下载 4:积分下载',
  `ctitle` varchar(32) default NULL,
  `moviecountry` tinyint(1) default '0' COMMENT '国家：0:'''' 1:华语影视 2:欧美大片 3:日韩风格 4:其他国家',
  `movieyear` varchar(4) default '',
  `movietype` tinyint(1) default '0' COMMENT '类型：0:'''' 1:电影大片 2:电视剧集',
  `lstatus` tinyint(1) default '0',
  `fail` int(3) NOT NULL default '0',
  `updatetime` datetime default NULL,
  PRIMARY KEY  (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `link2`
--

CREATE TABLE IF NOT EXISTS `link2` (
  `pageid` varchar(11) default NULL,
  `author` varchar(20) NOT NULL,
  `title` varchar(128) default NULL,
  `link` varchar(128) NOT NULL default '',
  `cat` varchar(32) default NULL,
  `linktype` tinyint(1) default '0' COMMENT '链接类型：0:'''' 1:影视资讯 2:热门评论 3:影视资源 4:购票链接 5:影视资料',
  `ctitle` varchar(32) default NULL,
  `moviecountry` tinyint(1) default '0' COMMENT '国家：0:'''' 1:华语影视 2:欧美大片 3:日韩风格 4:其他国家',
  `movieyear` varchar(4) default '',
  `movietype` tinyint(1) default '0' COMMENT '类型：0:'''' 1:电影大片 2:电视剧集',
  `lstatus` tinyint(1) default '0',
  `fail` int(3) NOT NULL default '0',
  `updatetime` datetime default NULL,
  PRIMARY KEY  (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `onlylink`
--

CREATE TABLE IF NOT EXISTS `onlylink` (
  `author` varchar(20) NOT NULL,
  `title` varchar(64) default NULL,
  `link` varchar(128) NOT NULL default '',
  `cat` varchar(32) default NULL,
  `lstatus` tinyint(1) default '0',
  `fail` int(3) NOT NULL default '0',
  `updatetime` datetime default NULL,
  PRIMARY KEY  (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL auto_increment,
  `mediaid` varchar(11) default NULL,
  `ids` varchar(256) default NULL,
  `title` varchar(32) default NULL,
  `aka` varchar(256) default NULL,
  `meta` varchar(512) default NULL,
  `summary` varchar(256) default NULL,
  `imgurl` varchar(16) default NULL,
  `simgurl` varchar(64) default NULL,
  `catcountry` int(1) default '0' COMMENT '国家：0:华语影视 1:欧美大片 2:日韩风格 3:其他国家',
  `cattype` int(1) default '0' COMMENT '类型：0:电影大片 1:电视剧集',
  `mstatus` tinyint(1) NOT NULL default '0' COMMENT '影片的状态：0:无 1：出预告片 2:马上登陆 3:正在上映 4:放映结束 5:出售碟片',
  `quality` tinyint(1) NOT NULL default '0' COMMENT '清晰度：0:'''' 1:未知 2:抢先 3:修正 4:普清 5:高清 6:超清 7:三维',
  `ziyuan` int(3) NOT NULL default '0',
  `yugao` int(3) NOT NULL default '0',
  `yingping` int(3) NOT NULL default '0',
  `zixun` int(3) NOT NULL default '0',
  `pubdate` date default NULL,
  `updatetime` datetime default '0000-00-00 00:00:00',
  `hot` float default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `mediaid` (`mediaid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12563 ;

-- --------------------------------------------------------

--
-- 表的结构 `pagetmp`
--

CREATE TABLE IF NOT EXISTS `pagetmp` (
  `id` int(11) NOT NULL auto_increment,
  `cycle` tinyint(1) NOT NULL default '0' COMMENT '更新周期',
  `quality` tinyint(1) NOT NULL default '0' COMMENT '清晰度：0:'''' 1:未知 2:抢先 3:修正 4:普清 5:高清 6:超清 7:三维',
  `ziyuan` int(3) NOT NULL default '0',
  `yugao` int(3) NOT NULL default '0',
  `yingping` int(3) NOT NULL default '0',
  `zixun` int(3) NOT NULL default '0',
  `updatetime` datetime default '0000-00-00 00:00:00',
  `hot` float default '0',
  PRIMARY KEY  (`id`,`cycle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12563 ;

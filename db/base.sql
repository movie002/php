# Host: 127.0.0.1  (Version: 5.1.50-community-log)
# Date: 2014-04-11 20:37:00
# Generator: MySQL-Front 5.3  (Build 4.13)

/*!40101 SET NAMES utf8 */;

#
# Source for table "author"
#

DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `updatetime` datetime DEFAULT '0000-00-00 00:00:00',
  `rss` smallint(4) DEFAULT '1' COMMENT '0:主动搜索,1:rss,>1000:link爬取,>2000:onlylink爬取',
  `rate` int(11) NOT NULL DEFAULT '0',
  `name` varchar(20) DEFAULT NULL,
  `url` varchar(128) NOT NULL,
  `rssurl` varchar(128) NOT NULL,
  `failtimes` int(11) NOT NULL DEFAULT '0',
  `cmovietype` varchar(256) DEFAULT NULL COMMENT '(c分类t标题l链接)执行->0未知 1电影 2电视剧 3综艺 4动漫',
  `cmoviecountry` varchar(256) DEFAULT NULL COMMENT '0未知 1华语 2欧美 3日韩 4其他',
  `clinkquality` varchar(256) DEFAULT NULL COMMENT '0未知 1未知 2抢先 3修正 4普清 5高清 6超清 7三维',
  `clinktype` varchar(256) DEFAULT NULL COMMENT '0未知类型 1迅雷资源 2FTP资源 3BT 资源 4磁力链接 5电驴资源 6网盘资源 7网页在线 8百度影音 9快播资源',
  `clinkway` varchar(256) DEFAULT NULL COMMENT '0未知类型 1影视资讯 2热门评论 3预告花絮 4相关活动 5购票链接 6下载链接 7在线观看 8影视资料',
  `ctitle` varchar(128) DEFAULT NULL COMMENT '影片名字的提取方法,g是按照模式抽取<c>g%《(.*?)》%0</c>,f是按照模式剔除,x是综合方法<c>x%1%1</c>',
  `clinkdownway` varchar(128) DEFAULT NULL COMMENT '0未知资源 1直接下载 2跳转下载 3登陆下载 4积分下载',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rssurl` (`rssurl`)
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8;

#
# Source for table "celebrity"
#

DROP TABLE IF EXISTS `celebrity`;
CREATE TABLE `celebrity` (
  `id` varchar(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `pic` varchar(50) NOT NULL,
  `fail` int(3) NOT NULL DEFAULT '0',
  `rate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Source for table "link"
#

DROP TABLE IF EXISTS `link`;
CREATE TABLE `link` (
  `pageid` varchar(11) DEFAULT NULL,
  `author` varchar(20) NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `ctitle` varchar(32) DEFAULT NULL,
  `link` varchar(128) NOT NULL DEFAULT '',
  `cat` varchar(32) DEFAULT NULL,
  `linkquality` tinyint(1) DEFAULT '0' COMMENT '清晰度：0:'''' 1:TC抢先 2:TC修正 3:DVD清晰 4:BD普清 5:HD高清 6:3D片源',
  `linkway` tinyint(1) DEFAULT '0' COMMENT '如果链接是影视资源，子类型：0:'''' 1:网站 2:预告 3:花絮 4:在线 5:下载 6:综合',
  `linktype` tinyint(1) DEFAULT '0' COMMENT '链接类型：0:'''' 1:影视资讯 2:热门评论 3:影视资源 4:购票链接 5:影视资料',
  `linkdownway` tinyint(1) DEFAULT '0' COMMENT '如果是下载，子类型：0:'''' 1:迅雷资源 2:FTP资源 3:BT资源 4:磁力链接 5:电驴资源 6:其他资源',
  `moviecountry` tinyint(1) DEFAULT '0' COMMENT '国家：0:'''' 1:华语影视 2:欧美大片 3:日韩风格 4:其他国家',
  `movieyear` varchar(4) DEFAULT '',
  `movietype` tinyint(1) DEFAULT '0' COMMENT '类型：0:'''' 1:电影大片 2:电视剧集',
  `fail` int(2) NOT NULL DEFAULT '0',
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Source for table "link2"
#

DROP TABLE IF EXISTS `link2`;
CREATE TABLE `link2` (
  `pageid` varchar(11) DEFAULT NULL,
  `author` varchar(20) NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `ctitle` varchar(32) DEFAULT NULL,
  `link` varchar(128) NOT NULL DEFAULT '',
  `cat` varchar(32) DEFAULT NULL,
  `linkway` tinyint(1) DEFAULT '0' COMMENT '链接类型：0:',
  `moviecountry` tinyint(1) DEFAULT '0' COMMENT '国家：0:'''' 1:华语影视 2:欧美大片 3:日韩风格 4:其他国家',
  `movieyear` varchar(4) DEFAULT '',
  `movietype` tinyint(1) DEFAULT '0' COMMENT '类型：0:'''' 1:电影大片 2:电视剧集',
  `fail` int(2) NOT NULL DEFAULT '0',
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Source for table "mailsub"
#

DROP TABLE IF EXISTS `mailsub`;
CREATE TABLE `mailsub` (
  `pageid` int(11) NOT NULL DEFAULT '0',
  `email` varchar(64) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`email`,`pageid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Source for table "onlylink"
#

DROP TABLE IF EXISTS `onlylink`;
CREATE TABLE `onlylink` (
  `author` varchar(20) NOT NULL,
  `title` varchar(64) DEFAULT NULL,
  `link` varchar(128) NOT NULL DEFAULT '',
  `cat` varchar(32) DEFAULT NULL,
  `fail` int(3) NOT NULL DEFAULT '0',
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Source for table "page"
#

DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mediaid` varchar(11) DEFAULT NULL,
  `ids` varchar(256) DEFAULT NULL,
  `title` varchar(32) DEFAULT NULL,
  `aka` varchar(256) DEFAULT NULL,
  `meta` varchar(512) DEFAULT NULL,
  `summary` varchar(256) DEFAULT NULL,
  `imgurl` varchar(16) DEFAULT NULL,
  `simgurl` varchar(64) DEFAULT NULL,
  `catcountry` int(1) DEFAULT '0' COMMENT '国家：0:华语影视 1:欧美大片 2:日韩风格 3:其他国家',
  `cattype` int(1) DEFAULT '0' COMMENT '类型：0:电影大片 1:电视剧集',
  `mstatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '影片的状态：0:无 1：出预告片 2:马上登陆 3:正在上映 4:放映结束 5:出售碟片',
  `quality` tinyint(1) NOT NULL DEFAULT '0',
  `ziyuan` int(3) NOT NULL DEFAULT '0',
  `yugao` int(3) NOT NULL DEFAULT '0',
  `yingping` int(3) NOT NULL DEFAULT '0',
  `zixun` int(3) NOT NULL DEFAULT '0',
  `pubdate` date DEFAULT NULL,
  `updatetime` datetime DEFAULT '0000-00-00 00:00:00',
  `hot` float DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mediaid` (`mediaid`)
) ENGINE=MyISAM AUTO_INCREMENT=11639 DEFAULT CHARSET=utf8;

-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 04 月 10 日 20:06
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
  `rss` smallint(4) default '1' COMMENT '0:主动搜索,1:rss,>1000:link爬取,>2000:onlylink爬取',
  `rate` int(11) NOT NULL default '0',
  `name` varchar(20) default NULL,
  `meta` varchar(8) default NULL,
  `url` varchar(128) NOT NULL,
  `rssurl` varchar(128) NOT NULL,
  `failtimes` int(11) NOT NULL default '0',
  `cmovietype` varchar(256) default NULL COMMENT '(c分类t标题l链接)执行->0未知 1电影 2电视剧 3综艺 4动漫',
  `cmoviecountry` varchar(256) default NULL COMMENT '0未知 1华语 2欧美 3日韩 4其他',
  `clinkquality` varchar(128) default NULL COMMENT '0未知 1未知 2抢先 3修正 4普清 5高清 6超清 7三维',
  `clinktype` varchar(256) default NULL COMMENT '0未知 1影视资讯 2热门评论 3影视资源 4购票链接 5影视资料 6相关活动',
  `clinkway` varchar(128) default NULL COMMENT '0未知 1网站 2预告 3花絮 4在线 5下载 6综合',
  `clinkonlinetype` varchar(128) default NULL COMMENT '0未知 1优酷在线 2搜狐影视 3百度影音 4PPlive 5风行 6土豆 7爱稀奇 8PPS',
  `clinkdowntype` varchar(128) default NULL COMMENT '0未知 1迅雷资源 2FTP资源 3BT  资源 4磁力链接 5电驴资源 6网盘资源 7其他资源',
  `ctitle` varchar(128) default NULL COMMENT '影片名字的提取方法,g是按照模式抽取<c>g%《(.*?)》%0</c>,f是按照模式剔除,x是综合方法<c>x%1%1</c>',
  `csrclink` varchar(128) default NULL COMMENT '爬取srclink的方法',
  `clinkproperty` varchar(128) default NULL COMMENT '0未知资源 1直接下载 2跳转下载 3登陆下载 4积分下载',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `rssurl` (`rssurl`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=216 ;

--
-- 导出表中的数据 `author`
--

INSERT INTO `author` (`id`, `updatetime`, `rss`, `rate`, `name`, `meta`, `url`, `rssurl`, `failtimes`, `cmovietype`, `cmoviecountry`, `clinkquality`, `clinktype`, `clinkway`, `clinkonlinetype`, `clinkdowntype`, `ctitle`, `csrclink`, `clinkproperty`) VALUES
(116, '0000-00-00 00:00:00', 0, 0, '预告片世界', NULL, 'http://www.yugaopian.com', 'http://www.yugaopian.com/rss.xml', 76, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%movie%3</c><c>l%.*?%-1</c>', '<c>l%.*?%2</c>', NULL, NULL, '<c>g%(.*?) \\(%1</c><c>g% \\((.*?)\\)%1</c><c>g%(.*?) 预告片%1</c><c>g%(.*?) 先行版%1</c><c>g%(.*?) 剧场版%1</c>', NULL, NULL),
(117, '0000-00-00 00:00:00', 0, 0, '电影FM', NULL, 'http://www.dianying.fm', 'http://www.dianying.fm', 0, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>g%(.*?)\\(%1</c>', NULL, NULL),
(118, '0000-00-00 00:00:00', 0, 0, '豆瓣预告', NULL, 'http://www.douban.com', 'http://www.douban.com', 0, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>g%(.*?)\\(%1</c>', NULL, NULL),
(119, '0000-00-00 00:00:00', 0, 0, '时光网预告', NULL, 'http://www.mtime.com', 'http://www.mtime.com', 0, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>g%(.*?)\\(%1</c>', NULL, NULL),
(120, '2014-04-10 16:30:00', 1, 0, '海阔天空电影', NULL, 'http://www.12u.cn', 'http://www.12u.cn/feed', 115, '<c>l%\\/jddy\\/|\\/vedio\\/|\\/3d\\/%1</c><c>t%电影%1</c><c>l%.*?%2</c>', '<c>l%gangtai%1</c><c>l%meiju%2</c><c>l%riju|hanju%3</c><c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%\\/manhua\\/%-1</c><c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(121, '2014-04-10 00:23:53', 1, 0, '仁心博客', NULL, 'http://www.9sh.net/', 'http://www.9sh.net/rss.xml', 34, '<c>c%电视剧%2</c><c>c%电影|3D高清%1</c><c>l%.*?%-1</c>', '<c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(122, '2014-04-10 16:00:00', 1, 0, '龙部落', NULL, 'http://www.longbuluo.com/', 'http://www.longbuluo.com/feed', 2, '<c>c%电影%1</c><c>c%剧集|电视%2</c><c>c%综艺%3</c><c>c%动画%4</c><c>l%.*?%1</c>', '<c>c%大陆|港台%1</c><c>c%欧美%2</c><c>c%日韩%3</c><c>l%.*?%0</c>', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(123, '0000-00-00 00:00:00', 1, 0, '时光网预告', NULL, 'http://www.mtime.com/trailer/trailer/index.html', 'http://www.mtime.com/trailer/trailer/index.html', 24, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%2</c>', NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(124, '2014-04-10 15:38:23', 1, 0, '新浪电影', NULL, 'http://rss.sina.com.cn/', 'http://rss.sina.com.cn/ent/film/focus7.xml', 46, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(125, '2014-04-10 16:38:54', 1, 0, '新浪电视', NULL, 'http://rss.sina.com.cn/', 'http://rss.sina.com.cn/ent/tv/focus7.xml', 54, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(126, '1999-11-30 00:00:00', 1, 0, '搜狐电影', NULL, 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/dianying.xml', 60, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(127, '1999-11-30 00:00:00', 1, 0, '搜狐电视', NULL, 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/dianshiqianyan.xml', 49, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(128, '1999-11-30 00:00:00', 1, 0, '搜狐评论', NULL, 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/redianpinglun.xml', 46, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%1</c>', NULL, NULL),
(129, '2014-04-10 13:48:14', 1, 0, '影评yp136', NULL, 'http://www.yp136.com/', 'http://www.yp136.com/feed', 75, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(130, '2014-03-30 05:38:39', 1, 0, '影评网', NULL, 'http://yingpingwang.com', 'http://yingpingwang.com/feed', 179, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(131, '0000-00-00 00:00:00', 1, 0, '影评网920TV', NULL, 'http://www.920tv.com/blog/', 'http://www.920tv.com/blog/rss.xml', 777, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(132, '2014-04-10 08:00:59', 1, 0, '时光网电视新闻', NULL, 'http://news.mtime.com', 'http://feed.mtime.com/tvnews.rss', 1, '<c>l%.*?%2</c>', NULL, NULL, '<c>c%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(133, '2014-04-10 09:34:08', 1, 0, '时光网视频新闻', NULL, 'http://news.mtime.com', 'http://feed.mtime.com/videonews.rss', 1, '', NULL, NULL, '<c>c%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(134, '2014-04-09 00:00:00', 1, 0, '贼吧网', NULL, 'http://www.zei8.com/', 'http://www.zei8.com/data/rss/173.xml', 129, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%【(.*?)】%0</c>', NULL, '<c>l%.*?%2</c>'),
(135, '2014-04-10 10:48:33', 1, 0, '时光网电影新闻', NULL, 'http://news.mtime.com', 'http://feed.mtime.com/movienews.rss', 454, '<c>l%.*?%1</c>', NULL, NULL, '<c>c%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(136, '2014-04-06 10:28:56', 1, 0, 'VEVO最新电影(论坛)', NULL, 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=85&auth=0', 61, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(137, '2014-03-22 02:12:22', 1, 0, 'VEVO高清影视(论坛)', NULL, 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=86&auth=0', 47, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(138, '2014-03-23 16:22:03', 1, 0, 'VEVO3D影视(论坛)', NULL, 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=96&auth=0', 45, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(139, '2014-04-10 15:44:26', 1, 0, 'Cinephilia迷影', NULL, 'http://cinephilia.net', 'http://cinephilia.net/feed', 13, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(140, '2014-04-10 15:18:04', 1, 0, '影像日报', NULL, 'http://moviesoon.com/', 'http://moviesoon.com/news/feed/', 10, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(141, '0000-00-00 00:00:00', 1, 0, '电影下载网站(论坛)', NULL, 'http://www.dy558.com/', 'http://www.dy558.com/down/rss.php', 62, '<c>c%电视%2</c><c>c%连续剧%2</c><c>c%综艺%3</c><c>c%.*?%1</c>', '<c>c%国内%1</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%3</c>'),
(142, '0000-00-00 00:00:00', 1, 0, '梦幻天堂(论坛)', NULL, 'http://www.killman.net/', 'http://www.killman.net/rss.php', 169, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(143, '2014-04-10 16:46:12', 1, 0, '飞鸟娱乐(论坛)', NULL, 'http://bbs.hdbird.com/', 'http://bbs.hdbird.com/rss.php', 57, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(144, '2014-04-10 13:23:46', 1, 0, '中国高清(论坛)', NULL, 'http://www.bt49.com/', 'http://www.bt49.com/forum.php?mod=rss', 39, '<c>c%电影|原盘|合集|迅雷|预告|抢先%1</c><c>c%连续剧|电视%2</c><c>c%动漫%4</c><c>c%综艺%3</c><c>c%.*?%-l</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(145, '2014-04-10 10:14:32', 1, 0, '很BT电影联盟', NULL, 'http://henbt.com/', 'http://henbt.com/rss.xml', 9, '<c>c%连续剧%2</c><c>c%综艺%3</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(146, '2013-08-04 22:35:40', 1, 0, '时光荏苒', NULL, 'http://www.sgrr.net', 'http://www.sgrr.net/feed/', 452, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, NULL),
(147, '2014-04-09 17:31:52', 1, 0, '映像讯', NULL, 'http://mkvcn.com/feed', 'http://mkvcn.com/feed', 36, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%1</c><c>g%【(.*?)】%0</c>', NULL, NULL),
(148, '2014-02-12 09:42:56', 1, 0, '影伙虫', NULL, 'http://www.facemov.com/feed', 'http://www.facemov.com/feed', 66, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, NULL),
(149, '2014-04-10 12:03:21', 1, 0, 'VeryCD电影', NULL, 'http://www.verycd.com/base/movie/feed', 'http://www.verycd.com/base/movie/feed', 60, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(150, '2014-04-10 16:26:10', 1, 0, 'VeryCD剧集', NULL, 'http://www.verycd.com/base/tv/feed', 'http://www.verycd.com/base/tv/feed', 46, '<c>l%.*?%2</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(151, '2014-04-10 14:36:04', 1, 0, 'VeryCD动漫', NULL, 'http://www.verycd.com/base/cartoon/feed', 'http://www.verycd.com/base/cartoon/feed', 48, '<c>l%.*?%4</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(152, '2014-04-10 12:11:35', 1, 0, 'VeryCD综艺', NULL, 'http://www.verycd.com/base/zongyi/feed', 'http://www.verycd.com/base/zongyi/feed', 33, '<c>l%.*?%3</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(153, '2014-04-10 13:05:32', 1, 0, '大连生活网', NULL, 'http://www.dlkoo.com/', 'http://www.dlkoo.com/down/Rss.xml', 9, '<c>c%电影%1</c><c>c%影视%1</c><c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动漫%4</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%6</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(154, '2014-04-09 20:38:28', 1, 0, '沒有水的魚', NULL, 'http://www.an80.cc/', 'http://www.an80.cc/feed', 27, NULL, NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(155, '2014-01-06 19:14:45', 1, 0, '云播|电影下载', NULL, 'http://www.hanyutang.com/', 'http://www.hanyutang.com/feed', 0, '<c>c%电视剧%2</c><c>c%电影%1</c><c>l%.*?%0</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(156, '2014-04-08 18:14:37', 1, 0, '第三世界(迅雷)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=16&auth=0', 154, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(157, '2014-04-10 14:49:32', 1, 0, 'xed2k', NULL, 'http://www.xed2k.com/', 'http://www.xed2k.com/forum.php?mod=rss&fid=0&auth=0', 49, '<c>c%电影%1</c><c>l%.*?%-1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%5</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(158, '2014-04-08 23:07:02', 1, 0, '中国高清网', NULL, 'http://www.gaoqing.la', 'http://www.gaoqing.la/feed', 8, '<c>c%series%2</c><c>c%.*?%1</c>', NULL, '<c>c%3d%6</c><c>c%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(159, '2014-03-27 13:15:53', 1, 0, '电影蜜蜂', NULL, 'http://www.dybee.net/', 'http://www.dybee.net/feed', 20, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>g%《(.*?)》%1</c>', NULL, '<c>l%.*?%2</c>'),
(160, '2014-04-08 19:07:23', 1, 0, '我爱下最新(论坛)', NULL, 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=5&auth=0', 1, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(161, '2014-01-23 20:31:59', 1, 0, '我爱下1080P(论坛)', NULL, 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=54&auth=0', 1, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(162, '2014-04-10 05:08:17', 1, 0, '我爱下720P(论坛)', NULL, 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=53&auth=0', 1, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(163, '2014-04-06 12:32:54', 1, 0, '我爱下3D(论坛)', NULL, 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=63&auth=0', 1, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(164, '2014-04-10 00:00:00', 1, 0, '一路电影网', NULL, 'http://www.16movies.cn/', 'http://www.16movies.cn/feed.xml', 77, '<c>c%电影%1</c><c>l%.*?%-1</c>', NULL, '<c>c%1080%6</c><c>c%720%5</c><c>c%3D%7</c><c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(165, '2014-04-09 08:54:17', 1, 0, '网易电影频道', NULL, 'http://ent.163.com/', 'http://ent.163.com/special/00031K7Q/rss_entmovie.xml', 56, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(166, '2014-04-10 15:57:07', 1, 0, '网易电视频道', NULL, 'http://ent.163.com/', 'http://ent.163.com/special/00031K7Q/rss_enttv.xml', 57, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(167, '0000-00-00 00:00:00', 1, 0, '磁力搜索', NULL, 'http://www.cili.so/', 'http://www.cili.so/rss.php', 120, '<c>l%.*?%0</c>', '<c>l%.*?%0</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%4</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(168, '2013-10-20 10:42:58', 1, 0, '第三世界(高清)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=38&auth=0', 162, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(169, '2014-04-04 20:32:41', 1, 0, '第三世界(动漫)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=25&auth=0', 169, '<c>l%.*?%4</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(170, '2014-04-03 11:15:02', 1, 0, '第三世界(综艺)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=18&auth=0', 163, '<c>l%.*?%3</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(171, '0000-00-00 00:00:00', 1, 0, '第三世界(电视剧)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/forum-17-1.html', 218, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(172, '2014-04-05 20:51:06', 1, 0, '大片网', NULL, 'http://www.bigpian.cn/', 'http://www.bigpian.cn/feed', 62, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(173, '2014-04-10 14:41:54', 1, 0, '圣城发布页', NULL, 'http://www.cnscg.org/', 'http://www.cnscg.org/rss.xml', 4, '<c>c%美剧|连续剧%2</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(174, '2014-04-09 06:25:39', 1, 0, '老调网', NULL, 'http://www.bestxl.com/', 'http://www.bestxl.com/rss.php', 3, '<c>c%美剧%2</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(175, '2014-04-09 22:56:49', 1, 0, '影视帝国(影评)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=24&auth=0', 2, '<c>l%.*?%1</c>', '', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%2</c>', NULL, '', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(176, '2014-04-08 02:14:56', 1, 0, '影视帝国32', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=70&auth=0', 2, '<c>l%.*?%2</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(177, '2014-04-09 22:04:16', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=68&auth=0', 1, '<c>l%.*?%2</c>', '<c>l%.*?%1</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(178, '2014-04-09 23:06:58', 1, 0, '影视帝国(评论)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=12&auth=0', 1, '<c>c%.*?%1</c>', '', '', '<c>l%.*?%2</c>', '', NULL, '', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(179, '2014-04-08 00:15:23', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=69&auth=0', 2, '<c>l%.*?%2</c>', '<c>l%.*?%2</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(180, '2014-04-08 18:54:31', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=8&auth=0', 1, '<c>l%.*?%1</c>', '<c>l%.*?%1</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(181, '2014-04-08 19:01:54', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=9&auth=0', 2, '<c>l%.*?%1</c>', '<c>l%.*?%2</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(182, '2014-04-08 01:41:05', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=10&auth=0', 1, '<c>l%.*?%1</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(183, '2014-04-05 11:48:16', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=21&auth=0', 1, '<c>l%.*?%4</c>', '', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(184, '2014-04-09 22:03:57', 1, 0, '影视帝国(1080)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=36&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%6</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(185, '2014-04-10 15:46:55', 1, 0, '影视帝国(3D)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=74&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%7</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(186, '2014-04-10 10:01:26', 1, 0, '影视帝国(720p)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=37&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(187, '2014-04-01 19:51:34', 1, 0, '影视帝国(720p)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=38&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(188, '2014-04-09 23:22:34', 1, 0, '影视帝国(720p)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=63&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(189, '2014-04-09 00:31:37', 1, 0, '影视帝国(720p)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=33&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(190, '2013-09-09 10:45:19', 1, 0, '影视帝国(1024)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=41&auth=0', 2, '<c>l%.*?%1</c>', '', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(191, '2014-04-10 16:25:36', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=180&auth=0', 0, '<c>l%.*?%2</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(192, '2014-04-10 09:29:37', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=231&auth=0', 0, '<c>l%.*?%2</c>', '', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(193, '2014-04-10 06:16:12', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=154&auth=0', 0, '<c>l%.*?%2</c>', '<c>l%.*?%1</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(194, '2014-04-10 15:52:26', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=99&auth=0', 0, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(195, '2014-04-10 09:08:40', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=194&auth=0', 0, '<c>l%.*?%1</c>', '<c>l%.*?%2</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(196, '2014-04-10 00:00:00', 1000, 0, '无忧无虑', NULL, 'http://www.bt5156.com/', 'http://www.bt5156.com/', 51, '<c>l%zongyi%3</c><c>l%dongman%4</c><c>l%tv%2</c><c>l%dy|movie%1</c><c>l%.*?%-1</c>', '<c>l%\\/hytv\\/%1</c><c>l%\\/rihantv\\/%3</c><c>l%\\/oumeitv\\/%2</c><c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%game%-1</c><c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%2</c>', '<c>g%《(.*?)》%1</c><c>g%(.*?)-20%1</c>', NULL, '<c>l%.*?%2</c>'),
(197, '2014-04-09 00:00:00', 1000, 0, '红潮网', NULL, 'http://www.5281520.com/', 'http://www.5281520.com/', 2, '<c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动画片%4</c><c>c%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(198, '0000-00-00 00:00:00', 1000, 0, '高清影视下载网', NULL, 'http://www.gaoqing.tv', 'http://www.gaoqing.tv', 302, '<c>l%movie|m3d|m3d|mp4%1</c><c>l%.*?%2</c>', NULL, '<c>l%mp4%4</c><c>l%m3d%6</c><c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>f%\\(.*?\\)%1</c><c>f%\\[.*?\\]%1</c>', NULL, '<c>l%.*?%2</c>'),
(199, '2014-04-10 16:07:14', 1000, 0, 'ed2000', NULL, 'http://www.ed2000.com/', 'http://www.ed2000.com/', 454, '<c>c%电影%1</c><c>c%剧集%2</c><c>c%动漫%4</c><c>c%综艺%3</c><c>l%.*?%-1</c>', '<c>x%t%0</c>', '<c>x%q%0</c>', '<c>c%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>c%磁力%4</c><c>c%电驴%5</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(200, '2014-04-10 00:00:00', 1000, 0, '飘花资源网', NULL, 'http://www.piaohua.com', 'http://www.piaohua.com', 217, '<c>c%电视剧%2</c><c>c%电影%1</c>', '<c>l%.*?%0</c>', '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%2</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(201, '0000-00-00 00:00:00', 1000, 0, '搜磁力链接', '', 'http://www.somag.net/', 'http://www.somag.net/', 0, '<c>c%电影%1</c><c>c%动漫%4</c><c>c%3D%1</c><c>c%.*?%2</c>', '<c>c%美剧%2</c><c>c%日剧%3</c><c>c%韩剧%3</c><c>c%.*?%0</c>', '<c>t%1080p%6</c><c>t%720p%5</c><c>t%HD%5</c><c>c%3D%7</c><c>c%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%4</c>', '<c>g%《(.*?)》%1</c>', NULL, '<c>l%.*?%2</c>'),
(202, '1999-11-30 00:00:00', 1000, 0, '电影天堂', NULL, 'http://www.dy2018.com/', 'http://www.dy2018.com/', 1748, '<c>c%电影%1</c><c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动漫%4</c><c>l%.*?%0</c>', '<c>c%华语%1</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%2</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(203, '0000-00-00 00:00:00', 1000, 0, '人人影视', NULL, 'http://www.yyets.com', 'http://www.yyets.com', 162, '<c>c%电影%1</c><c>c%剧%2</c><c>l%.*?%0</c>', '<c>c%大%3</c><c>c%美|英%2</c><c>c%日|韩%3</c><c>l%.*?%0</c>', '<c>c%720P%4</c><c>c%1080P%5</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', NULL, '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(204, '2014-04-09 00:00:00', 1000, 0, 'BT天堂', NULL, 'http://www.bttiantang.com/', 'http://www.bttiantang.com/', 0, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%1</c>', NULL, '<c>l%.*?%2</c>'),
(205, '2014-04-10 17:09:15', 2000, 0, '格瓦拉活动', NULL, 'http://www.gewara.com/activity/', 'http://www.gewara.com/activity/', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%6</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(206, '2014-04-08 15:55:00', 2000, 0, '金鹰网', NULL, 'http://www.hunantv.com', 'http://www.hunantv.com', 340, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', NULL, NULL, '<c>c%评论%1</c><c>c%资讯%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(207, '2014-04-10 15:09:00', 2000, 0, '凤凰网娱乐', NULL, 'http://ent.ifeng.com', 'http://ent.ifeng.com', 264, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', '<c>c%华语%1</c><c>c%海外%2</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', NULL, '<c>c%专题%2</c><c>c%评论%2</c><c>c%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(208, '2014-02-19 10:52:42', 2000, 0, '网易娱乐', NULL, 'http://ent.163.com', 'http://ent.163.com', 724, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', '<c>c%华语|港台%1</c><c>c%海外|欧美%2</c><c>c%日韩%3</c>', NULL, '<c>c%专题|评论%2</c><c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(209, '2014-04-10 00:00:00', 2000, 0, '新浪评论', NULL, 'http://ent.sina.com.cn/review/', 'http://ent.sina.com.cn/review/', 75, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, '<c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(210, '2014-04-10 17:08:15', 2000, 0, '搜狐娱乐', NULL, 'http://yule.sohu.com', 'http://yule.sohu.com', 85, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, '<c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(211, '2014-04-10 00:00:00', 2000, 0, '时光网新闻', NULL, 'http://news.mtime.com', 'http://news.mtime.com', 458, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, '<c>c%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(212, '2014-04-09 20:05:00', 2000, 0, '时光网评论', NULL, 'http://www.mtime.com/review', 'http://www.mtime.com/review', 109, '<c>c%.*?%1</c>', NULL, NULL, '<c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(213, '0000-00-00 00:00:00', 2000, 0, '海上电影', NULL, 'http://www.sfs-cn.com/', 'http://www.sfs-cn.com/', 0, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, NULL, NULL, NULL),
(214, '2014-04-10 00:00:00', 2000, 0, '格瓦拉影讯', NULL, 'http://www.gewara.com/news/cinema', 'http://www.gewara.com', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(215, '2014-04-10 17:04:44', 1, 0, '星飞宇部落', NULL, 'http://www.xfyrb.com/', 'http://www.xfyrb.com/feed', 0, '<c>l%movie|yazhuo%1</c><c>l%tv%2</c><c>l%uncategorized%3</c><c>l%.*?%0</c>', '<c>l%cn|gt%1</c><c>l%om%2</c><c>l%yazhuo%3</c><c>l%.*?%0</c>', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>');

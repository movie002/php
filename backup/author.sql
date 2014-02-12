-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 02 月 06 日 09:28
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

--
-- 导出表中的数据 `author`
--

INSERT INTO `author` (`id`, `updatetime`, `rss`, `rate`, `name`, `meta`, `url`, `rssurl`, `failtimes`, `cmovietype`, `cmoviecountry`, `clinkquality`, `clinktype`, `clinkway`, `clinkonlinetype`, `clinkdowntype`, `ctitle`, `csrclink`, `clinkproperty`) VALUES
(1, '2014-02-05 19:44:37', 1, 0, '海阔天空电影', NULL, 'http://www.12u.cn', 'http://www.12u.cn/feed', 109, '<c>l%\\/jddy\\/|\\/vedio\\/|\\/3d\\/%1</c><c>t%电影%1</c><c>l%.*?%2</c>', '<c>l%gangtai%1</c><c>l%meiju%2</c><c>l%riju|hanju%3</c><c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%\\/manhua\\/%-1</c><c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(2, '2013-05-13 18:03:20', 0, 0, '糯米饭', NULL, 'http://nuomifan.net', 'http://nuomifan.net/feed', 49, '<c>c%电影%1</c><c>c%电视剧%2</c>', '<c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>c%情调美图%-1</c><c>c%搞笑漫画%-1</c><c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%1</c>', NULL, NULL),
(3, '2014-02-01 21:23:23', 1, 0, '仁心博客', NULL, 'http://www.9sh.net/', 'http://www.9sh.net/rss.xml', 34, '<c>c%电视剧%2</c><c>c%电影|3D高清%1</c><c>l%.*?%-1</c>', '<c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(4, '2013-12-13 18:22:41', 0, 0, '370电影网', NULL, 'http://www.370kk.com', 'http://www.370kk.com/maps/rss.xml', 37, '<c>l%\\/tiyu\\/|\\/zongyi\\/%3</c><c>l%\\/dongman\\/%4</c><c>l%\\/tvb\\/|\\/oumei\\/|\\/rihan\\/|\\/xiju\\/|ju\\/|\\/jilupian\\/%2</c><c>l%pian\\/%1</c><c>l%.*?%-1</c>', '<c>l%oumei%2</c><c>l%rihan%3</c><c>l%taiwan|guochan|tvb%1</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', NULL, NULL, NULL, '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(5, '0000-00-00 00:00:00', 0, 0, '龙城电影', NULL, 'http://www.0393dy.com', 'http://www.0393dy.com/map/rss.xml', 0, '<c>l%pian\\/%1</c><c>l%\\/dongman\\/%4</c><c>l%\\/tiyu\\/|\\/zongyi\\/%3</c><c>l%ju\\/%2</c><c>l%.*?%-1</c>', '<c>l%\\/gantai|guochan%1</c><c>l%\\/rihan%3</c><c>l%\\/haiwai%2</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先|首发%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%\\/yinle\\/%-1</c><c>l%\\/zuixinzixun\\/%1</c><c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', NULL, '<c>x%1%1</c>', NULL, NULL),
(6, '2013-12-14 05:16:29', 0, 0, '全能影视', NULL, 'http://www.qnvod.com/', 'http://www.qnvod.com/rss.xml', 79, '<c>l%\\/donghua\\/%4</c><c>l%\\/zongyi\\/%3</c><c>l%\\/tvb\\/|\\/oumei\\/|\\/rihan\\/|\\/xiju\\/|ju\\/%2</c><c>l%.*?%1</c>', '<c>l%\\/tvb\\/%1</c><c>l%\\/oumei\\/%2</c><c>l%\\/rihan\\/%3</c><c>l%\\/taiwanju\\/%1</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先|首发%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', NULL, '<c>x%1%1</c>', NULL, NULL),
(7, '2013-12-08 20:08:42', 1, 0, '预告片世界', NULL, 'http://www.yugaopian.com', 'http://www.yugaopian.com/rss.xml', 67, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%movie%3</c><c>l%.*?%-1</c>', '<c>l%.*?%2</c>', NULL, NULL, '<c>g%(.*?) \\(%1</c><c>g% \\((.*?)\\)%1</c><c>g%(.*?) 预告片%1</c><c>g%(.*?) 先行版%1</c><c>g%(.*?) 剧场版%1</c>', NULL, NULL),
(8, '0000-00-00 00:00:00', 0, 0, '时光网预告', NULL, 'http://www.mtime.com/trailer/trailer/index.html', 'http://www.mtime.com/trailer/trailer/index.html', 11, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%2</c>', NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(9, '2014-02-05 15:33:45', 1, 0, '新浪电影', NULL, 'http://rss.sina.com.cn/', 'http://rss.sina.com.cn/ent/film/focus7.xml', 45, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(10, '2014-02-05 15:58:01', 1, 0, '新浪电视', NULL, 'http://rss.sina.com.cn/', 'http://rss.sina.com.cn/ent/tv/focus7.xml', 51, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(11, '2013-11-02 12:12:35', 1, 0, '搜狐电影', NULL, 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/dianying.xml', 59, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(12, '2013-11-02 12:30:59', 1, 0, '搜狐电视', NULL, 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/dianshiqianyan.xml', 48, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(13, '2013-09-01 10:28:22', 1, 0, '搜狐评论', NULL, 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/redianpinglun.xml', 45, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%1</c>', NULL, NULL),
(14, '0000-00-00 00:00:00', 0, 0, '电影FM', NULL, 'http://www.dianying.fm', 'http://www.dianying.fm', 0, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>g%(.*?)\\(%1</c>', NULL, NULL),
(15, '2014-02-04 12:14:44', 1, 0, '影评yp136', NULL, 'http://www.yp136.com/', 'http://www.yp136.com/feed', 56, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(16, '2013-09-09 19:40:49', 1, 0, '天天影评', NULL, 'http://imsou.cn/', 'http://imsou.cn/feed', 374, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(17, '2013-04-23 19:07:15', 1, 0, '720p高清影评', NULL, 'http://www.720yp.com', 'http://www.720yp.com/feed', 291, NULL, NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(18, '2013-12-26 16:46:14', 1, 0, '影评网', NULL, 'http://yingpingwang.com', 'http://yingpingwang.com/feed', 171, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(19, '2013-08-22 15:07:08', 1, 0, '影评网920TV', NULL, 'http://www.920tv.com/blog/', 'http://www.920tv.com/blog/rss.xml', 512, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(20, '2014-02-05 00:00:00', 1001, 0, '无忧无虑', NULL, 'http://www.bt5156.com/', 'http://www.bt5156.com/', 48, '<c>l%zongyi%3</c><c>l%dongman%4</c><c>l%tv%2</c><c>l%dy|movie%1</c><c>l%.*?%-1</c>', '<c>l%\\/hytv\\/%1</c><c>l%\\/rihantv\\/%3</c><c>l%\\/oumeitv\\/%2</c><c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%game%-1</c><c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%2</c>', '<c>g%《(.*?)》%1</c><c>g%(.*?)-20%1</c>', NULL, '<c>l%.*?%2</c>'),
(21, '2013-11-18 00:00:00', 1002, 0, '7369电影下载', NULL, 'http://www.7369dy.cc/', 'http://www.7369dy.cc/', 122, '<c>l%\\/zongyipian\\/%3</c><c>l%dongman%4</c><c>l%dianshiju%2</c><c>l%dianying%1</c><c>l%.*?%-1</c>', '<c>l%oumei%2</c><c>l%hanguo%3</c><c>l%riben%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%1</c>', NULL, '<c>l%.*?%2</c>'),
(22, '2013-12-15 00:00:00', 1003, 0, '高清影视下载网', NULL, 'http://www.gaoqing.tv', 'http://www.gaoqing.tv', 130, '<c>l%movie|m3d|m3d|mp4%1</c><c>l%.*?%2</c>', NULL, '<c>l%mp4%4</c><c>l%m3d%6</c><c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>f%\\(.*?\\)%1</c><c>f%\\[.*?\\]%1</c>', NULL, '<c>l%.*?%2</c>'),
(23, '2014-02-05 19:26:01', 1004, 0, 'ed2000', NULL, 'http://www.ed2000.com/', 'http://www.ed2000.com/', 450, '<c>c%电影%1</c><c>c%剧集%2</c><c>c%动漫%4</c><c>c%综艺%3</c><c>l%.*?%-1</c>', '<c>x%t%0</c>', '<c>x%q%0</c>', '<c>c%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>c%磁力%4</c><c>c%电驴%5</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(24, '2014-02-05 00:00:00', 1005, 0, '飘花资源网', NULL, 'http://www.piaohua.com', 'http://www.piaohua.com', 104, '<c>c%电视剧%2</c><c>c%电影%1</c>', '<c>l%.*?%0</c>', '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%2</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(25, '2013-08-27 16:02:34', 0, 0, '80s手机电影', NULL, 'http://www.y80s.com', 'http://www.y80s.com', 34, '<c>c%电影%1</c><c>c%电视剧%2</c><c>c%动漫%4</c><c>l%.*?%0</c>', '<c>c%国语%1</c><c>c%外语%2</c><c>l%.*?%0</c>', '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%6</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(26, '2014-02-05 19:25:00', 1007, 0, '人人影视', NULL, 'http://www.yyets.com', 'http://www.yyets.com', 147, '<c>c%电影%1</c><c>c%剧%2</c><c>l%.*?%0</c>', '<c>c%大%3</c><c>c%美|英%2</c><c>c%日|韩%3</c><c>l%.*?%0</c>', '<c>c%720P%4</c><c>c%1080P%5</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', NULL, '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(27, '2014-02-04 00:00:00', 1008, 0, 'A67手机电影', NULL, 'http://www.a67.com', 'http://www.a67.com', 111, '<c>c%电影%1</c><c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动漫%4</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>c%预告片%2</c><c>l%.*?%5</c>', NULL, '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(28, '2014-02-05 00:00:00', 1009, 0, '7060手机电影', NULL, 'http://www.7060.com', 'http://www.7060.com', 192, '<c>c%电影%1</c><c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动漫%4</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', NULL, '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(29, '0000-00-00 00:00:00', 1010, 0, '161电影网', NULL, 'http://www.dy161.com/', 'http://www.dy161.com/', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<c>l%.*?%2</c>'),
(30, '2014-02-05 14:40:00', 2001, 0, '金鹰网', NULL, 'http://www.hunantv.com', 'http://www.hunantv.com', 314, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', NULL, NULL, '<c>c%评论%1</c><c>c%资讯%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(31, '2014-02-05 16:16:00', 2002, 0, '凤凰网娱乐', NULL, 'http://ent.ifeng.com', 'http://ent.ifeng.com', 243, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', '<c>c%华语%1</c><c>c%海外%2</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', NULL, '<c>c%专题%2</c><c>c%评论%2</c><c>c%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(32, '1970-01-01 08:00:00', 2003, 0, '网易娱乐', NULL, 'http://ent.163.com', 'http://ent.163.com', 708, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', '<c>c%华语|港台%1</c><c>c%海外|欧美%2</c><c>c%日韩%3</c>', NULL, '<c>c%专题|评论%2</c><c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(34, '2014-02-04 00:00:00', 2004, 0, '新浪娱乐', NULL, 'http://ent.sina.com.cn', 'http://ent.sina.com.cn', 74, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, '<c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(35, '2014-01-16 08:00:00', 2005, 0, '搜狐娱乐', NULL, 'http://yule.sohu.com', 'http://yule.sohu.com', 85, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, '<c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(36, '2014-02-05 17:52:00', 2006, 0, '时光网新闻', NULL, 'http://news.mtime.com', 'http://news.mtime.com', 442, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, '<c>c%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(37, '2014-02-04 16:51:00', 2007, 0, '时光网评论', NULL, 'http://www.mtime.com/review', 'http://www.mtime.com/review', 105, '<c>c%.*?%1</c>', NULL, NULL, '<c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(38, '2013-04-24 20:22:42', 1, 0, '第一资源网', NULL, 'http://www.1dvd.in', 'http://www.1dvd.in/rss.php', 114, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%^(.*?)\\/%0</c>', NULL, NULL),
(39, '2013-12-28 09:00:22', 1, 0, 'VEVO最新电影(论坛)', NULL, 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=85&auth=0', 46, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(40, '2014-02-05 20:00:40', 1, 0, 'VEVO高清影视(论坛)', NULL, 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=86&auth=0', 37, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(41, '2014-02-05 20:00:42', 1, 0, 'VEVO3D影视(论坛)', NULL, 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=96&auth=0', 35, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(42, '2014-02-05 12:18:03', 1, 0, 'Cinephilia迷影', NULL, 'http://cinephilia.net', 'http://cinephilia.net/feed', 8, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(43, '2014-02-05 15:49:07', 1, 0, '影像日报', NULL, 'http://moviesoon.com/', 'http://moviesoon.com/news/feed/', 8, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(44, '2014-02-05 16:17:14', 1, 0, '电影下载网站(论坛)', NULL, 'http://www.dy558.com/', 'http://www.dy558.com/down/rss.php', 51, '<c>c%电视%2</c><c>c%连续剧%2</c><c>c%综艺%3</c><c>c%.*?%1</c>', '<c>c%国内%1</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%3</c>'),
(45, '2014-02-05 19:56:08', 1, 0, '梦幻天堂(论坛)', NULL, 'http://www.killman.net/', 'http://www.killman.net/rss.php', 16, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(46, '2014-02-05 20:00:58', 1, 0, '飞鸟娱乐(论坛)', NULL, 'http://bbs.hdbird.com/', 'http://bbs.hdbird.com/rss.php', 41, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(47, '2014-02-05 20:01:03', 1, 0, '中国高清(论坛)', NULL, 'http://www.bt49.com/', 'http://www.bt49.com/forum.php?mod=rss', 37, '<c>c%电影|原盘|合集|迅雷|预告|抢先%1</c><c>c%连续剧|电视%2</c><c>c%动漫%4</c><c>c%综艺%3</c><c>c%.*?%-l</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(48, '2014-02-05 17:27:00', 1011, 0, '很BT电影联盟', NULL, 'http://henbt.com/', 'http://henbt.com/', 92, '<c>c%连续剧%2</c><c>c%综艺%3</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(49, '0000-00-00 00:00:00', 1, 0, '好电影', NULL, 'http://hao.fm', 'http://hao.fm/rss', 25, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(50, '2013-05-05 20:56:17', 1, 0, '电影1980', NULL, 'http://www.movie1980.com', 'http://www.movie1980.com/feed.asp', 39, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(51, '0000-00-00 00:00:00', 2008, 0, '海上电影', NULL, 'http://www.sfs-cn.com/', 'http://www.sfs-cn.com/', 0, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, NULL, NULL, NULL),
(52, '2013-09-17 16:21:47', -1, 0, '爱看片', NULL, 'http://movie.ip0722.com', 'http://movie.ip0722.com/forum.php?mod=rss&fid=2&auth=0', 1, NULL, NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(53, '2013-10-17 16:23:49', 1, 0, '时光荏苒', NULL, 'http://www.sgrr.net', 'http://www.sgrr.net/feed/', 348, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, NULL),
(54, '2014-02-03 16:32:04', 1, 0, '映像讯', NULL, 'http://mkvcn.com/feed', 'http://mkvcn.com/feed', 35, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%1</c><c>g%【(.*?)】%0</c>', NULL, NULL),
(55, '2014-01-18 21:30:23', 1, 0, '影伙虫', NULL, 'http://www.facemov.com/feed', 'http://www.facemov.com/feed', 49, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, NULL),
(56, '2014-02-05 12:14:13', 1, 0, 'VeryCD电影', NULL, 'http://www.verycd.com/base/movie/feed', 'http://www.verycd.com/base/movie/feed', 55, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(57, '2014-02-05 20:01:26', 1, 0, 'VeryCD剧集', NULL, 'http://www.verycd.com/base/tv/feed', 'http://www.verycd.com/base/tv/feed', 45, '<c>l%.*?%2</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(58, '2014-02-05 20:01:27', 1, 0, 'VeryCD动漫', NULL, 'http://www.verycd.com/base/cartoon/feed', 'http://www.verycd.com/base/cartoon/feed', 45, '<c>l%.*?%4</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(59, '2014-02-04 23:51:54', 1, 0, 'VeryCD综艺', NULL, 'http://www.verycd.com/base/zongyi/feed', 'http://www.verycd.com/base/zongyi/feed', 32, '<c>l%.*?%3</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(60, '2014-02-05 10:39:58', 1, 0, '大连生活网', NULL, 'http://www.dlkoo.com/', 'http://www.dlkoo.com/down/Rss.xml', 6, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%6</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(61, '2014-02-03 19:35:56', 1, 0, '沒有水的魚', NULL, 'http://www.an80.cc/', 'http://www.an80.cc/feed', 22, NULL, NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(62, '2013-11-18 18:29:33', 1, 0, '云点播', NULL, 'http://www.yundianbo.info', 'http://www.yundianbo.info/feed', 6, NULL, NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(63, '2013-11-15 05:34:09', 1, 0, '搜磁力链接', NULL, 'http://www.somag.net/', 'http://www.somag.net/feed/', 172, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%4</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(64, '2014-02-05 20:01:33', 1, 0, 'xed2k', NULL, 'http://www.xed2k.com/', 'http://www.xed2k.com/forum.php?mod=rss&fid=0&auth=0', 38, '<c>c%电影%1</c><c>l%.*?%-1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%5</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>');

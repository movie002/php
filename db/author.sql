-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 03 月 05 日 10:32
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
  `clinkquality` varchar(128) default NULL COMMENT '清晰度：0:'''' 1:未知 2:抢先 3:修正 4:普清 5:高清 6:超清 7:三维',
  `clinktype` varchar(256) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:影视资讯 2:热门评论 3:影视资源 4:购票链接 5:影视资料',
  `clinkway` varchar(128) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:网站 2:预告 3:花絮 4:在线 5:下载 6:综合',
  `clinkonlinetype` varchar(128) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:优酷在线 2:搜狐影视 3:百度影音 4:PPlive 5:风行 6:土豆 7:爱稀奇 8:PPS',
  `clinkdowntype` varchar(128) default NULL COMMENT '判别每个条目如果是(c分类t标题l链接) 执行->0:'''' 1:迅雷资源 2:FTP资源 3:BT资源 4:磁力链接 5:电驴资源 6:其他资源',
  `ctitle` varchar(128) default NULL COMMENT '影片名字的提取方法,g是按照模式抽取,f是按照模式剔除',
  `csrclink` varchar(128) default NULL COMMENT '爬取srclink的方法',
  `clinkproperty` varchar(128) default NULL COMMENT '下载链接属性：0:未知资源 1:直接下载 2:跳转下载 3:登陆下载 4:积分下载',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `rssurl` (`rssurl`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107 ;

--
-- 导出表中的数据 `author`
--

INSERT INTO `author` (`id`, `updatetime`, `rss`, `rate`, `name`, `meta`, `url`, `rssurl`, `failtimes`, `cmovietype`, `cmoviecountry`, `clinkquality`, `clinktype`, `clinkway`, `clinkonlinetype`, `clinkdowntype`, `ctitle`, `csrclink`, `clinkproperty`) VALUES
(1, '2014-03-04 21:46:29', 1, 0, '海阔天空电影', NULL, 'http://www.12u.cn', 'http://www.12u.cn/feed', 113, '<c>l%\\/jddy\\/|\\/vedio\\/|\\/3d\\/%1</c><c>t%电影%1</c><c>l%.*?%2</c>', '<c>l%gangtai%1</c><c>l%meiju%2</c><c>l%riju|hanju%3</c><c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%\\/manhua\\/%-1</c><c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(2, '2013-05-13 18:03:20', 0, 0, '糯米饭', NULL, 'http://nuomifan.net', 'http://nuomifan.net/feed', 49, '<c>c%电影%1</c><c>c%电视剧%2</c>', '<c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>c%情调美图%-1</c><c>c%搞笑漫画%-1</c><c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%1</c>', NULL, NULL),
(3, '2014-03-03 23:40:38', 1, 0, '仁心博客', NULL, 'http://www.9sh.net/', 'http://www.9sh.net/rss.xml', 34, '<c>c%电视剧%2</c><c>c%电影|3D高清%1</c><c>l%.*?%-1</c>', '<c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(4, '2013-12-13 18:22:41', 0, 0, '370电影网', NULL, 'http://www.370kk.com', 'http://www.370kk.com/maps/rss.xml', 37, '<c>l%\\/tiyu\\/|\\/zongyi\\/%3</c><c>l%\\/dongman\\/%4</c><c>l%\\/tvb\\/|\\/oumei\\/|\\/rihan\\/|\\/xiju\\/|ju\\/|\\/jilupian\\/%2</c><c>l%pian\\/%1</c><c>l%.*?%-1</c>', '<c>l%oumei%2</c><c>l%rihan%3</c><c>l%taiwan|guochan|tvb%1</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', NULL, NULL, NULL, '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(5, '0000-00-00 00:00:00', 0, 0, '龙城电影', NULL, 'http://www.0393dy.com', 'http://www.0393dy.com/map/rss.xml', 0, '<c>l%pian\\/%1</c><c>l%\\/dongman\\/%4</c><c>l%\\/tiyu\\/|\\/zongyi\\/%3</c><c>l%ju\\/%2</c><c>l%.*?%-1</c>', '<c>l%\\/gantai|guochan%1</c><c>l%\\/rihan%3</c><c>l%\\/haiwai%2</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先|首发%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%\\/yinle\\/%-1</c><c>l%\\/zuixinzixun\\/%1</c><c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', NULL, '<c>x%1%1</c>', NULL, NULL),
(6, '2013-12-14 05:16:29', 0, 0, '全能影视', NULL, 'http://www.qnvod.com/', 'http://www.qnvod.com/rss.xml', 79, '<c>l%\\/donghua\\/%4</c><c>l%\\/zongyi\\/%3</c><c>l%\\/tvb\\/|\\/oumei\\/|\\/rihan\\/|\\/xiju\\/|ju\\/%2</c><c>l%.*?%1</c>', '<c>l%\\/tvb\\/%1</c><c>l%\\/oumei\\/%2</c><c>l%\\/rihan\\/%3</c><c>l%\\/taiwanju\\/%1</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先|首发%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', NULL, '<c>x%1%1</c>', NULL, NULL),
(7, '2013-12-08 20:08:42', 1, 0, '预告片世界', NULL, 'http://www.yugaopian.com', 'http://www.yugaopian.com/rss.xml', 76, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%movie%3</c><c>l%.*?%-1</c>', '<c>l%.*?%2</c>', NULL, NULL, '<c>g%(.*?) \\(%1</c><c>g% \\((.*?)\\)%1</c><c>g%(.*?) 预告片%1</c><c>g%(.*?) 先行版%1</c><c>g%(.*?) 剧场版%1</c>', NULL, NULL),
(8, '0000-00-00 00:00:00', 0, 0, '时光网预告', NULL, 'http://www.mtime.com/trailer/trailer/index.html', 'http://www.mtime.com/trailer/trailer/index.html', 11, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%2</c>', NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(9, '2014-03-04 23:04:42', 1, 0, '新浪电影', NULL, 'http://rss.sina.com.cn/', 'http://rss.sina.com.cn/ent/film/focus7.xml', 45, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(10, '2014-03-04 23:00:13', 1, 0, '新浪电视', NULL, 'http://rss.sina.com.cn/', 'http://rss.sina.com.cn/ent/tv/focus7.xml', 53, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(11, '2013-11-02 12:12:35', 1, 0, '搜狐电影', NULL, 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/dianying.xml', 59, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(12, '2013-11-02 12:30:59', 1, 0, '搜狐电视', NULL, 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/dianshiqianyan.xml', 48, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(13, '2013-09-01 10:28:22', 1, 0, '搜狐评论', NULL, 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/redianpinglun.xml', 45, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%1</c>', NULL, NULL),
(14, '0000-00-00 00:00:00', 0, 0, '电影FM', NULL, 'http://www.dianying.fm', 'http://www.dianying.fm', 0, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>g%(.*?)\\(%1</c>', NULL, NULL),
(15, '2014-03-03 16:59:56', 1, 0, '影评yp136', NULL, 'http://www.yp136.com/', 'http://www.yp136.com/feed', 74, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(16, '2013-09-09 19:40:49', 1, 0, '天天影评', NULL, 'http://imsou.cn/', 'http://imsou.cn/feed', 487, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(17, '2013-04-23 19:07:15', 1, 0, '720p高清影评', NULL, 'http://www.720yp.com', 'http://www.720yp.com/feed', 308, NULL, NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(18, '2014-02-18 19:21:01', 1, 0, '影评网', NULL, 'http://yingpingwang.com', 'http://yingpingwang.com/feed', 177, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(19, '2013-08-22 15:07:08', 1, 0, '影评网920TV', NULL, 'http://www.920tv.com/blog/', 'http://www.920tv.com/blog/rss.xml', 625, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(20, '2014-03-04 00:00:00', 1001, 0, '无忧无虑', NULL, 'http://www.bt5156.com/', 'http://www.bt5156.com/', 49, '<c>l%zongyi%3</c><c>l%dongman%4</c><c>l%tv%2</c><c>l%dy|movie%1</c><c>l%.*?%-1</c>', '<c>l%\\/hytv\\/%1</c><c>l%\\/rihantv\\/%3</c><c>l%\\/oumeitv\\/%2</c><c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%game%-1</c><c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%2</c>', '<c>g%《(.*?)》%1</c><c>g%(.*?)-20%1</c>', NULL, '<c>l%.*?%2</c>'),
(21, '2013-11-18 00:00:00', 1002, 0, '7369电影下载', NULL, 'http://www.7369dy.cc/', 'http://www.7369dy.cc/', 122, '<c>l%\\/zongyipian\\/%3</c><c>l%dongman%4</c><c>l%dianshiju%2</c><c>l%dianying%1</c><c>l%.*?%-1</c>', '<c>l%oumei%2</c><c>l%hanguo%3</c><c>l%riben%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%1</c>', NULL, '<c>l%.*?%2</c>'),
(22, '2013-12-15 00:00:00', 1003, 0, '高清影视下载网', NULL, 'http://www.gaoqing.tv', 'http://www.gaoqing.tv', 237, '<c>l%movie|m3d|m3d|mp4%1</c><c>l%.*?%2</c>', NULL, '<c>l%mp4%4</c><c>l%m3d%6</c><c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>f%\\(.*?\\)%1</c><c>f%\\[.*?\\]%1</c>', NULL, '<c>l%.*?%2</c>'),
(23, '2014-03-05 04:21:39', 1004, 0, 'ed2000', NULL, 'http://www.ed2000.com/', 'http://www.ed2000.com/', 451, '<c>c%电影%1</c><c>c%剧集%2</c><c>c%动漫%4</c><c>c%综艺%3</c><c>l%.*?%-1</c>', '<c>x%t%0</c>', '<c>x%q%0</c>', '<c>c%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>c%磁力%4</c><c>c%电驴%5</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(24, '2014-03-05 00:00:00', 1005, 0, '飘花资源网', NULL, 'http://www.piaohua.com', 'http://www.piaohua.com', 203, '<c>c%电视剧%2</c><c>c%电影%1</c>', '<c>l%.*?%0</c>', '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%2</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(25, '2013-08-27 16:02:34', 0, 0, '80s手机电影', NULL, 'http://www.y80s.com', 'http://www.y80s.com', 34, '<c>c%电影%1</c><c>c%电视剧%2</c><c>c%动漫%4</c><c>l%.*?%0</c>', '<c>c%国语%1</c><c>c%外语%2</c><c>l%.*?%0</c>', '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%6</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(26, '2014-03-05 02:53:00', 1007, 0, '人人影视', NULL, 'http://www.yyets.com', 'http://www.yyets.com', 162, '<c>c%电影%1</c><c>c%剧%2</c><c>l%.*?%0</c>', '<c>c%大%3</c><c>c%美|英%2</c><c>c%日|韩%3</c><c>l%.*?%0</c>', '<c>c%720P%4</c><c>c%1080P%5</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', NULL, '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(27, '2014-02-04 00:00:00', 1008, 0, 'A67手机电影', NULL, 'http://www.a67.com', 'http://www.a67.com', 111, '<c>c%电影%1</c><c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动漫%4</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>c%预告片%2</c><c>l%.*?%5</c>', NULL, '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(28, '2014-02-05 00:00:00', 1009, 0, '7060手机电影', NULL, 'http://www.7060.com', 'http://www.7060.com', 192, '<c>c%电影%1</c><c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动漫%4</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', NULL, '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(30, '2014-03-04 23:11:00', 2001, 0, '金鹰网', NULL, 'http://www.hunantv.com', 'http://www.hunantv.com', 328, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', NULL, NULL, '<c>c%评论%1</c><c>c%资讯%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(31, '2014-03-04 00:00:00', 2002, 0, '凤凰网娱乐', NULL, 'http://ent.ifeng.com', 'http://ent.ifeng.com', 262, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', '<c>c%华语%1</c><c>c%海外%2</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', NULL, '<c>c%专题%2</c><c>c%评论%2</c><c>c%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(32, '1970-01-01 08:00:00', 2003, 0, '网易娱乐', NULL, 'http://ent.163.com', 'http://ent.163.com', 713, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', '<c>c%华语|港台%1</c><c>c%海外|欧美%2</c><c>c%日韩%3</c>', NULL, '<c>c%专题|评论%2</c><c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(34, '2014-03-04 00:00:00', 2004, 0, '新浪娱乐', NULL, 'http://ent.sina.com.cn', 'http://ent.sina.com.cn', 75, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, '<c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(35, '2014-03-04 09:33:00', 2005, 0, '搜狐娱乐', NULL, 'http://yule.sohu.com', 'http://yule.sohu.com', 85, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, '<c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(36, '2014-03-04 22:40:00', 2006, 0, '时光网新闻', NULL, 'http://news.mtime.com', 'http://news.mtime.com', 453, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, '<c>c%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(37, '2014-03-04 16:21:00', 2007, 0, '时光网评论', NULL, 'http://www.mtime.com/review', 'http://www.mtime.com/review', 109, '<c>c%.*?%1</c>', NULL, NULL, '<c>c%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(38, '2013-04-24 20:22:42', 1, 0, '第一资源网', NULL, 'http://www.1dvd.in', 'http://www.1dvd.in/rss.php', 227, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%^(.*?)\\/%0</c>', NULL, NULL),
(39, '2014-02-25 23:00:39', 1, 0, 'VEVO最新电影(论坛)', NULL, 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=85&auth=0', 49, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(40, '2014-03-03 17:01:22', 1, 0, 'VEVO高清影视(论坛)', NULL, 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=86&auth=0', 39, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(41, '2014-03-01 05:00:33', 1, 0, 'VEVO3D影视(论坛)', NULL, 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=96&auth=0', 37, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(42, '2014-03-04 14:45:06', 1, 0, 'Cinephilia迷影', NULL, 'http://cinephilia.net', 'http://cinephilia.net/feed', 9, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(43, '2014-03-04 15:34:33', 1, 0, '影像日报', NULL, 'http://moviesoon.com/', 'http://moviesoon.com/news/feed/', 9, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(44, '2014-03-04 19:52:18', 1, 0, '电影下载网站(论坛)', NULL, 'http://www.dy558.com/', 'http://www.dy558.com/down/rss.php', 60, '<c>c%电视%2</c><c>c%连续剧%2</c><c>c%综艺%3</c><c>c%.*?%1</c>', '<c>c%国内%1</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%3</c>'),
(45, '2014-03-05 04:56:36', 1, 0, '梦幻天堂(论坛)', NULL, 'http://www.killman.net/', 'http://www.killman.net/rss.php', 16, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(46, '2014-03-05 05:01:48', 1, 0, '飞鸟娱乐(论坛)', NULL, 'http://bbs.hdbird.com/', 'http://bbs.hdbird.com/rss.php', 45, NULL, NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(47, '2014-03-04 23:00:12', 1, 0, '中国高清(论坛)', NULL, 'http://www.bt49.com/', 'http://www.bt49.com/forum.php?mod=rss', 38, '<c>c%电影|原盘|合集|迅雷|预告|抢先%1</c><c>c%连续剧|电视%2</c><c>c%动漫%4</c><c>c%综艺%3</c><c>c%.*?%-l</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(48, '2014-03-04 23:47:00', 1011, 0, '很BT电影联盟', NULL, 'http://henbt.com/', 'http://henbt.com/', 92, '<c>c%连续剧%2</c><c>c%综艺%3</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(49, '0000-00-00 00:00:00', 1, 0, '好电影', NULL, 'http://hao.fm', 'http://hao.fm/rss', 51, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(50, '2013-05-05 20:56:17', 1, 0, '电影1980', NULL, 'http://www.movie1980.com', 'http://www.movie1980.com/feed.asp', 152, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(51, '0000-00-00 00:00:00', 2008, 0, '海上电影', NULL, 'http://www.sfs-cn.com/', 'http://www.sfs-cn.com/', 0, NULL, NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, NULL, NULL, NULL),
(52, '2013-09-17 16:21:47', -1, 0, '爱看片', NULL, 'http://movie.ip0722.com', 'http://movie.ip0722.com/forum.php?mod=rss&fid=2&auth=0', 1, NULL, NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(53, '2013-10-17 16:23:49', 1, 0, '时光荏苒', NULL, 'http://www.sgrr.net', 'http://www.sgrr.net/feed/', 441, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, NULL),
(54, '2014-03-04 13:33:38', 1, 0, '映像讯', NULL, 'http://mkvcn.com/feed', 'http://mkvcn.com/feed', 36, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%1</c><c>g%【(.*?)】%0</c>', NULL, NULL),
(55, '2014-02-12 21:01:42', 1, 0, '影伙虫', NULL, 'http://www.facemov.com/feed', 'http://www.facemov.com/feed', 65, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, NULL),
(56, '2014-03-04 17:00:18', 1, 0, 'VeryCD电影', NULL, 'http://www.verycd.com/base/movie/feed', 'http://www.verycd.com/base/movie/feed', 58, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(57, '2014-03-04 23:00:52', 1, 0, 'VeryCD剧集', NULL, 'http://www.verycd.com/base/tv/feed', 'http://www.verycd.com/base/tv/feed', 45, '<c>l%.*?%2</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(58, '2014-03-04 23:01:55', 1, 0, 'VeryCD动漫', NULL, 'http://www.verycd.com/base/cartoon/feed', 'http://www.verycd.com/base/cartoon/feed', 46, '<c>l%.*?%4</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(59, '2014-03-04 23:01:51', 1, 0, 'VeryCD综艺', NULL, 'http://www.verycd.com/base/zongyi/feed', 'http://www.verycd.com/base/zongyi/feed', 32, '<c>l%.*?%3</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', NULL, NULL, '<c>x%1%1</c>', NULL, NULL),
(60, '2014-03-05 00:32:55', 1, 0, '大连生活网', NULL, 'http://www.dlkoo.com/', 'http://www.dlkoo.com/down/Rss.xml', 8, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%6</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(61, '2014-03-02 21:08:23', 1, 0, '沒有水的魚', NULL, 'http://www.an80.cc/', 'http://www.an80.cc/feed', 25, NULL, NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(62, '2013-11-18 18:29:33', 1, 0, '云点播', NULL, 'http://www.yundianbo.info', 'http://www.yundianbo.info/feed', 8, NULL, NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(76, '2014-03-02 23:06:12', 1, 0, '第三世界(迅雷)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=16&auth=0', 23, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(64, '2014-03-04 23:02:03', 1, 0, 'xed2k', NULL, 'http://www.xed2k.com/', 'http://www.xed2k.com/forum.php?mod=rss&fid=0&auth=0', 49, '<c>c%电影%1</c><c>l%.*?%-1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%5</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(65, '2014-03-04 16:06:31', 1, 0, '中国高清网', NULL, 'http://www.gaoqing.la', 'http://www.gaoqing.la/feed', 1, '<c>c%series%2</c><c>c%.*?%1</c>', NULL, '<c>c%3d%6</c><c>c%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(66, '2014-03-03 19:06:42', 1, 0, '电影蜜蜂', NULL, 'http://www.dybee.net/', 'http://www.dybee.net/feed', 12, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>g%《(.*?)》%1</c>', NULL, '<c>l%.*?%2</c>'),
(67, '2014-03-04 23:02:13', 1, 0, '我爱下最新(论坛)', NULL, 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=5&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(68, '2014-02-07 20:01:43', 1, 0, '我爱下1080P(论坛)', NULL, 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=54&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(69, '2014-02-07 20:01:43', 1, 0, '我爱下720P(论坛)', NULL, 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=53&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(70, '2014-03-04 05:02:35', 1, 0, '我爱下3D(论坛)', NULL, 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=63&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(71, '2014-03-04 00:00:00', 1, 0, '一路电影网', NULL, 'http://www.16movies.cn/', 'http://www.16movies.cn/feed.xml', 74, '<c>c%电影%1</c><c>l%.*?%-1</c>', NULL, '<c>c%1080%6</c><c>c%720%5</c><c>c%3D%7</c><c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(72, '2014-03-02 09:45:57', 1, 0, '网易电影频道', NULL, 'http://ent.163.com/', 'http://ent.163.com/special/00031K7Q/rss_entmovie.xml', 47, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(73, '2014-03-04 11:07:02', 1, 0, '网易电视频道', NULL, 'http://ent.163.com/', 'http://ent.163.com/special/00031K7Q/rss_enttv.xml', 45, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>g%《(.*?)》%0</c>', NULL, NULL),
(74, '2014-03-05 00:00:00', 1008, 0, '搜磁力链接', '', 'http://www.somag.net/', 'http://www.somag.net/', 0, '<c>c%电影%1</c><c>c%动漫%4</c><c>c%3D%1</c><c>c%.*?%2</c>', '<c>c%美剧%2</c><c>c%日剧%3</c><c>c%韩剧%3</c><c>c%.*?%0</c>', '<c>t%1080p%6</c><c>t%720p%5</c><c>t%HD%5</c><c>c%3D%7</c><c>c%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%4</c>', '<c>g%《(.*?)》%1</c>', NULL, '<c>l%.*?%2</c>'),
(75, '2014-02-27 17:02:12', 1, 0, '磁力搜索', NULL, 'http://www.cili.so/', 'http://www.cili.so/rss.php', 3, '<c>l%.*?%0</c>', '<c>l%.*?%0</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%4</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(77, '2014-02-24 17:01:42', 1, 0, '第三世界(高清)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=38&auth=0', 24, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>g%《(.*?)》%0</c>', NULL, '<c>l%.*?%2</c>'),
(78, '2014-02-24 23:02:29', 1, 0, '第三世界(动漫)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=25&auth=0', 28, '<c>l%.*?%4</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(79, '2014-02-24 23:02:29', 1, 0, '第三世界(综艺)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=18&auth=0', 26, '<c>l%.*?%3</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(80, '0000-00-00 00:00:00', 1, 0, '第三世界(电视剧)', NULL, 'http://www.mythe3.com/', 'http://www.mythe3.com/forum-17-1.html', 79, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(81, '2014-03-05 00:26:41', 1, 0, '大片网', NULL, 'http://www.bigpian.cn/', 'http://www.bigpian.cn/feed', 25, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(82, '2014-03-04 17:03:19', 1, 0, '圣城发布页', NULL, 'http://www.cnscg.org/', 'http://www.cnscg.org/rss.xml', 1, '<c>c%美剧|连续剧%2</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(83, '2014-03-05 05:03:47', 1, 0, '老调网', NULL, 'http://www.bestxl.com/', 'http://www.bestxl.com/rss.php', 0, '<c>c%美剧%2</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(84, '1999-11-30 00:00:00', 1002, 0, '电影天堂', NULL, 'http://www.dy2018.com/', 'http://www.dy2018.com/', 0, '<c>c%电影%1</c><c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动漫%4</c><c>l%.*?%0</c>', '<c>c%华语%1</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%2</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(85, '2014-03-01 23:04:06', 1, 0, '影视帝国(影评)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=24&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%2</c>', NULL, '', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(86, '2014-03-01 16:26:21', 1, 0, '影视帝国32', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=70&auth=0', 1, '<c>l%.*?%2</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(89, '0000-00-00 00:00:00', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=68&auth=0', 1, '<c>l%.*?%2</c>', '<c>l%.*?%1</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(87, '2014-03-01 16:26:23', 1, 0, '影视帝国(评论)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=12&auth=0', 1, '<c>c%.*?%1</c>', '', '', '<c>l%.*?%2</c>', '', NULL, '', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(88, '2014-03-01 16:26:24', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=69&auth=0', 1, '<c>l%.*?%2</c>', '<c>l%.*?%2</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(90, '2014-03-01 16:26:25', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=8&auth=0', 1, '<c>l%.*?%1</c>', '<c>l%.*?%1</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(91, '2014-03-01 16:26:25', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=9&auth=0', 1, '<c>l%.*?%1</c>', '<c>l%.*?%2</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(92, '2014-03-01 16:26:26', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=10&auth=0', 1, '<c>l%.*?%1</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(93, '2014-03-01 16:26:27', 1, 0, '影视帝国', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=21&auth=0', 1, '<c>l%.*?%4</c>', '', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(0, '2014-03-04 23:04:04', 1, 0, '影视帝国(1080)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=36&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%6</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(95, '2014-03-01 16:26:29', 1, 0, '影视帝国(3D)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=74&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%7</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(96, '2014-03-01 16:26:30', 1, 0, '影视帝国(720p)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=37&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(97, '2014-03-01 16:26:31', 1, 0, '影视帝国(720p)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=38&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(98, '2014-03-05 05:03:57', 1, 0, '影视帝国(720p)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=63&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%3</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(99, '2014-03-01 16:26:32', 1, 0, '影视帝国(720p)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=33&auth=0', 1, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(100, '2014-03-01 16:26:34', 1, 0, '影视帝国(1024)', NULL, 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=41&auth=0', 2, '<c>l%.*?%1</c>', '', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(101, '2014-03-04 23:04:10', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=180&auth=0', 0, '<c>l%.*?%2</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(102, '2014-03-03 17:04:04', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=231&auth=0', 0, '<c>l%.*?%2</c>', '', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%3</c>'),
(103, '2014-03-04 23:04:13', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=154&auth=0', 0, '<c>l%.*?%2</c>', '<c>l%.*?%1</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(104, '2014-03-02 23:09:09', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=99&auth=0', 0, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(105, '2014-03-04 17:03:37', 1, 0, '3E帝国', NULL, 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=194&auth=0', 0, '<c>l%.*?%1</c>', '<c>l%.*?%2</c>', '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>x%1%1</c>', NULL, '<c>l%.*?%2</c>'),
(106, '2014-03-03 00:00:00', 1009, 0, 'BT天堂', NULL, 'http://www.bttiantang.com/', 'http://www.bttiantang.com/', 0, '<c>l%.*?%1</c>', '', '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%5</c>', NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%1</c>', NULL, '<c>l%.*?%2</c>');

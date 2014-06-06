-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 06 月 06 日 13:05
-- 服务器版本: 5.1.50
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `movie002`
--

-- --------------------------------------------------------

--
-- 表的结构 `author`
--

CREATE TABLE IF NOT EXISTS `author` (
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
  `email` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rssurl` (`rssurl`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=220 ;

--
-- 转存表中的数据 `author`
--

INSERT INTO `author` (`rss`, `rate`, `name`, `url`, `rssurl`, `failtimes`, `cmovietype`, `cmoviecountry`, `clinkquality`, `clinktype`, `clinkway`, `ctitle`, `clinkdownway`, `email`) VALUES
(0, 0, '预告片世界', 'http://www.yugaopian.com', 'http://www.yugaopian.com/rss.xml', 0, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%.*?%7</c>', '<c>l%.*?%3</c>', '<c>g%(.*?) \\(%1</c><c>g% \\((.*?)\\)%1</c><c>g%(.*?) 预告片%1</c><c>g%(.*?) 先行版%1</c><c>g%(.*?) 剧场版%1</c>', NULL, NULL),
(0, 0, '电影FM', 'http://www.dianying.fm', 'http://www.dianying.fm', 0, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%.*?%4</c>', '<c>l%.*?%6</c>', '<c>g%(.*?)\\(%1</c>', '<c>l%.*?%2</c>', NULL),
(0, 0, '豆瓣预告', 'http://www.douban.com', 'http://www.douban.com', 0, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%.*?%7</c>', '<c>l%.*?%3</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(0, 0, '时光网预告', 'http://www.mtime.com', 'http://www.mtime.com', 0, '<c>l%.*?%0</c>', NULL, NULL, '<c>l%.*?%7</c>', '<c>l%.*?%3</c>', '<c>g%(.*?)\\(%1</c>', NULL, NULL),
(1, 0, '海阔天空电影', 'http://www.12u.cn', 'http://www.12u.cn/feed', 0, '<c>l%ju\\/|gangtai\\/|tv\\/%2</c><c>l%zongyi%3</c><c>l%.*?%1</c>', '<c>l%gangtai%1</c><c>l%meiju%2</c><c>l%riju|hanju%3</c><c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '仁心博客', 'http://www.9sh.net/', 'http://www.9sh.net/rss.xml', 0, '<c>c%电视剧%2</c><c>c%电影|3D高清%1</c><c>l%.*?%-1</c>', '<c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '龙部落', 'http://www.longbuluo.com/', 'http://www.longbuluo.com/feed', 0, '<c>c%电影%1</c><c>c%剧集|电视%2</c><c>c%综艺%3</c><c>c%动画%4</c><c>l%.*?%1</c>', '<c>c%大陆|港台%1</c><c>c%欧美%2</c><c>c%日韩%3</c><c>l%.*?%0</c>', '<c>l%.*?%5</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '时光网预告', 'http://www.mtime.com/trailer/trailer/index.html', 'http://www.mtime.com/trailer/trailer/index.html', 0, NULL, NULL, NULL, '<c>l%.*?%7</c>', '<c>l%.*?%3</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '新浪电影', 'http://rss.sina.com.cn/', 'http://rss.sina.com.cn/ent/film/focus7.xml', 0, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '新浪电视', 'http://rss.sina.com.cn/', 'http://rss.sina.com.cn/ent/tv/focus7.xml', 0, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '搜狐电影', 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/dianying.xml', 0, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '搜狐电视', 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/dianshiqianyan.xml', 0, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '搜狐评论', 'http://rss.yule.sohu.com/rss.shtml', 'http://rss.yule.sohu.com/rss/redianpinglun.xml', 0, NULL, NULL, NULL, NULL, '<c>l%.*?%2</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '影评yp136', 'http://www.yp136.com/', 'http://www.yp136.com/feed', 0, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>l%.*?%2</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '影评网', 'http://yingpingwang.com', 'http://yingpingwang.com/feed', 0, NULL, NULL, NULL, NULL, '<c>l%.*?%2</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '影评网920TV', 'http://www.920tv.com/blog/', 'http://www.920tv.com/blog/rss.xml', 1, NULL, NULL, NULL, NULL, '<c>l%.*?%2</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '时光网电视新闻', 'http://news.mtime.com', 'http://feed.mtime.com/tvnews.rss', 0, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%1</c><c>g%"(.*?)"%0</c>', NULL, NULL),
(1, 0, '时光网视频新闻', 'http://news.mtime.com', 'http://feed.mtime.com/videonews.rss', 0, NULL, NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%1</c><c>g%"(.*?)"%0</c>', NULL, NULL),
(1, 0, '贼吧网', 'http://www.zei8.com/', 'http://www.zei8.com/data/rss/173.xml', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>g%【(.*?)】%0</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '时光网电影新闻', 'http://news.mtime.com', 'http://feed.mtime.com/movienews.rss', 0, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, 'VEVO最新电影(论坛)', 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=85&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, 'VEVO高清影视(论坛)', 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=86&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, 'VEVO3D影视(论坛)', 'http://www.qianxun.me', 'http://www.qianxun.me/rss.php?fid=96&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, 'Cinephilia迷影', 'http://cinephilia.net', 'http://cinephilia.net/feed', 0, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>c%资讯%1</c><c>c%.*?%2</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '影像日报', 'http://moviesoon.com/', 'http://moviesoon.com/news/feed/', 1, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '电影下载网站(论坛)', 'http://www.dy558.com/', 'http://www.dy558.com/down/rss.php', 0, '<c>c%电视%2</c><c>c%连续剧%2</c><c>c%综艺%3</c><c>c%.*?%1</c>', '<c>c%国内%1</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '梦幻天堂(论坛)', 'http://www.killman.net/', 'http://www.killman.net/rss.php', 8, NULL, NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '飞鸟娱乐(论坛)', 'http://bbs.hdbird.com/', 'http://bbs.hdbird.com/rss.php', 0, '<c>c%连续剧%2</c><c>c%电影%1</c><c>c%综艺%3</c><c>c%.*?%0</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>c%资源|电影|连续剧%6</c><c>c%.*?%-1</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '中国高清(论坛)', 'http://www.bt49.com/', 'http://www.bt49.com/forum.php?mod=rss', 1, '<c>c%自主发布|字幕|蓝光|影视|电影|原盘|合集|迅雷%1</c><c>c%美剧|连续剧|纪录片%2</c><c>c%动漫%4</c><c>c%综艺%3</c><c>c%.*?%-1</c>', NULL, '<c>c%高清%5</c><c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>c%碟报%1</c><c>c%评论%2</c><c>c%预告%3</c><c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '很BT电影联盟', 'http://henbt.com/', 'http://henbt.com/rss.xml', 0, '<c>c%连续剧%2</c><c>c%综艺%3</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '时光荏苒', 'http://www.sgrr.net', 'http://www.sgrr.net/feed/', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '映像讯', 'http://mkvcn.com/', 'http://mkvcn.com/feed', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%5</c>', '<c>c%碟报%1</c><c>c%评论%2</c><c>c%脱口秀%7</c><c>c%预告|花絮%3</c><c>c%.*?%1</c>', '<c>g%《(.*?)》%1</c><c>g%【(.*?)】%0</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影伙虫', 'http://www.facemov.com/', 'http://www.facemov.com/feed', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, 'VeryCD电影', 'http://www.verycd.com/base/movie/', 'http://www.verycd.com/base/movie/feed', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%7</c>', '<c>l%.*?%7</c>', '<c>x%1%1</c>', NULL, NULL),
(1, 0, 'VeryCD剧集', 'http://www.verycd.com/base/tv/', 'http://www.verycd.com/base/tv/feed', 0, '<c>l%.*?%2</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%7</c>', '<c>l%.*?%7</c>', '<c>x%1%1</c>', NULL, NULL),
(1, 0, 'VeryCD动漫', 'http://www.verycd.com/base/cartoon/', 'http://www.verycd.com/base/cartoon/feed', 0, '<c>l%.*?%4</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%7</c>', '<c>l%.*?%7</c>', '<c>x%1%1</c>', NULL, NULL),
(1, 0, 'VeryCD综艺', 'http://www.verycd.com/base/zongyi/', 'http://www.verycd.com/base/zongyi/feed', 0, '<c>l%.*?%3</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%7</c>', '<c>l%.*?%7</c>', '<c>x%1%1</c>', NULL, NULL),
(1, 0, '大连生活网', 'http://www.dlkoo.com/', 'http://www.dlkoo.com/down/Rss.xml', 1, '<c>c%电影%1</c><c>c%影视%1</c><c>c%电视剧%2</c><c>t%\\[MV\\]%-1</c><c>c%综艺%3</c><c>c%动漫%4</c><c>c%.*?%-1</c>', NULL, '<c>l%.*?%3</c>', '<c>l%.*?%0</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '沒有水的魚', 'http://www.an80.cc/', 'http://www.an80.cc/feed', 0, NULL, NULL, '<c>l%.*?%4</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '云播|电影下载', 'http://www.hanyutang.com/', 'http://www.hanyutang.com/feed', 2, '<c>c%电视剧%2</c><c>c%电影%1</c><c>l%.*?%0</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '第三世界(迅雷)', 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=16&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', '<c>l%.*?%2</c>', NULL),
(0, 0, 'xed2k', 'http://www.xed2k.com/', 'http://www.xed2k.com/forum.php?mod=rss&fid=0&auth=0', 1, '<c>c%电影%1</c><c>l%.*?%-1</c>', NULL, NULL, '<c>l%.*?%5</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '中国高清网', 'http://www.gaoqing.la', 'http://www.gaoqing.la/feed', 0, '<c>c%series%2</c><c>c%.*?%1</c>', NULL, '<c>c%3d%6</c><c>c%.*?%5</c>', '<c>l%.*?%4</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '电影蜜蜂', 'http://www.dybee.net/', 'http://www.dybee.net/feed', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%5</c>', '<c>l%.*?%6</c>', '<c>g%《(.*?)》%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '我爱下最新(论坛)', 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=5&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '我爱下1080P(论坛)', 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=54&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '我爱下720P(论坛)', 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=53&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '我爱下3D(论坛)', 'http://bbs.u4dy.net/', 'http://bbs.u4dy.net/forum.php?mod=rss&fid=63&auth=0', 0, '<c>l%.*?%1</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '一路电影网', 'http://www.16movies.cn/', 'http://www.16movies.cn/feed.xml', 1, '<c>c%电影%1</c><c>c%蓝光原盘%1</c><c>l%3D%1</c><c>l%.*?%-1</c>', NULL, '<c>c%1080|蓝光%6</c><c>c%720%5</c><c>c%3D|半宽%7</c><c>l%.*?%4</c>', '<c>l%.*?%6</c>', '<c>c%电影|大片|蓝光|半宽%6</c><c>c%.*?%-1</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '网易电影频道', 'http://ent.163.com/', 'http://ent.163.com/special/00031K7Q/rss_entmovie.xml', 0, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '网易电视频道', 'http://ent.163.com/', 'http://ent.163.com/special/00031K7Q/rss_enttv.xml', 0, '<c>l%.*?%2</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '磁力搜索', 'http://www.cili.so/', 'http://www.cili.so/rss.php', 7, '<c>l%.*?%0</c>', '<c>l%.*?%0</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '第三世界(高清)', 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=38&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '第三世界(动漫)', 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=25&auth=0', 0, '<c>l%.*?%4</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '第三世界(综艺)', 'http://www.mythe3.com/', 'http://www.mythe3.com/rss.php?fid=18&auth=0', 0, '<c>l%.*?%3</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '第三世界(电视剧)', 'http://www.mythe3.com/', 'http://www.mythe3.com/forum-17-1.html', 0, '<c>l%.*?%2</c>', NULL, NULL, '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '大片网', 'http://www.bigpian.cn/', 'http://www.bigpian.cn/feed', 3, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '圣城发布页', 'http://www.cnscg.org/', 'http://www.cnscg.org/rss.xml', 0, '<c>c%美剧|连续剧%2</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '老调网', 'http://www.bestxl.com/', 'http://www.bestxl.com/rss.php', 0, '<c>c%美剧%2</c><c>c%.*?%1</c>', NULL, NULL, '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国(影评)', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=24&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国32', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=70&auth=0', 0, '<c>l%.*?%2</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '影视帝国', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=68&auth=0', 0, '<c>l%.*?%2</c>', '<c>l%.*?%1</c>', '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '影视帝国(评论)', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=12&auth=0', 0, '<c>c%.*?%1</c>', NULL, '', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=69&auth=0', 0, '<c>l%.*?%2</c>', '<c>l%.*?%2</c>', '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '影视帝国', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=8&auth=0', 0, '<c>l%.*?%1</c>', '<c>l%.*?%1</c>', '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=9&auth=0', 0, '<c>l%.*?%1</c>', '<c>l%.*?%2</c>', '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=10&auth=0', 0, '<c>l%.*?%1</c>', '<c>l%.*?%3</c>', '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=21&auth=0', 0, '<c>l%.*?%4</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国(1080)', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=36&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%6</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国(3D)', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=74&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%7</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国(720p)', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=37&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国(720p)', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=38&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国(720p)', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=63&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国(720p)', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=33&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '影视帝国(1024)', 'http://www.y4dg.com/', 'http://www.y4dg.com/forum.php?mod=rss&fid=41&auth=0', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '3E帝国', 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=180&auth=0', 1, '<c>l%.*?%2</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '3E帝国', 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=231&auth=0', 1, '<c>l%.*?%2</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%3</c>', NULL),
(1, 0, '3E帝国', 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=154&auth=0', 1, '<c>l%.*?%2</c>', '<c>l%.*?%1</c>', '<c>l%.*?%4</c>', '<c>l%.*?%6</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '3E帝国', 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=99&auth=0', 1, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%6</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, '3E帝国', 'http://bbs.3e-online.com/', 'http://bbs.3e-online.com/forum.php?mod=rss&fid=194&auth=0', 1, '<c>l%.*?%1</c>', '<c>l%.*?%2</c>', '<c>l%.*?%4</c>', '<c>l%.*?%6</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1000, 0, '无忧无虑', 'http://www.bt5156.com/', 'http://www.bt5156.com/', 0, '<c>l%zongyi%3</c><c>l%dongman%4</c><c>l%tv%2</c><c>l%dy|movie%1</c><c>l%.*?%-1</c>', '<c>l%\\/hytv\\/%1</c><c>l%\\/rihantv\\/%3</c><c>l%\\/oumeitv\\/%2</c><c>t%中国|香港|台湾|大陆|澳门%1</c><c>t%欧美|美国|欧洲|俄罗斯|英国|法国|德国%2</c><c>t%日本|韩国%3</c><c>l%.*?%0</c>', '<c>t%TC|TS|抢先%1</c><c>l%\\/3d\\/%6</c><c>t%DVD%3</c><c>t%BD|1280|高清|HD%4</c><c>l%.*?%3</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>g%《(.*?)》%1</c><c>g%(.*?)-20%1</c>', '<c>l%.*?%2</c>', NULL),
(1000, 0, '红潮网', 'http://www.5281520.com/', 'http://www.5281520.com/', 0, '<c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动画片%4</c><c>c%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1000, 0, '高清影视下载网', 'http://www.gaoqing.tv', 'http://www.gaoqing.tv', 0, '<c>l%movie|m3d|m3d|mp4%1</c><c>l%.*?%2</c>', NULL, '<c>l%mp4%4</c><c>l%m3d%6</c><c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', '<c>f%\\(.*?\\)%1</c><c>f%\\[.*?\\]%1</c>', '<c>l%.*?%2</c>', NULL),
(1000, 0, 'ed2000', 'http://www.ed2000.com/', 'http://www.ed2000.com/', 0, '<c>c%电影%1</c><c>c%剧集%2</c><c>c%动漫%4</c><c>c%综艺%3</c><c>l%.*?%-1</c>', NULL, '<c>l%.*?%4</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1000, 0, '飘花资源网', 'http://www.piaohua.com', 'http://www.piaohua.com', 0, '<c>c%电视剧%2</c><c>c%电影%1</c>', '<c>l%.*?%0</c>', '<c>l%.*?%3</c>', '<c>l%.*?%2</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1000, 0, '搜磁力链接', 'http://www.somag.net/', 'http://www.somag.net/', 0, '<c>c%电影%1</c><c>c%动漫%4</c><c>c%3D%1</c><c>c%.*?%2</c>', '<c>c%美剧%2</c><c>c%日剧%3</c><c>c%韩剧%3</c><c>c%.*?%0</c>', '<c>t%1080p%6</c><c>t%720p%5</c><c>t%HD%5</c><c>c%3D%7</c><c>c%.*?%4</c>', '<c>l%.*?%4</c>', '<c>l%.*?%6</c>', '<c>g%《(.*?)》%1</c>', '<c>l%.*?%2</c>', NULL),
(1000, 0, '电影天堂', 'http://www.dy2018.com/', 'http://www.dy2018.com/', 0, '<c>c%电影%1</c><c>c%电视剧%2</c><c>c%综艺%3</c><c>c%动漫%4</c><c>l%.*?%0</c>', '<c>c%华语%1</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', '<c>l%.*?%5</c>', '<c>l%.*?%2</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1000, 0, '人人影视', 'http://www.yyets.com', 'http://www.yyets.com', 0, '<c>c%电影%1</c><c>c%剧%2</c><c>l%.*?%0</c>', '<c>c%大%3</c><c>c%美|英%2</c><c>c%日|韩%3</c><c>l%.*?%0</c>', '<c>c%720P%4</c><c>c%1080P%5</c><c>l%.*?%3</c>', '<c>l%.*?%10</c>', '<c>l%.*?%6</c>', '<c>g%《(.*?)》%0</c>', '<c>l%.*?%2</c>', NULL),
(1000, 0, 'BT天堂', 'http://www.bttiantang.com/', 'http://www.bttiantang.com/', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', '<c>g%《(.*?)》%1</c>', '<c>l%.*?%2</c>', NULL),
(2000, 0, '格瓦拉活动', 'http://www.gewara.com/activity/', 'http://www.gewara.com/activity/', 0, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>l%.*?%4</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(2000, 0, '金鹰网', 'http://www.hunantv.com', 'http://www.hunantv.com', 0, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(2000, 0, '凤凰网娱乐', 'http://ent.ifeng.com', 'http://ent.ifeng.com', 0, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', '<c>c%华语%1</c><c>c%海外%2</c><c>c%欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(2000, 0, '网易娱乐', 'http://ent.163.com', 'http://ent.163.com', 0, '<c>c%电影%1</c><c>c%电视%2</c><c>c%综艺%3</c>', '<c>c%华语|港台%1</c><c>c%欧美日韩%0</c><c>c%海外|欧美%2</c><c>c%日韩%3</c><c>c%.*?%0</c>', NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(2000, 0, '新浪评论', 'http://ent.sina.com.cn/review/', 'http://ent.sina.com.cn/review/', 0, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(2000, 0, '搜狐娱乐', 'http://yule.sohu.com', 'http://yule.sohu.com', 0, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(2000, 0, '时光网新闻', 'http://news.mtime.com', 'http://news.mtime.com', 10, '<c>c%电影%1</c><c>c%电视%2</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%1</c><c>g%`(.*?)`%0</c>', NULL, NULL),
(2000, 0, '时光网评论', 'http://www.mtime.com/review', 'http://www.mtime.com/review', 0, '<c>c%.*?%1</c>', NULL, NULL, NULL, '<c>l%.*?%2</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(2000, 0, '海上电影', 'http://www.sfs-cn.com/', 'http://www.sfs-cn.com/', 0, NULL, NULL, NULL, NULL, '<c>l%.*?%1</c>', NULL, NULL, NULL),
(2000, 0, '格瓦拉影讯', 'http://www.gewara.com/news/cinema', 'http://www.gewara.com', 0, '<c>l%.*?%1</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '星飞宇部落', 'http://www.xfyrb.com/', 'http://www.xfyrb.com/feed', 0, '<c>l%movie|yazhuo%1</c><c>l%tv%2</c><c>l%uncategorized%3</c><c>l%.*?%0</c>', '<c>l%cn|gt%1</c><c>l%om%2</c><c>l%yazhuo%3</c><c>l%.*?%0</c>', '<c>l%.*?%5</c>', '<c>l%.*?%1</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL),
(1, 0, 'xed2k资讯', 'http://www.xed2k.com/portal.php?mod=list&catid=2', 'http://www.xed2k.com/portal.php?mod=rss&catid=2', 1, '<c>c%电影%1</c>', NULL, NULL, NULL, '<c>l%.*?%1</c>', '<c>g%《(.*?)》%0</c>', NULL, NULL),
(1, 0, '光影资源联盟', 'http://www.etdown.net/', 'http://www.etdown.net/', 0, '<c>l%.*?%1</c>', NULL, '<c>l%.*?%5</c>', '<c>l%.*?%3</c>', '<c>l%.*?%6</c>', '<c>x%1%1</c>', '<c>l%.*?%2</c>', NULL);

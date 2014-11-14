logfile=/srv/php/log/run.log
rm -rf $logfile
echo $(date) > $logfile
## 1 资源获取
## 1.1从rss获取的资源
/srv/php/timeexc.sh curl http://php.movie002.com/reader/getrss.php -o /srv/php/log/getrss.log
## 1.2使用代码从(资源)获取的资源
/srv/php/timeexc.sh curl http://php.movie002.com/reader/c1000/get.php -o /srv/php/log/c1000.log
## 1.3使用代码从(纯链接)获取的资源
/srv/php/timeexc.sh curl http://php.movie002.com/reader/c2000/get.php -o /srv/php/log/c2000.log

## 2 资源处理
## 2.1 处理onlylink，得到mtitle
/srv/php/timeexc.sh curl http://php.movie002.com/getpage/getonlylink.php?d=10 -o /srv/php/log/getonlylink.log
## 2.1 处理onlylink，将onlylink处理到link和link2
/srv/php/timeexc.sh curl 'http://php.movie002.com/getpage/getlink2.php?f1=0&f2=2&r=0.9&d=10' -o /srv/php/log/getlink1.log
/srv/php/timeexc.sh curl 'http://php.movie002.com/getpage/getlink2.php?f1=3&f2=5&r=0.4&d=10' -o /srv/php/log/getlink2.log
## 2.2 处理电影的状态，正在上映和即将上映的
/srv/php/timeexc.sh curl http://php.movie002.com/getpage/upmstatus.php -o /srv/php/log/upmstatus.log
## 2.4 利用onlylink得到page并修改其状态
/srv/php/timeexc.sh curl http://php.movie002.com/getpage/getpage.php?d=2 -o /srv/php/log/getpage.log
## 2.4 更新page的其他属性
/srv/php/timeexc.sh curl http://php.movie002.com/getpage/updatepage.php?d=2 -o /srv/php/log/getpage.log
## 2.5 更新page的状态，资源数等
/srv/php/timeexc.sh curl http://php.movie002.com/getpage/updatestatus.php?d=60 -o /srv/php/log/updatestatus.log
## 2.5 得到票房信息
##/srv/movie002/timeexc.sh curl http://php.movie002.com/reader/movie.mtime.com.boxoffice.php -o /srv/php/log/boxoffice.log
## 2.5 得到演员信息
/srv/php/timeexc.sh curl http://php.movie002.com/reader/getcele/getcele.php -o /srv/php/log/getcele.log
##/srv/movie002/timeexc.sh  curl http://php.movie002.com/reader/getcele/getimgurl.php -o /srv/php/log/getimgurl.log

## 3 页面生成
## 3.1 生成公共页面
##/srv/php/timeexc.sh curl http://php.movie002.com/gendb/gen_share.php -o /srv/php/log/genshare.log
## 3.4 生成index
/srv/php/timeexc.sh curl http://php.movie002.com/genv/gen_index.php -o /srv/php/log/genindex.log
## 3.2 生成pages
/srv/php/timeexc.sh curl http://php.movie002.com/genv/gen_page.php?d=30 -o /srv/php/log/genpage.log
## 3.3 生成list
/srv/php/timeexc.sh curl http://php.movie002.com/genv/gen_list.php -o /srv/php/log/genlist.log
## 3.5 生成辅助页面，友情链接之类的
/srv/php/timeexc.sh curl http://php.movie002.com/genauthor/gen.php -o /srv/php/log/genauthor.log
## 3.6 生成sitemap
/srv/php/timeexc.sh curl http://php.movie002.com/genv/sitemap/gen.php -o /srv/php/log/gensitemap.log
## 3.7 生成数据库备份
#/srv/movie002/timeexc.sh  curl http://php.movie002.com/backup/dumpfunc.php -o /srv/php/log/dumpfunc.log

## 4 通知更新
/srv/php/timeexc.sh curl http://php.movie002.com/genv/gen_update.php -o /srv/php/log/genupdate.log
echo $(date) >> $logfile

## 4 操作日志发送到邮箱
curl http://php.movie002.com/log/back_log.php
logfile=/srv/movie002/log/run.log
rm -rf $logfile
echo $(date) > $logfile
## 1 资源获取
## 1.1从rss获取的资源
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/reader/getrss.php -o /srv/movie002/log/getrss.log
## 1.2使用代码从(资源)获取的资源
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/reader/c1000/get.php -o /srv/movie002/log/c1000.log
## 1.3使用代码从(纯链接)获取的资源
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/reader/c2000/get.php -o /srv/movie002/log/c2000.log

## 2 资源处理
## 2.1 处理onlylink，将onlylink处理到link和link2
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/reader/getlink.php -o /srv/movie002/log/getlink.log
## 2.2 处理电影的状态，正在上映和即将上映的
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/getpage/upmstatus.php -o /srv/movie002/log/upmstatus.log
## 2.3 利用link2修改page的状态
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/getpage/getpage2.php -o /srv/movie002/log/getpage2.log
## 2.4 利用link得到page并修改其状态
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/getpage/getpage.php -o /srv/movie002/log/getpage.log
## 2.5 更新page的状态，资源数等
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/getpage/updatestatus.php?d=2 -o /srv/movie002/log/updatestatus.log
## 2.5 得到票房信息
##/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/reader/movie.mtime.com.boxoffice.php -o /srv/movie002/log/boxoffice.log
## 2.5 得到演员信息
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/reader/getcele/getcele.php -o /srv/movie002/log/getcele.log
##/srv/movie002/timeexc.sh  curl http://127.0.0.1/movie002/reader/getcele/getimgurl.php -o /srv/movie002/log/getimgurl.log

## 3 页面生成
## 3.1 生成公共页面
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/gen/gen_share.php -o /srv/movie002/log/genshare.log
## 3.2 生成pages
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/gen/gen_page.php?d=30 -o /srv/movie002/log/genpage.log
## 3.3 生成list
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/gen/gen_list.php -o /srv/movie002/log/genlist.log
## 3.4 生成index
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/gen/gen_index.php -o /srv/movie002/log/genindex.log
## 3.5 生成辅助页面，友情链接之类的
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/gen/gen_author.php -o /srv/movie002/log/genauthor.log
## 3.6 生成sitemap
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/sitemap/gen.php -o /srv/movie002/log/gensitemap.log
## 3.7 生成数据库备份
#/srv/movie002/timeexc.sh  curl http://127.0.0.1/movie002/backup/dumpfunc.php -o /srv/movie002/log/dumpfunc.log

## 4 通知更新
/srv/movie002/timeexc.sh curl http://127.0.0.1/movie002/gen/gen_update.php -o /srv/movie002/log/genupdate.log
echo $(date) >> $logfile

## 4 操作日志发送到邮箱
curl http://127.0.0.1/movie002/log/back_log.php
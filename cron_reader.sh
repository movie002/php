logfile=/srv/php/log/run.log
rm -rf $logfile
echo $(date) > $logfile
## 1 资源获取
## 1.1从rss获取的资源
/srv/php/timeexc.sh curl 'http://127.0.0.1/php/reader/getrss.php?fid=1&lid=40' -o /srv/php/log/getrss1.log
/srv/php/timeexc.sh curl 'http://127.0.0.1/php/reader/getrss.php?fid=41&lid=80' -o /srv/php/log/getrss2.log
/srv/php/timeexc.sh curl 'http://127.0.0.1/php/reader/getrss.php?fid=81&lid=1000' -o /srv/php/log/getrss3.log
## 1.2使用代码从(资源)获取的资源
/srv/php/timeexc.sh curl http://127.0.0.1/php/reader/c1000/get.php -o /srv/php/log/c1000.log
## 1.3使用代码从(纯链接)获取的资源
/srv/php/timeexc.sh curl http://127.0.0.1/php/reader/c2000/get.php -o /srv/php/log/c2000.log

## 2 资源处理
## 2.1 处理onlylink，得到mtitle
/srv/php/timeexc.sh curl http://127.0.0.1/php/getpage/getonlylink.php?d=10 -o /srv/php/log/getonlylink.log
## 2.1 处理onlylink，将onlylink处理到link和link2
/srv/php/timeexc.sh curl 'http://127.0.0.1/php/getpage/getlink2.php?f1=0&f2=2&r=0.9&d=10' -o /srv/php/log/getlink1.log
/srv/php/timeexc.sh curl 'http://127.0.0.1/php/getpage/getlink2.php?f1=3&f2=5&r=0.4&d=10' -o /srv/php/log/getlink2.log
## 2.2 处理电影的状态，正在上映和即将上映的
/srv/php/timeexc.sh curl http://127.0.0.1/php/getpage/upmstatus.php -o /srv/php/log/upmstatus.log
## 2.4 利用onlylink得到page并修改其状态
/srv/php/timeexc.sh curl http://127.0.0.1/php/getpage/getpage.php?d=2 -o /srv/php/log/getpage.log
## 2.4 更新page的其他属性
/srv/php/timeexc.sh curl http://127.0.0.1/php/getpage/updatepage.php?d=2 -o /srv/php/log/getpage.log
## 2.5 更新page的状态，资源数等
/srv/php/timeexc.sh curl http://127.0.0.1/php/getpage/updatestatus.php?d=60 -o /srv/php/log/updatestatus.log
## 2.5 得到票房信息
##/srv/movie002/timeexc.sh curl http://127.0.0.1/php/reader/movie.mtime.com.boxoffice.php -o /srv/php/log/boxoffice.log
## 2.5 得到演员信息
/srv/php/timeexc.sh curl http://127.0.0.1/php/reader/getcele/getcele.php -o /srv/php/log/getcele.log
##/srv/movie002/timeexc.sh  curl http://127.0.0.1/php/reader/getcele/getimgurl.php -o /srv/php/log/getimgurl.log

## 4 通知更新
/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_update.php -o /srv/php/log/genupdate.log
echo $(date) >> $logfile

## 4 操作日志发送到邮箱
curl http://127.0.0.1/php/log/back_log.php

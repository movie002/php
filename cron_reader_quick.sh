logfile=/srv/php/log/run.log
rm -rf $logfile
echo $(date) > $logfile
##这个执行任务里面的执行频率较快,两个小时执行一次
## 1 资源获取
## 1.1从rss获取的资源
/srv/php/timeexc.sh curl 'http://127.0.0.1/php/reader/getrss.php?id=9' -o /srv/php/log/getrss.log
## 1.2使用代码从(资源)获取的资源
##/srv/php/timeexc.sh curl http://127.0.0.1/php/reader/c1000/get.php -o /srv/php/log/c1000.log
## 1.3使用代码从(纯链接)获取的资源
##/srv/php/timeexc.sh curl http://127.0.0.1/php/reader/c2000/get.php -o /srv/php/log/c2000.log

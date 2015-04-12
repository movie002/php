logfile=/srv/php/log/run.log
rm -rf $logfile
echo $(date) > $logfile
## 3 页面生成
## 3.1 生成公共页面
##/srv/php/timeexc.sh curl http://127.0.0.1/php/genx/gen_share.php -o /srv/php/log/genshare.log
## 3.4 生成index
/srv/php/timeexc.sh curl http://127.0.0.1/php/genx/gen_index.php -o /srv/php/log/xgenindex.log
## 3.2 生成pages
/srv/php/timeexc.sh curl http://127.0.0.1/php/genx/gen_page.php?d=30 -o /srv/php/log/xgenpage.log
## 3.3 生成list
/srv/php/timeexc.sh curl http://127.0.0.1/php/genx/gen_list.php -o /srv/php/log/xgenlist.log
## 3.5 生成辅助页面
/srv/php/timeexc.sh curl http://127.0.0.1/php/genx/gen_static.php -o /srv/php/log/xgenstatic.log
## 3.6 生成sitemap
/srv/php/timeexc.sh curl http://127.0.0.1/php/genx/gen_sitemap.php -o /srv/php/log/xgensitemap.log
/srv/php/timeexc.sh curl http://127.0.0.1/php/genx/gen_search.php -o /srv/php/log/xgensearch.log

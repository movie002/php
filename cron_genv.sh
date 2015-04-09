logfile=/srv/php/log/run.log
rm -rf $logfile
echo $(date) > $logfile
## 3 页面生成
## 3.1 生成公共页面
##/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_share.php -o /srv/php/log/genshare.log
## 3.4 生成index
/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_index.php -o /srv/php/log/genindex.log
## 3.2 生成pages
/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_page.php?d=30 -o /srv/php/log/genpage.log
## 3.3 生成list
/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_list.php -o /srv/php/log/genlist.log
## 3.5 生成辅助页面，友情链接之类的
/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_static.php -o /srv/php/log/genstatic.log
## 3.6 生成sitemap
/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_sitemap.php -o /srv/php/log/gensitemap.log
## 3.6 生成统计
/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_author.php -o /srv/php/log/genauthor.log
/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_update.php -o /srv/php/log/genupdate.log
/srv/php/timeexc.sh curl http://127.0.0.1/php/genv/gen_search.php -o /srv/php/log/gensearch.log

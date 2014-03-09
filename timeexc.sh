logfile=/srv/php/log/run.log
echo '' >> $logfile
echo $* >> $logfile

##抽取log文件
NAME=$*
LOGFILE=`echo ${NAME##*log/}`
echo 'log file:  http://php.movie002.com/log/'$LOGFILE >> $logfile
timebegin=$(date +%s)
echo 'begin: ' $(date) >> $logfile
$*
timeend=$(date +%s)
echo 'end: ' $(date) >> $logfile
usetime=$(($timeend-$timebegin))
echo 'timeuse: ' $usetime s >> $logfile

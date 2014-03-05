logfile=/srv/movie002/log/run.log
echo '' >> $logfile
echo $* >> $logfile

##抽取log文件
NAME=$*
LOGFILE=`echo ${NAME##*log/}`
echo 'log file:  http://www.movie002.com/movie002/log/'$LOGFILE >> $logfile
timebegin=$(date +%s)
echo 'begin: ' $(date) >> $logfile
$*
timeend=$(date +%s)
echo 'end: ' $(date) >> $logfile
usetime=$(($timeend-$timebegin))
echo 'timeuse: ' $usetime s >> $logfile

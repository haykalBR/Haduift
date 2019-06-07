#!/usr/bin/env bash
### BEGIN INIT INFO
# Provides:          ABS_Controlle_Channels
# Required-Start:    $remote_fs $syslog
# Required-Stop:     $remote_fs $syslog
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Run Chanels cron job of the lucky panel
# Description:       Run Chanels cron job of the lucky panel
#                    to controle chanels transcodings.
### END INIT INFO

PIDCount=$(ps auxw | grep -v grep | grep -c 'server:commande')

PID=$(ps -ef | grep 'server:commande' | grep -v grep | awk '{print $2}')

LOGFILE=/var/log/WebServe.log

if [ ! "$PIDCount" == "1" ] && [ ! "$PIDCount" == "0" ]; then
    echo "$PIDCount cron jobs are running at the same time please contact Haykel Brinis  or use KILL Commande or restart the server"
    return -1
fi

#Create the file
FileCreater () {
    if [ ! -f "$1" ];then
        touch $1
        chown www-data:www-data $1
    fi
}

#DELETE THE FILE
DeleteFile (){
    FileCreater $1
    rm -rf $1
}

#start the service.
start() {
  if [ "$PIDCount" == "1" ]; then
    echo 'Service already running' >&2
    return 1
  fi

  #delete the log file and recreat it
  DeleteFile $LOGFILE
  FileCreater $LOGFILE

#Starting the service.
  echo 'Starting service…'
#product service cron:server:monitor --env=prod &
  php /var/www/html/Haduift/bin/console server:commande &> $LOGFILE &
  echo 'Service started'
}

#Stoping services
stop() {
#service is not running
  if [ "$PIDCount" == "0" ]; then
    echo "service is not running"
    return 1
  fi
#Stoping services
  echo 'Stopping service…' >&2
  kill -15 $PID
  echo 'Service stopped' >&2
  PIDCount=$(ps auxw | grep -v grep | grep -c 'server:commande')
}

case "$1" in
  start)
    start
    ;;
  stop)
    stop
    ;;
  restart)
    stop
    start
    ;;
  *)
    echo "Usage: {start|stop|restart|uninstall}"
esac

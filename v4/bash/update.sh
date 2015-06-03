#/bin/bash
for i in `cat ./ip/iplist`
do
    ssh -l root $i "cd /www/v4 && git pull -u origin master " # update
done

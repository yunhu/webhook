#/bin/bash
for i in `cat ./ip/updateiplist.txt`
do
    ssh -l root $i "cd /www/mbs && git pull -u origin master && /home/phpdoc/createDoc.sh" # fix bug
	#ssh -l root $i "/www/webhook/update.sh" 
done

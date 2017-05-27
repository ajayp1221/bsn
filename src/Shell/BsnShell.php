<?php
namespace App\Shell;

use Cake\Console\Shell;
/*
 
PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
0,5,10,15,20,25,30,35,40,45,50,55 * * * * cd /var/www/business.zakoopi.com/current && php bin/cake.php bsn perminute
0 9 * * * cd /var/www/business.zakoopi.com/current && php bin/cake.php bsn perday
0 * * * * cd /var/www/business.zakoopi.com/current && php bin/cake.php bsn hourly
0 4 * * * cd /var/www/business.zakoopi.com/current && php bin/cake.php bsn perdayatfour
0 6 * * * cd /var/www/business.zakoopi.com/current && php bin/cake.php bsn perdayatsix
0 3 * * * cd /var/www && sh dbdump.sh
* 6 * / 10 * * cd /var/www/business.zakoopi.com/current && php bin/cake.php bsn everytendays

# Auto restart MySQL
# * * * * * sudo service mysql status || sudo service mysql start



 */
class BsnShell extends Shell
{
    public function main()
    {
        $this->out('Welcome to business.zakoopi.com '. date("d-m-Y h:i:s A") );
    }
//    public function dumpdb(){
//        $db_file = "zakoopi_new-".date('d-m-Y(h:i:s A)').".sql";
//        $cmd = "cd ..; mysqldump zakoopi_new > /var/www/html/production/bkp/db/".$db_file." --user='root' --password='ALinux@zakoopi'";
//        $output = null;
//        $return_var = null;
//        exec($cmd, $output, $return_var);
//    }
    
//    public function StoreScoreUpdates(){
//        // use for store score updates 
//        $db_file = "zakoopi_new-".date('d-m-Y(h:i:s A)').".sql";
//        $cmd = "cd ..; mysqldump zakoopi_new > /var/www/html/production/bkp/db/".$db_file." --user='root' --password='ALinux@zakoopi'";
//        file_get_contents('http://www.zakoopi.com/batchjobs/StoreScoreUpdate');
//        $output = null;
//        $return_var = null;
//        exec($cmd, $output, $return_var);
//    }
    
    public function hourly(){ // This function run per hours
//        file_put_contents(WWW_ROOT.'rq.txt', "Cron Per Hour : ".date('c'));
        //file_get_contents('https://api.thingspeak.com/update?api_key=FYSMFCNMXFAFSUJ0&field2='.date('c'));
        file_get_contents('http://business.zakoopi.com/cron/repeatemailanniversary');
        file_get_contents('http://business.zakoopi.com/cron/repeatemailbday');
        file_get_contents('http://business.zakoopi.com/cron/repeatsmsanniversary');
        file_get_contents('http://business.zakoopi.com/cron/repeatsmsbday');
        file_get_contents('http://business.zakoopi.com/cron/daily-report');
    }
    public function perminute(){ // This function run in every 5 minutes
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', -1);
        //file_get_contents('https://api.thingspeak.com/update?api_key=FYSMFCNMXFAFSUJ0&field1='.date('c'));
//        file_get_contents('http://www.zakoopi.com/batchjobs/android_gcm');
//        file_put_contents(WWW_ROOT.'rq.txt', "Cron Perminute : ".date('c')."---");
        file_get_contents('http://business.zakoopi.com/cron/nonrepeatsms');
        file_get_contents('http://business.zakoopi.com/cron/nonrepeatemail');
        file_get_contents('http://business.zakoopi.com/cron/mobilepush');
        file_get_contents('http://business.zakoopi.com/cron/low-balance-alert');
        
        file_get_contents('http://business.zakoopi.com/cron/jdimap');
        file_get_contents('http://business.zakoopi.com/cron/jdservicesimap');
        file_get_contents('http://business.zakoopi.com/cron/metroimap');
        
    }
    public function perday(){ // This function run at 9:00 am everyday
        //file_get_contents('https://api.thingspeak.com/update?api_key=FYSMFCNMXFAFSUJ0&field3='.date('c'));
//        file_put_contents(WWW_ROOT.'rq.txt', "Cron Per Day : ".date('c'));
        file_get_contents('http://business.zakoopi.com/cron/shareposts');
        file_get_contents('http://business.zakoopi.com/cron/album-expire');
        file_get_contents('http://business.zakoopi.com/cron/last-vist-notify');
        
    }
    
    public function perdayatfour(){  // This function run at 4:00 am everyday
//        file_get_contents('http://business.zakoopi.com/cron/mandrillresponse');
        file_get_contents('http://business.zakoopi.com/cron/gupshup-log');
        file_get_contents('http://business.zakoopi.com/cron/message-gupshup-res');
        file_get_contents('http://business.zakoopi.com/cron/sms-leadger');
    }

    public function perdayatsix(){  // This function run at 6:00 am everyday
//        file_get_contents('http://business.zakoopi.com/cron/plivoresponse');
		file_get_contents('http://business.zakoopi.com/cron/ten-days-balance-alert');
    }
    
    public function everytendays(){
//        file_get_contents('http://business.zakoopi.com/cron/ten-days-balance-alert');   
    }
    
}
<?php
    //author : Firman Susanto
    //this script for replacing amiquote in amibroker.
    //<date(Y-M-D), <ticker> the rest is same.

    ini_set('max_execution_time', '-1');
    date_default_timezone_set("Asia/Bangkok");
    $param['quotes']       = ['BBRI', 'BBCA'];
    $param['start']        = '2000-01-01';
   
    // $yesterday = new DateTime('yesterday');
    // $param['start'] = $yesterday->format('Y-m-d'); 

    $param['end']          = date('Y-m-d');
    $param['savedQuotes']  = 'A:/Hobby/Stocks/AmiBrokerData/Scrapping Result/';
    getData($param);
    function getData($param){
        $strToTimeStart = strtotime($param['start']);
        $strToTimeEnd   = strtotime($param['end']);
        $content = "<ticker>,<ymd>,<open>,<high>,<low>,<close>,<volume>\n";
        for($i = 0 ; $i < count($param['quotes']); $i++){ 
            $item = $param['quotes'][$i];
            $count = 0;
            $url = 'https://query1.finance.yahoo.com/v7/finance/download/'.$item.'.JK?period1='.$strToTimeStart.'&period2='.$strToTimeEnd.'&interval=1d&events=history'; 
            if (($handle = @fopen($url, "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
                    if($count != 0) { 
                        $date   = $row[0];
                        $open   = $row[1];
                        $high   = $row[2];
                        $low    = $row[3];
                        $close  = $row[4];
                        $volume = $row[6];
                        $content .= $item.",".$date.",".$open.",".$high.",".$low.",".$close.",".$volume."\n";
                    }
                    $count++;
                }
            }
            echo $item." Success"."<br>";
        }
        $fp = fopen($param['savedQuotes'].$param['start'].'-'.$param['end'].".txt","wb");
        fwrite($fp,$content);
        fclose($fp);
        fclose($handle);
    }
    echo "Download Data Success";
?>


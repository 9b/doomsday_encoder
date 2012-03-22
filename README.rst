Purpose
=======
AJAX will soon be adopted more in malicious JavaScript code. This encoder and packaged content serves as a testing harness for those interested in combating techniques that may find their way into exploit kits. These encoders allow researchers to investigate how they are built, how they operate and prove that these techniques are easy to implement. 

Dependencies
============
* MySQL (could remove this and use sessions instead)
* PHP

Primary Class Library
=====================
* dramaqueen.php

Implementing in your Code
=========================
1. Include the dramaqueen.php library:
	
	include_once 'utilities/dramaqueen.php';
2. Create an instance of the kenai class:
	
	$obfuscator = new kenai();

3. Pass the code you want encoded to the lesda_encoder:

	$data_result = $obfuscator->lesda_encoder("document.write('9b+');");

4. Pass the result of lesda to the hilma_encoder:

	$data_result = $obfuscator->hilma_encoder($data_result);

5. Return the result to the user (you need to wrap it in script tags)

redgift
=======
Demonstration of encoder with static AJAX url used for processing

Live example: www.9bplus.com/redgift/direct.php

bluegift
========
Demonstration of encoder with dynamic AJAX url that deletes itself after a specfied time

Live example: www.9bplus.com/bluegift/direct.php

greengift
=========
Demonstration of encoder contents delivered through AJAX

Live Example: www.9bplus.com/greengift/index.php?token=<token displayed on the landing page>

orangegift
==========
Demonstration of exta encoder (rolling modulus) wrapping AJAX secondary payload

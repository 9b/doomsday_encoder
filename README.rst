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

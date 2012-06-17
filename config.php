<?php

// REPLACE THIS: replace the values below with the values assigned by round sky
define('PARTNER_ID', 'contigodirect');
define('PARTNER_PASSWORD', 'b45a07fda2de360');

define('PARTNER_DOMAIN', 'thepaydayhound.com');

//sub_id, used to a sub source, like affiliate id, or name of this site, etc.
//that way if one sub source is not working out, we can pull just that one source and not the whole account.
define('SUB_ID', '');

//do you want Round Sky to list manage the leads so you earn extra revenue?
//talk with Round Sky first.
define('LIST_MANAGE', 1);

// how long to allow the script to run PER tier
// minimum is 30
define('MAXIMUM_TIME',     180);

//which tiers are you posting to?
//default is 12 tiers provided by Round Sky.
$TIERS = array();
$TIERS[] = 1;
$TIERS[] = 2;
$TIERS[] = 3;
$TIERS[] = 4;
$TIERS[] = 5;
$TIERS[] = 6;
$TIERS[] = 7;
$TIERS[] = 8;
$TIERS[] = 9;
$TIERS[] = 10;
$TIERS[] = 11;
$TIERS[] = 12;


//by default the script posts to the LIVE URL.
//if you need to test comment out the LIVE URL and uncomment out the TEXT URL.
//then when you are ready to go live, uncoment the LIVE URL (remove the two // in front of the line) and comment out the TEST URL (add // in front of the line)
// live post url
define('POST_URL',         'http://www.leadhorizon.com/leads/payday/live.php');
// test post url
//define('POST_URL',         'http://www.leadhorizon.com/leads/payday/test.php');

//-----------------------do not change below this line-----------------------------//

// do not change. this is the message received from lead horizon on a successful post
define('APPROVED_MESSAGE', 'APPROVED');
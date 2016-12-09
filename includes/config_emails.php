<?php

//Use Database or Files to store emails -> 1=database,2=files

$SYSTEM_TYPE = 1;



/***********

If USING OPTION 1,

Please insure that you change file: load_database.php

to reflect database information. Then run file: install_data.php	

*************/



/*

If using option 2 for SYSTEM_TYPE

You need file location, Must be Chmoded to 777

Do not know how to CHMOD? Visit: http://www.free-php-scripts.net/CHMOD

*/



$FILE_LOCATION = 'email_list.txt';



/*

Require email confirmation to submitted requests

Those who join the mailing list MUST confirm before added

*/

$REQUIRE_CONFIRM = 1; // 1-> yes, 2 -> no



/*

Allow unsubscription requests.

*/

$ALLOW_REMOVAL = 1; // 1-> yes, 2 -> no



//Site title

$EMAIL_OPTIONS['TITLE'] = 'Kia Motors Ecuador';

//Site URL

$EMAIL_OPTIONS['URL'] = 'http://kia.com.ec/';

//Main email

//$EMAIL_OPTIONS['FROM'] = 'kia@aekia.com.ec';

//$EMAIL_OPTIONS['FROM'] = 'boletin@kia.com.ec';

$EMAIL_OPTIONS['FROM'] = 'info@kia.com.ec';
//$EMAIL_OPTIONS['FROM'] = 'renan@ecuaplanettrading.com';

//Charset 

$EMAIL_OPTIONS['CHARSET'] = 'utf-8';

//Type -> HTML=text/html | Text = text/plain

$EMAIL_OPTIONS['TYPE'] = 'text/html';



/*

Confirmation email template

---------------------------------

For this template you can use the following variables:

	$+confirm_require+$ -> Confirmation requirement statement if needed

*/

$REQUIRE_TEMPLATE = '<b>Hol@,</b></span><br /><br />

	<span align="justify"><b>Recibimos un pedido de Ud. para unirse al bolet&iacute;n '.$EMAIL_OPTIONS['TITLE'].'.</b></span><br /><br />

	<span align="justify"><b>$+confirm_require+$.</b></span><br /><br />

	<span align="justify"><b>'.$EMAIL_OPTIONS['TITLE'].'</b></span><br />';

	

/*

Remove confirmation email template

---------------------------------

For this template you can use the following variables:

	$+email_address+$ -> Email removed

*/

$REMOVE_TEMPLATE = '<b>Hello,</b></span><br /><br />

	<span align="justify"><b>We have received a request from you to be removed from '.$EMAIL_OPTIONS['TITLE'].' mailing list.</b></span><br /><br />

	<span align="justify"><b>As of this moment, your email $+email_address+$ is removed from the mailing list.</b></span><br /><br />

	<span align="justify"><b>Feel free to rejoin our mailing list anytime @ '.$EMAIL_OPTIONS['URL'].'mailing_list.php.</b></span><br /><br />

	<span align="justify"><b>'.$EMAIL_OPTIONS['TITLE'].'</b></span><br />';	



/* +++++++++++++++++++++++++++++++++++++

		END FILE 

---------------------------------------*/



?>
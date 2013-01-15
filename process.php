<?
require_once('config.php');
require_once('roundsky_class.php');
require_once('functions.php');


// initialize the lead data structure
$lead = array(
	'partner'          => PARTNER_ID,
	'partner_password' => PARTNER_PASSWORD,
	'domain'           => PARTNER_DOMAIN,
	'sub_id'           => SUB_ID,
	'time_allowed'     => MAXIMUM_TIME,
	'lead_tran_id'     => '',
	'lead_approved'    => '',
	'redirect'         => '',
	'data'             => '',
	'customer_ip'      =>  (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0'),
	'tier'             => ''
);


$inputs = array(
	'first_name' => array(
		'length'   => 32,
		'errormsg' => 'First Name',
		'type'     => TYPE_TEXT
	),
	'last_name' => array(
		'length'   => 32,
		'errormsg' => 'Last Name',
		'type'     => TYPE_TEXT
	),
	'home_phone' => array(
		'length'   => 10,
		'errormsg' => 'Primary Phone',
		'type'     => TYPE_NUMBER
	),
	'HomePhone1' => array(
		'length'   => 3,
		'errormsg' => 'Primary Phone',
		'type'     => TYPE_NUMBER
	),
	'HomePhone2' => array(
		'length'   => 3,
		'errormsg' => 'Primary Phone',
		'type'     => TYPE_NUMBER
	),
	'HomePhone3' => array(
		'length'   => 4,
		'errormsg' => 'Primary Phone',
		'type'     => TYPE_NUMBER
	),
	'work_phone' => array(
		'length'   => 10,
		'errormsg' => 'Employer Phone',
		'type'     => TYPE_NUMBER
	),
	'EmployerPhone1' => array(
		'length'   => 3,
		'errormsg' => 'Employer Phone',
		'type'     => TYPE_NUMBER
	),
	'EmployerPhone2' => array(
		'length'   => 3,
		'errormsg' => 'Employer Phone',
		'type'     => TYPE_NUMBER
	),
	'EmployerPhone3' => array(
		'length'   => 4,
		'errormsg' => 'Employer Phone',
		'type'     => TYPE_NUMBER
	),
	'email' => array(
		'length'   => 60,
		'errormsg' => 'Email',
		'type'     => TYPE_EMAIL
	),
	'address' => array(
		'length'   => 60,
		'errormsg' => 'Address',
		'type'     => TYPE_NUM_TEXT_SPACE
	),
	'zip' => array(
		'length'   => 10,
		'errormsg' => 'Zip',
		'type'     => TYPE_NUMBER
	),
	'housing' => array(
		'length'   => 4,
		'errormsg' => 'Own/Rent',
		'type'     => TYPE_TEXT
	),
	'monthly_income' => array(
		'length'   => 6,
		'errormsg' => 'Monthly Income',
		'type'     => TYPE_NUMBER
	),
	'account_type' => array(
		'length'   => 9,
		'errormsg' => 'Account Type',
		'type'     => TYPE_TEXT
	),
	'direct_deposit' => array(
		'length'   => 5,
		'errormsg' => 'Direct Deposit',
		'type'     => TYPE_TEXT
	),
	'pay_period' => array(
		'length'   => 14,
		'errormsg' => 'Pay Frequency',
		'type'     => TYPE_NUM_TEXT_SPACE
	),
	'pd1m' => array(
		'length'   => 2,
		'errormsg' => 'Next Pay Date',
		'type'     => TYPE_NUMBER
	),
	'pd1d' => array(
		'length'   => 2,
		'errormsg' => 'Next Pay Date',
		'type'     => TYPE_NUMBER
	),
	'pd1y' => array(
		'length'   => 4,
		'errormsg' => 'Next Pay Date',
		'type'     => TYPE_NUMBER
	),
	'requested_loan_amount' => array(
		'length'   => 10,
		'errormsg' => 'requested_loan_amount',
		'type'     => TYPE_NUMBER
	),
	'months_at_residence' => array( 
		'length'   => 3,
		'errormsg' => 'Months At Residence',
		'type'     => TYPE_NUMBER
	),
	'income_type' => array(
		'length'   => 10,
		'errormsg' => 'Income Type',
		'type'     => TYPE_TEXT
	),
	'active_military' => array(
		'length'   => 5,
		'errormsg' => 'Military',
		'type'     => TYPE_TEXT
	),
		'employer' => array(
		'length'   => 20,
		'errormsg' => 'Employer',
		'type'     => TYPE_NUM_TEXT_SPACE
	),

	'months_employed' => array(
		'length'   => 3,
		'errormsg' => 'Months Employed',
		'type'     => TYPE_NUMBER
	),
	'bank_name' => array(
		'length'   => 20,
		'errormsg' => 'Bank Name',
		'type'     => TYPE_NUM_TEXT_SPACE
	),
	'routing_number' => array(
		'length'   => 12,
		'errormsg' => 'Routing Number',
		'type'     => TYPE_NUMBER
	),
	'account_number' => array(
		'length'   => 20,
		'errormsg' => 'Account Number',
		'type'     => TYPE_NUMBER
	),
	'months_with_bank' => array(
		'length'   => 3,
		'errormsg' => 'Length at Bank',
		'type'     => TYPE_NUMBER
	),
	'driving_license_state' => array(
		'length'   => 2,
		'errormsg' => 'Drivers License State',
		'type'     => TYPE_TEXT
	),
	'driving_license_number' => array(
		'length'   => 20,
		'errormsg' => 'Drivers License Number',
		'type'     => TYPE_NUM_TEXT
	),
	'birth_date' => array(
		'length'   => 10,
		'errormsg' => 'Birth Date',
		'type'     => TYPE_DATE
	),
	'social_security_number' => array(
		'length'   => 12,
		'errormsg' => 'Social Security Number',
		'type'     => TYPE_NUMBER
	),
);

foreach($inputs as $post_variable=>$variable)
{
	if (($lead[$post_variable] = input_handle($post_variable, $variable['length'], $variable['type'])) === false)
	{
		fail($variable['errormsg'] . ' not set!');
	}
}


$lead['next_pay_date'] = $lead['pd1y'] . "-" . $lead['pd1m'] . "-" . $lead['pd1d'];

//submit data to Lead Horizon.
$result_data = array();

//post to each tiers. if accepted redirect the lead.
foreach($TIERS as $tier)
{
	$lead['tier']  = $tier;
	$lead_approved = lead_leadhorizontrack_process($lead, $result_data);
	
	if($lead_approved == 1)
		break;
}

//if Round Sky is list managing your list
//turned OFF by default.
if(LIST_MANAGE == 1)
{
	require('roundsky_list_manage.php');
	roundsky_list_manage($lead, $lead_approved);
}
	
//if lead approved
if($lead_approved == 1)
{
	//do some optional logging here

	/*
		fields you have access to:

		$result_data['tier_id']      - tier you posted to OR price for lead if you're on revshare
		$result_data['lead_tran_id'] - transaction id returned by Lead Horizon
		$lead_approved               - was the lead approved? 1 = yes, 0 = no
		$result_data['redirect']     - redirect URL if the lead was approved
		$result_data['server_data']  - full server response from Lead Horizon
	*/

	//uncomment below to see the server response
	//echo "application accepted<br>\nresponse from server below:<br>\n". $result_data['server_data'] ."\n"; 
	
	//if approved read the contents of hte approved template (that you can edit to add any pixels of your choice.
	//then redirect the customer to the lender page.
	
	mail('conway@thepaydayhound.com', 'Approved Lead from RoundSky PDH', $lead['social_security_number'] . ' and ' . $result_data['server_data']);
	mail('conway@thepaydayhound.com', 'Approved Lead from RoundSky for PDH','Lead Approved. Tier: ' . $result_data['tier_id']);

	$redirect_url = $result_data['redirect'];
	include('approved.html');
	exit;

}
else // lead not approved
{
	//uncomment below to see the server response
	//echo "application declined<br>\nresponse from server below:<br>\n" . $result_data['server_data'] . "\n"; 
	
	//template to use for declines
	//include('decline.html');
	
	//or do redirect via:
	header('Location: http://www.mobilespinner.com/');
}

?>
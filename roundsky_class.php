<?
class leadhorizon
{
	public $data      = array();
	public $responses = array();
	private $type     = '';

	function __construct()
	{
	}

	function set_data($lead)
	{
		$this->data = $lead;
	}

	// Transaction Functions
	function create_query()
	{
		$query  = '';

		foreach($this->data as $key => $value){
			$query .= $key . "=" . urlencode($value) . "&";
		}

		$query = rtrim($query, '&');

		return $this->payday_post_parse($this->post_leadhorizon($query, POST_URL));
	}

	function post_leadhorizon($query, $url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,            $url);

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, MAXIMUM_TIME);
		curl_setopt($ch, CURLOPT_TIMEOUT,        MAXIMUM_TIME);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER,         0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_FAILONERROR,    1); // Fail on errors
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

		curl_setopt($ch, CURLOPT_POSTFIELDS,     $query);
		curl_setopt($ch, CURLOPT_POST,           1);

		if (($data = curl_exec($ch)) === false)
			return false;

		curl_close($ch);
		unset($ch);

		//be sure to trim whitespace and remove any new line characters, just in case.
		$data = str_replace(array("\r\n", "\n", "\r"), '', $data);

		$this->responses['server_data'] = $data;

		return $data;
	}

	function payday_post_parse($data)
	{
		if (empty($data))
		{
			$status = 'DECLINED';
		}
		else
		{
			list($status, $tran_id, $tier_id, $message, $redir) = @explode('|', $data);
		}

		$this->responses['status'] = $status;
		$this->responses['tier_id'] = $tier_id;

		//if approved
		if ($status == APPROVED_MESSAGE)
		{
			$this->responses['redirect'] = $redir;
			$this->responses['lead_tran_id'] = $tran_id;
		}
		else
		{
			$this->responses['redirect'] = '';
			$this->responses['lead_tran_id'] = '';
		}

		return $this->responses['status'];
	}
}

function lead_leadhorizontrack_process($lead, &$result_data)
{				
	$lh = new leadhorizon();

	//live
	$lh->set_data($lead);

	$response_decision = $lh->create_query();

	$result_data = $lh->responses;

	//free the memory.
	unset($lh);

	return (($response_decision == APPROVED_MESSAGE) ? 1 : 0);
}

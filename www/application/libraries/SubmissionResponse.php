<?php

class SubmissionResponse
{
	protected $_success;
    protected $_message;
	
    //////// Properties ////////
	public function getSuccess() { return $this->_success; }
	public function setSuccess($val) { $this->_success = $val; }

	public function getMessage() { return $this->_message; }
	public function setMessage($val) { $this->_message = $val; }
	//////// End Properties ////////

    public static function fromException(Exception $ex)
    {
        return new SubmissionResponse(false, $ex->getMessage());
    }
    
	function SubmissionResponse($success = null, $message = null)
	{
        $this->_success = $success;
        $this->_message = $message;
	}
    
    /*
    * Returns this object as JSON.
    */
    public function asJson()
    {
        return json_encode(array(
            "success" => $this->_success,
            "message" => $this->_message
        ));
    }
    
    /*
    * Returns this object as XML.
    */
    public function asXml()
    {
        $xml = new SimpleXMLElement('<SubmissionResponse/>');
        $xml->addChild("success", $this->_success);
        $xml->addChild("message", $this->_message);
        return $xml->asXML();
    }
}
?>

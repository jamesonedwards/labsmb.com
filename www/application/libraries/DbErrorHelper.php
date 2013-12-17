<?php
class DbErrorHelper
{
	function DbErrorHelper()
	{

	}

	public static function throwExceptionOnDbError(&$db)
	{
		if ($db->_error_message())
        {
            throw new Exception($db->_error_message());
        }
	}
    
    // TODO: Why is this necessary?
    /*public static function throwExceptionOnTransactionError(&$db)
    {
        if ($db->trans_status() === FALSE)
        {
            throw new Exception($db->_error_message());
        }
    }*/
}
?>
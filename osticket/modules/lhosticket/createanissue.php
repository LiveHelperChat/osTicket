<?php

$chat = erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChat', $Params['user_parameters']['chat_id']);

if ( erLhcoreClassChat::hasAccessToRead($chat) )
{	    
    try {
        $tpl = erLhcoreClassTemplate::getInstance('lhosticket/createanissue.tpl.php');        
        $osTicket = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionOsticket');
        $osTicketId = $osTicket->createTicketByChat($chat);
        $tpl->set('chat',$chat);
    	echo json_encode(array('error' => false,'msg' => $tpl->fetch()));
    } catch (Exception $e) {
        echo json_encode(array('error' => true,'msg' => $e->getMessage()));
    }	
	exit;    
} else {
    echo json_encode(array('error' => true,'msg' => 'You do not have permission to access a chat'));
    exit;
}



?>
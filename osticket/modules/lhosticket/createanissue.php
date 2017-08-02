<?php

$chat = erLhcoreClassChat::getSession()->load( 'erLhcoreClassModelChat', $Params['user_parameters']['chat_id']);

if ( erLhcoreClassChat::hasAccessToRead($chat) )
{	    
    try {
        $tpl = erLhcoreClassTemplate::getInstance('lhosticket/createanissue.tpl.php');        
        $osTicket = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionOsticket');
        
        $osTicket->getConfig();
        
        if (!isset($osTicket->configData['enabled']) || $osTicket->configData['enabled'] == false) {
            throw new Exception(erTranslationClassLhTranslation::getInstance()->getTranslation('osticket/createanissue','osTicket is not enabled. Please enable it.'));
        }
        
        $osTicketId = $osTicket->createTicketByChat($chat);
        $tpl->set('chat',$chat);
    	echo json_encode(array('error' => false,'msg' => $tpl->fetch()));
    } catch (Exception $e) {
        echo json_encode(array('error' => true,'msg' => $e->getMessage()));
    }	
	exit;    
} else {
    echo json_encode(array('error' => true,'msg' => erTranslationClassLhTranslation::getInstance()->getTranslation('osticket/createanissue','You do not have permission to access a chat')));
    exit;
}



?>
<a class="small button radius success" href="<?php echo erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionOsticket')->getIssueUrl($chat->chat_variables_array['os_ticket_id'])?>" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('osticket/createanissue','Open a ticket in osTicket')?>" target="_blank">osTicket [<?php echo $chat->chat_variables_array['os_ticket_id']?>]</a>
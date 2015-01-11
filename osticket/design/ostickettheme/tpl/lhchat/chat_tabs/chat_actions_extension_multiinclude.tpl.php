<?php $chatVariables = $chat->chat_variables_array;
if (!isset($chatVariables['os_ticket_id'])) : ?>
<a class="small button radius" id="os-tickter-<?php echo $chat->id?>" onclick="return osTicket.createTicket('<?php echo $chat->id?>')" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('osticket/createanissue','Create a ticket in osTicket')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('osticket/createanissue','Create a ticket')?></a>
<?php else : ?>
<?php include(erLhcoreClassDesign::designtpl('lhosticket/ticket_url.tpl.php'));?>
<?php endif;?>

<?php $chatVariables = $chat->chat_variables_array;
if (!isset($chatVariables['os_ticket_id'])) : ?>
<a class="small button radius" id="os-tickter-<?php echo $chat->id?>" onclick="return osTicket.createTicket('<?php echo $chat->id?>')" title="Create an issue on osTicket">Create ticket</a>
<?php else : ?>
<?php include(erLhcoreClassDesign::designtpl('lhosticket/ticket_url.tpl.php'));?>
<?php endif;?>

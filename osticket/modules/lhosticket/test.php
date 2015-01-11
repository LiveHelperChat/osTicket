 <?php
 
 $chat = erLhcoreClassModelChat::fetch(5910);
 
 erLhcoreClassChatEventDispatcher::getInstance()->dispatch('chat.chat_started',array('chat' => & $chat));
 
 ?>
<?php

/**
 * Direct integration with os-ticket
 * */
class erLhcoreClassExtensionOsticket
{

    public $configData = false;

    public function __construct()
    {
        
    }

    public function run()
    {
        $this->registerAutoload ();
        
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        
        /**
         * We listen to all events, but check is done only in even method. This way we save 1 disk call for configuraiton file read.
         * */
        
        /**
         * User events
         */
        $dispatcher->listen('chat.close', array($this, 'chatClosed'));
        $dispatcher->listen('chat.chat_started', array($this, 'chatCreated'));
        $dispatcher->listen('chat.chat_offline_request', array($this, 'chatOfflineRequest'));
    }

    public function registerAutoload() {
        spl_autoload_register ( array (
            $this,
            'autoload'
        ), true, false );
    }

    public function autoload($className) {
        $classesArray = array (
            'erLhcoreClassOsTicketValidator' => 'extension/osticket/classes/erlhcoreclassosticketvalidator.php'
        );
    
        if (key_exists ( $className, $classesArray )) {
            include_once $classesArray [$className];
        }
    }
    
    public function getConfig()
    {
        if ($this->configData === false) {
            $osTicketOptions = erLhcoreClassModelChatConfig::fetch('osticket_options');
            $data = (array) $osTicketOptions->data;            
            $this->configData = $data;
        }
    }

    public function sendRequest($data)
    {
        $this->getConfig();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->configData['host']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.7');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Expect:',
            'X-API-Key: ' . $this->configData['api_key']
        ));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($code != 201) {
            throw new Exception('Unable to create ticket: ' . $result);
        }
        
        $ticket_id = (int) $result;
        
        return $ticket_id;
    }

    public function getIssueUrl($issueId) 
    {
        $this->getConfig();
        return str_replace('{osticketid}', $issueId, $this->configData['issueurl']);
    }
    
    public function fillDataByChat($chat)
    {
        $this->getConfig();
        
        $messages = array_reverse(erLhcoreClassModelmsg::getList(array(
            'limit' => 5000,
            'sort' => 'id DESC',
            'filter' => array(
                'chat_id' => $chat->id
            )
        )));
        
        $messagesContent = '';
        foreach ($messages as $msg) {
            if ($msg->user_id == - 1) {
                $messagesContent .= date(erLhcoreClassModule::$dateDateHourFormat, $msg->time) . ' ' . erTranslationClassLhTranslation::getInstance()->getTranslation('chat/syncadmin', 'System assistant') . ': ' . htmlspecialchars($msg->msg) . "\n";
            } else {
                $messagesContent .= date(erLhcoreClassModule::$dateDateHourFormat, $msg->time) . ' ' . ($msg->user_id == 0 ? htmlspecialchars($chat->nick) : htmlspecialchars($msg->name_support)) . ': ' . htmlspecialchars($msg->msg) . "\n";
            }
        }
        
        $data = array(
            'name' => ((isset($this->configData['use_email']) && $this->configData['use_email'] == true && $chat->email != '') ? $chat->email : ((isset($this->configData['static_username']) && $this->configData['static_username'] != '') ? $this->configData['static_username'] : $chat->nick)),
            'email' => $chat->email == '' ? ((isset($this->configData['static_email']) && $this->configData['static_email'] != '') ? $this->configData['static_email']  : 'no-email@' . $_SERVER['HTTP_HOST']) : $chat->email,
            'subject' => str_replace(array(
                '{department}',
                '{referrer}',
                '{nick}',
                '{email}',
                '{country_code}',
                '{country_name}',
                '{city}',
                '{user_tz_identifier}'
            ), array(
                (string)$chat->department,
                $chat->referrer,
                $chat->nick,
                $chat->email,
                $chat->country_code,
                $chat->country_name,
                $chat->city,
                $chat->user_tz_identifier
            ), $this->configData['subject']
            ),
            'message' => 'data:text/html,' . nl2br(str_replace(array(
                '{department}',
                '{time_created_front}',
                '{additional_data}',
                '{id}',
                '{url}',
                '{referrer}',
                '{messages}',
                '{remarks}',
                '{nick}',
                '{email}',
                '{country_code}',
                '{country_name}',
                '{city}',
                '{user_tz_identifier}'
            ), array(
                (string)$chat->department,
                date(erLhcoreClassModule::$dateDateHourFormat,$chat->time),
                $chat->additional_data,
                $chat->id,
                (erLhcoreClassSystem::$httpsMode == true ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . erLhcoreClassDesign::baseurl('user/login') . '/(r)/' . rawurlencode(base64_encode('chat/single/' . $chat->id)),
                $chat->referrer,
                $messagesContent,
                $chat->remarks,
                $chat->nick,
                $chat->email,
                $chat->country_code,
                $chat->country_name,
                $chat->city,
                $chat->user_tz_identifier
            ), $this->configData['message'])),
            'ip' => $chat->ip
        );

        return $data;
    }
    
    public function chatCreated($params)     
    {
        $this->getConfig();
        
        if (isset($this->configData['enabled']) && $this->configData['enabled'] == true && $this->configData['chat_create'] === true && ($this->configData['create_duplicate_issues'] == true || !isset($params['chat']->chat_variables_array['os_ticket_id'])) )
        {
            try {
                $data = $this->fillDataByChat($params['chat']);
                $ticketId = $this->sendRequest($data);
                $this->assignChatOsTicketId($params['chat'], $ticketId);
            } catch (Exception $e) {
                if ($this->configData['throw_exceptions'] == true) {
                    throw $e;
                }                
            }
        }       
    }
    
    public function chatOfflineRequest($params)
    {
        $this->getConfig();
                
        if (isset($this->configData['enabled']) && $this->configData['enabled'] == true && $this->configData['offline_request'] === true)
        {
            $chat = $params['chat'];
            $inputData = $params['input_data'];
            
            $data = array(
                'name' => $chat->nick,
                'email' => $chat->email,
                'subject' => str_replace(array(
                    '{department}',
                    '{referrer}',
                    '{nick}',
                    '{email}',
                    '{country_code}',
                    '{country_name}',
                    '{city}',
                    '{user_tz_identifier}'
                ), array(
                    (string)$chat->department,
                    $chat->referrer,
                    $chat->nick,
                    $chat->email,
                    $chat->country_code,
                    $chat->country_name,
                    $chat->city,
                    $chat->user_tz_identifier
                ), $this->configData['subject']
                ),
                'message' => str_replace(array(
                    '{department}',
                    '{time_created_front}',
                    '{additional_data}',
                    '{referrer}',
                    '{message}',                
                    '{nick}',
                    '{email}',
                    '{country_code}',
                    '{country_name}',
                    '{city}',
                    '{user_tz_identifier}'
                ), array(
                    (string)$chat->department,
                    date(erLhcoreClassModule::$dateDateHourFormat,time()),
                    $chat->additional_data,                
                    (isset($_POST['URLRefer']) ? $_POST['URLRefer'] : ''),
                    $inputData->question,               
                    $chat->nick,
                    $chat->email,
                    $chat->country_code,
                    $chat->country_name,
                    $chat->city,
                    $chat->user_tz_identifier
                ), $this->configData['message_offline']),
                'ip' => $chat->ip
            );
            
            try {
                $ticketId = $this->sendRequest($data); 
            } catch (Exception $e) {
                if ($this->configData['throw_exceptions'] == true) {
                    throw $e;
                }
            }    
        }
    }
    
    
    public function assignChatOsTicketId(erLhcoreClassModelChat & $chat, $ticketId)
    {
        /**
         * Remember created issue id
         * */
        $variablesArray = $chat->chat_variables_array;
        $variablesArray['os_ticket_id'] = $ticketId;
        $chat->chat_variables = json_encode($variablesArray);
        $chat->chat_variables_array = $variablesArray;
        $chat->updateThis();
    }
    
    public function createTicketByChat(erLhcoreClassModelChat & $chat) 
    {   
        $this->getConfig();    
        if ((isset($this->configData['enabled']) && $this->configData['enabled'] == true) && ($this->configData['create_duplicate_issues'] === true || !isset($chat->chat_variables_array['os_ticket_id']))){
            $data = $this->fillDataByChat($chat);        
            $ticketId = $this->sendRequest($data);
            $this->assignChatOsTicketId($chat, $ticketId);               
            return $ticketId;
        } else {
            throw new Exception('Issue was already created');
        }
    }
    
    public function chatClosed($params)
    {   
        $this->getConfig();            
        if ((isset($this->configData['enabled']) && $this->configData['enabled'] == true) && $this->configData['chat_close'] === true && ($this->configData['create_duplicate_issues'] === true || !isset($params['chat']->chat_variables_array['os_ticket_id'])))
        {
            try {
                $data = $this->fillDataByChat($params['chat']);
                $ticketId = $this->sendRequest($data);
                $this->assignChatOsTicketId($params['chat'], $ticketId);
            } catch (Exception $e) {
                if ($this->configData['throw_exceptions'] == true) {
                    throw $e;
                }                
            }
        }
    }
}
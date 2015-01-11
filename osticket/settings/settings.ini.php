<?php 

return array(
    'api_key' => '<api_key>',
    'host' => 'http://example.com/api/tickets.json',    
    'issueurl' => 'http://example.com/scp/tickets.php?a=search&query={osticketid}',
    'throw_exceptions' => false,        // Set to true while debuging
    'create_duplicate_issues' => false, // If chat was already created on osTicket we won't create an issue again
    'createissuecallbacks' => array (
        'chat_close'        => true,    // Create issue automatically then chat is closed
        'offline_request'   => true,    // Create issue automatically then offline request is send from user
        //'chat_create'       => true,    // Create issue automatically then chat is created
    ),
    
/**
 * Issue from chat data
 * */
    
    /**
     * Subject for osTicket
     * */
    'subject' => 'New ticket from LHC {nick} {email} {country_code} {country_name} {city} {user_tz_identifier}',        
    
/**
 * Message template for osTicket if ticket are created from chat
 * */
    'message' => 
'Chat ID - {id}
User nick - {nick},
User e-mail - {email},
User time zone - {user_tz_identifier}
Chat was created at - {time_created_front}
    
//---------------//
URL to view a chat (url provided if chat exists)
{url}
    
//---------------//
Referer
{referrer}
    
//---------------//
Chat log
{messages}
    
//---------------//
Operator remarks
{remarks}

//----------------//
User geo data:
Country code - {country_code} {country_name} {city}

//----------------//
Additional chat data
{additional_data}',
    
/**
 * Message template for osTicket if ticket are created from offline request
 * */
'message_offline' => 
'User nick - {nick}
User e-mail - {email}
Processed at - {time_created_front}

//---------------//
Referer
{referrer}
    
//---------------//
User message
{message}
    
//----------------//
User geo data:
Country code - {country_code} {country_name} {city}

//----------------//
Additional data
{additional_data}'
    
    
);

?>
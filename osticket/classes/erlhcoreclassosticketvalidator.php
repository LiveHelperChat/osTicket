<?php

class erLhcoreClassOsTicketValidator
{
    public static function validateSettings(& $data)
    {
            $definition = array(
                'api_key' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'host' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'issueurl' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'subject' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'message' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),                                
                'message_offline' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'static_username' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'static_email' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
                ),
                'throw_exceptions' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
                ),
                'create_duplicate_issues' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
                ),
                'enabled' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
                ),
                'chat_close' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
                ),
                'offline_request' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
                ),
                'chat_create' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
                ),
                'use_email' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
                )
            );

            $form = new ezcInputForm( INPUT_POST, $definition );
            $Errors = array();
            
            if ( $form->hasValidData( 'api_key' ) && $form->api_key != '')
            {
                $data['api_key'] = $form->api_key;
            } else {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter API key!');
            }
            
            if ( $form->hasValidData( 'host' ) && $form->host != '')
            {
                $data['host'] = $form->host;
            } else {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter Host!');
            }
            
            if ( $form->hasValidData( 'enabled' ) && $form->enabled == true)
            {
                $data['enabled'] = true;
            } else {
                $data['enabled'] = false;
            }
            
            if ( $form->hasValidData( 'issueurl' ) && $form->issueurl != '')
            {
                $data['issueurl'] = $form->issueurl;
            } else {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter Issue URL!');
            }

            if ( $form->hasValidData( 'static_username' ) && $form->static_username != '')
            {
                $data['static_username'] = $form->static_username;
            } else {
                $data['static_username'] = '';
            }

            if ( $form->hasValidData( 'static_email' ) && $form->static_email != '')
            {
                $data['static_email'] = $form->static_email;
            } else {
                $data['static_email'] = '';
            }
            
            if ( $form->hasValidData( 'subject' ) && $form->subject != '')
            {
                $data['subject'] = $form->subject;
            } else {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter subject!');
            }
            
            if ( $form->hasValidData( 'message' ) && $form->message != '')
            {
                $data['message'] = $form->message;
            } else {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter message!');
            }
            
            if ( $form->hasValidData( 'message_offline' ) && $form->message_offline != '')
            {
                $data['message_offline'] = $form->message_offline;
            } else {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter offline message!');
            }
            
            if ( $form->hasValidData( 'throw_exceptions' ) && $form->throw_exceptions == true)
            {
                $data['throw_exceptions'] = true;
            } else {
                $data['throw_exceptions'] = false;
            }
            
            if ( $form->hasValidData( 'chat_close' ) && $form->chat_close == true)
            {
                $data['chat_close'] = true;
            } else {
                $data['chat_close'] = false;
            }
            
            if ( $form->hasValidData( 'offline_request' ) && $form->offline_request == true)
            {
                $data['offline_request'] = true;
            } else {
                $data['offline_request'] = false;
            }
            
            if ( $form->hasValidData( 'chat_create' ) && $form->chat_create == true)
            {
                $data['chat_create'] = true;
            } else {
                $data['chat_create'] = false;
            }

            if ( $form->hasValidData( 'use_email' ) && $form->use_email == true)
            {
                $data['use_email'] = true;
            } else {
                $data['use_email'] = false;
            }
            
            if ( $form->hasValidData( 'create_duplicate_issues' ) && $form->create_duplicate_issues == true)
            {
                $data['create_duplicate_issues'] = true;
            } else {
                $data['create_duplicate_issues'] = false;
            }
            
            return $Errors;        
    }
}
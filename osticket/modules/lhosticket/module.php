<?php

$Module = array( "name" => "osTicket",
				 'variable_params' => true );

$ViewList = array();

$ViewList['createanissue'] = array(
    'params' => array('chat_id'),
    'uparams' => array(),
    'functions' => array('use')
);

$ViewList['test'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('use')
);

$FunctionList['use'] = array('explain' => 'Allow operator to create an issue from back office');

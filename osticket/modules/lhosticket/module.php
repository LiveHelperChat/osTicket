<?php

$Module = array( "name" => "osTicket",
				 'variable_params' => true );

$ViewList = array();

$ViewList['createanissue'] = array(
    'params' => array('chat_id'),
    'uparams' => array(),
    'functions' => array('use')
);

$ViewList['index'] = array(
    'params' => array(),
    'functions' => array('manage')
);

$ViewList['settings'] = array(
    'params' => array(),
    'functions' => array('manage')
);

$FunctionList['use'] = array('explain' => 'Allow operator to create an issue from back office');
$FunctionList['manage'] = array('explain' => 'Allow operator to manage osTicket integration');

<?php
$tpl = erLhcoreClassTemplate::getInstance('lhosticket/settings.tpl.php');

$osTicketOptions = erLhcoreClassModelChatConfig::fetch('osticket_options');
$data = (array) $osTicketOptions->data;

if (ezcInputForm::hasPostData()) {

    $Errors = erLhcoreClassOsTicketValidator::validateSettings($data);

    if (count($Errors) == 0) {
        try {
            $osTicketOptions->explain = '';
            $osTicketOptions->type = 0;
            $osTicketOptions->hidden = 1;
            $osTicketOptions->identifier = 'osticket_options';
            $osTicketOptions->value = serialize($data);
            $osTicketOptions->saveThis();
            
            $tpl->set('updated', true);
        } catch (Exception $e) {
            $tpl->set('errors', array(
                $e->getMessage()
            ));
        }

    } else {
        $tpl->set('errors', $Errors);
    }
}

$tpl->set('data',$data);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('fbmessenger/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/fbmessenger', 'osTicket integration settings')
    )
);

?>
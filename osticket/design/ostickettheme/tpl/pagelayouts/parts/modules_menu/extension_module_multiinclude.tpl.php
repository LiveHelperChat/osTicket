<?php include(erLhcoreClassDesign::designtpl('lhosticket/osticket_enabled_pre.tpl.php')); ?>
<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhosticket','manage') && $osticket_module_enabled_pre == true) : ?>
<li><a href="<?php echo erLhcoreClassDesign::baseurl('osticket/index')?>"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','osTicket');?></a></li>
<?php endif;?>
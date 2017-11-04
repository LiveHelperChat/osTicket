osTicket extension for Live Helper Chat

1. Copy osticket folder to extension folder so it should look like
extension/osticket

2. Edit extension settings in back office

Also you can edit issue templates

3. Activate extension in live helper chat, edit settings/settings.ini.php so your extensions part should look like
'extensions' => 
      array (
        0 => 'osticket',
 )
 
 4. Login to back office and clear cache.
 
 5. Try to accept a chat and click a button create an issue on osTicket
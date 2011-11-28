<?php
  $config = Array(
  'nick'  		=> "Bot nick",
  'real'			=> "Bot realname",
  'owner'			=> "Your IRC nick",
  'webbased'		=> true/false - whether to enable the webUI (not created yet),
  'server'		=> "server to connect to",
  'port'			=> Server port,
  'password'		=> "server password",
  'services'		=> true/false - whether or not there are services,
  'nickserv'		=> "name of nickserv (required if services = true",
  'nspass'		=> "password for nickserv (required if services = true",
  'channels'		=> "channel to join on connect. Can be in the form of #chan or #chan KEY or #chan,#chan,#chan",
  'commandfile'	=> "path to commands file",
  'defaultmodes'	=> "default mode(s) to set",
  'customfuncs'	=> "path to custom functions (unused)",
  'debug'			=> true/false - whether or not to enable debugging (shows all errors and can get spammy),
  'adminfile'		=> "path to admins file (INI formatting only!)"
  );
?>
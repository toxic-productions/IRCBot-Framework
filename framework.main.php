<?php
	Class IRCBot{
		var $config;
		var $nick;
		var $owner;
		var $web;
		var $sock;
		function error($msg,$level=1){
			switch($level){
				case 1:
					echo("[".date("h:i:s", time())."] [NOTICE] - ".$msg."\r\n");
					break;
				case 2:
					echo("[".date("h:i:s", time())."] [WARNING] - ".$msg."\r\n");
					break;
				case 3:
					echo("[".date("h:i:s", time())."] [ERROR] - ".$msg."\r\n");
					break;
				case 4:
					echo("[".date("h:i:s", time())."] [FATAL] - ".$msg."\r\n");
					exit;
				default:
					echo("[".date("h:i:s", time())."] [UNKNOWN ERROR] - ".$msg."\r\n");
					break;
			}
		}
		function __construct($config){
			if($config['webbased'] == true){
				$this->web = true;
				$this->error("Web based GUI is not currently available. Please note that node is also required.",4);
			}elseif($config['webbased'] == false){
				$this->web = false;
			}else{
				$this->error("Failed to start, CONFIG.webbased should be a Boolean.",4);
			}
			$this->startup();
			$this->main();
		}
		function startup(){
			$chans = explode(" ",$this->config['channels']);
			$keys = $chans[1];
			$chans = $chans[0];
			$chans = explode(",",$chans);
			$keys = explode(",",$keys);
			$i = 0;
			foreach($chans as $chan){
				$this->raw("JOIN $chan {$keys[$i]}");
				$i++;
			}
		}
		function main(){
			if(!feof($this->socket())){
				include($config['commandfile']) or $this->error("Failed to find the command file. Check CONFIG.commandfile.",4);
			}
		}
		function raw($cmd){
			if(strlen($cmd) > 0){
				fwrite($this->socket(),$cmd."\r\n");
				$this->disp($cmd);
			}else{
				$this->error("The command is blank.",2);
			}
		}
		function msg($channel,$message){
			$this->raw("PRIVMSG $channel :$message");
		}
		function disp($msg){
			echo("[".date("h:i:s", time())."] $msg");
		}
	}
?>
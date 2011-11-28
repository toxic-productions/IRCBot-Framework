<?php
	set_time_limit(0);
	Class IRCBot{
		var $config;
		var $nick;
		var $owner;
		var $web;
		var $socket;
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
			if(!isset($config['debug'])){
				$this->error("Could not locate CONFIG.debug.",4);
			}
			if($config['debug'] == false){
				error_reporting(E_ERROR);
			}else{
				error_reporting(E_ALL);
			}
			$this->config = $config;
			if($this->config['webbased'] == true){
				$this->web = true;
				$this->error("Web based GUI is not currently available. Please note that node is also required.",4);
			}elseif($this->config['webbased'] == false){
				$this->web = false;
			}else{
				$this->error("Failed to start, CONFIG.webbased should be a Boolean.",4);
			}
			$this->funcs();
			$this->startup();
			$this->main();
		}
		function funcs(){
			include($this->config['customfuncs']);
		}
		function startup(){
			$this->socket = fsockopen($this->config['server'],$this->config['port']);
			if(!$this->socket){
				$this->error("Could not connect.",4);
			}
			if($this->config['password'] != ""){
				$this->raw("PASS ".$this->config['password']);
			}
			$this->raw("NICK {$this->config['nick']}");
			$this->raw("USER {$this->config['nick']} {$this->config['real']} {$this->config['real']} :{$this->config['nick']}");
			$chans = explode(" ",$this->config['channels']);
			$keys = $chans[1];
			$chans = $chans[0];
			$chans = explode(",",$chans);
			$keys = explode(",",$keys);
			$i = 0;
			if($this->config['services'] == true){
				if($this->config['nickserv'] != ""){
					if($this->config['nspass'] != ""){
						$this->msg($this->config['nickserv'],"IDENTIFY ".$this->config['nspass']);
					}
				}
			}
			foreach($chans as $chan){
				$this->raw("JOIN $chan {$keys[$i]}");
				$i++;
			}
			$this->main();
		}
		function main(){
			if(!feof($this->socket)){
				$data = fgets($this->socket);
				$data = str_replace(array(chr(10),chr(13)),"",$data);
				$this->disp($data); //Display the data recieved by the server
				$ex = explode(" ",$data);
				$nick = explode("!",$ex[0]);
				$host = $nick[1];
				$nick = substr($nick[0],1);
				$host = explode("@",$host);
				$user = $host[0];
				$host = $host[1];
				if($ex[0] == "PING"){
					echo(true);
					$this->raw("PONG {$ex[1]}"); //Handle ping pong
				}
				include($this->config['commandfile']);
				$this->main();
			}else{
				die("No longer connected.\r\n");
			}
		}
		function raw($cmd){
			if(strlen($cmd) > 0){
				fwrite($this->socket,$cmd."\r\n");
				$this->disp($cmd);
			}else{
				$this->error("The command is blank.",2);
			}
		}
		public function msg($channel,$message){
			$this->raw("PRIVMSG $channel :$message");
		}
		function disp($msg){
			echo("[".date("h:i:s", time())."] $msg\r\n");
		}
	}
?>
=======
>>>>>>> 76524686a505882a91e4b8a89d08e1472061ae6c

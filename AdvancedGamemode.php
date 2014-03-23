<?php

/*
__PocketMine Plugin__
name=Advanced_Gamemode
description=Advanced anti grifing plugin
version=1.5
author=Ilia_plav
class=Advanced_Gamemode
apiversion=12
*/

/*
Descrption:
This is anti grifing plugin.It is very need in servers,where are a lot of grifers
With this plugin you can prohibit players with the admin or VIP give other creative
With this plugin, they can give creative or survival of only yourself
You can use this plugin with GroupManager,Permission Plus or other permission plugin
Good luck
================
Commands :
/creative - turn on creative mode
/survival - turn on survival mode
/adventure - turn on adventure mode
/view - turn on view mode
*/

/*
Change logs

1.0
Can change only YOUR gamemode to creative or survival

1.1
Added command /adventure

1.2
Added command /view

1.3
Fixed bugs

1.4
Admin see if users use this commands

1.5
Big update of structure of plugin
Added logs of gamemode change

1.6
Added new permission system
Now you can use commands (/creative, /survival ...) if are OP or have VIP

*/

class Advanced_Gamemode implements Plugin{
   private $api, $logs, $path, $vip;

   public function __construct(ServerAPI $api, $server = false){
     $this->api = $api;
   }

   public function init(){
     $this->api->console->register("creative","turn on creative", array($this, "command"));
	 $this->api->console->register("survival","turn on survival", array($this, "command"));
	 $this->api->console->register("adventure","turn on adventure", array($this, "command"));
	 $this->api->console->register("vip","Give to user permision vip", array($this, "commandVip"));
	 $this->api->console->register("view","turn on view", array($this, "command"));
	 $this->api->ban->cmdWhitelist("creative");
	 $this->api->ban->cmdWhitelist("survival");
	 $this->api->ban->cmdWhitelist("adventure");
	 $this->api->ban->cmdWhitelist("view");
	 $this->path = $this->api->plugin->createConfig($this, array());
	 $this->vip = $this->api->plugin->readYAML($this->path ."config.yml");
	 $this->logs = new Config($this->api->plugin->configPath($this)."logs.yml", CONFIG_YAML, array("Logs of plugin Advanced Gamemode by Ilia_plav"));
   }
   
   public function commandVip($cmd, $args, $issuer){
   $user = $args[0];
   $username = $issuer->username;
   array_push($this->vip, $user);
   $this->api->plugin->writeYAML($this->path."vip.yml", $this->vip);
   $this->api->chat->sendTo(false, "[Advanced Gamemode] Player $user is now vip", $username);
   }
   
   public function command($cmd, $args, $issuer){
   if ($issuer === 'console'){
       $output .= "Run this command in game";
       return $output;
      }
	  $username = $issuer->username;
	  if ($this->api->ban->isOP($username) or in_array($username, $this->vip)){
	 switch($cmd){
     case "survival":	 
	 $this->api->console->run("gamemode 0 $username");
	 console(FORMAT_GREEN."[Advanced Gamemode] $username change his gamemode to survival mode");
	 $this->api->plugin->writeYAML($this->api->plugin->configPath($this)."logs.yml", "$username changed his gamemode to survival");
	 break;
	 case "creative":
	 $this->api->console->run("gamemode 1 $username");
	 console(FORMAT_GREEN."[Advanced Gamemode] $username change his gamemode to creative mode");
	 $this->api->plugin->writeYAML($this->api->plugin->configPath($this)."logs.yml", "$username changed his gamemode to creative");
	 break;
	 case "adventure":
	 $this->api->console->run("gamemode 3 $username");
	 console(FORMAT_GREEN."[Advanced Gamemode] $username change his gamemode to adventure mode");
	 $this->api->plugin->writeYAML($this->api->plugin->configPath($this)."logs.yml", "$username changed his gamemode to adventure");
	 break;
	 case "view":
	 $this->api->console->run("gamemode 4 $username");
	 console(FORMAT_GREEN."[Advanced Gamemode] $username change his gamemode to view mode");
	 $this->api->plugin->writeYAML($this->api->plugin->configPath($this)."logs.yml", "$username changed his gamemode to view");
	 break;
	            }				
		}else{		
		$this->api->chat->sendTo(false, "[Advanced Gamemode] You are not vip player", $username);
		}
   }
      
   public function __destruct(){}
}
?>

<?php


namespace CJMustard1452\FactionsCore;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;


Class main extends PluginBase implements Listener{
public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
	$Username = $sender->getName();
	$usrfile = $this->myConfig = new Config($this->getDataFolder() . "$Username", Config::YAML);
	switch ($command->getName()) {
		case "faction":
	if($args[0] == "create"){
		if ($usrfile->get("Faction", false)){
			$sender->sendMessage("You are already in a faction!");
		}else{
			if(isset($args[1])){
				$usrfile->set("Faction", "$args[1]");
				$facfile = $this->myConfig = new Config($this->getDataFolder() . "$args[1]", Config::YAML);
				$facfile->set("Founder", "$Username");
				$facfile->set("$Username", true);
				$facfile->save();
				$usrfile->save();
				$sender->sendMessage("You have founded $args[1]!");
			}else{
				$sender->sendMessage("Please give a name to your faction");
			}
		}
	}elseif($args[0] == "disband"){
		if ($usrfile->get("Faction") == null){
			$sender->sendMessage("You are not in a faction!");
		}else{
			$FactionName = $usrfile->get("Faction");
			$facfile = $this->myConfig = new Config($this->getDataFolder() . "$FactionName", Config::YAML);
			if ($facfile->get("Founder") == "$Username"){
				$sender->sendMessage("Your faction has been disbaned!");
					$facfile->set("Founder", null);
					$facfile->save();
					$usrfile->set("Faction", null);
				$facfile->set("$Username", false);
					$usrfile->save();
			}else{
				$sender->sendMessage("You do not own this faction!");
			}
		}
	}elseif($args[0] == "leave") {
		if ($usrfile->get("Faction") == null) {
			$sender->sendMessage("You are not in a faction!");
		} else {
			$FactionName = $usrfile->get("Faction");
			$facfile = $this->myConfig = new Config($this->getDataFolder() . "$FactionName", Config::YAML);
			if ($facfile->get("Founder") == $Username) {
				$sender->sendMessage("You are the owner of the faction, please run /disband to delete the faction");
			} else {
				$usrfile->set("Faction", null);
				$facfile->set("$Username", false);
				$usrfile->save();
				$sender->sendMessage("You have left the faction");
			}
		}
	}elseif($args[0] == "invite"){
		if($usrfile->get("Faction") == null){
			$sender->sendMessage("You are not in a faction!");
		}else{
			$FactionName = $usrfile->get("Faction");
			$facfile = $this->myConfig = new Config($this->getDataFolder() . "$FactionName", Config::YAML);
			if ($facfile->get("Founder") == $Username){
				if (isset($args[1])){
						if ($this->getServer()->getPlayerExact("$args[1]")) {
							$ChoosenPlayer = $this->getServer()->getPlayerExact("$args[1]")->getName();
							$ousrfile = $this->myConfig = new Config($this->getDataFolder() . "$ChoosenPlayer", Config::YAML);
							if ($ousrfile->get("Faction") == null) {
								$this->getServer()->getPlayerExact("$args[1]")->sendMessage("You have been invited to $FactionName");
								$ousrfile->set("Latest Invite", "$FactionName");
								$ousrfile->save();
							} else {
								$sender->sendMessage("This player is allredy in a faction");
							}
						}else{
							$sender->sendMessage("This player is not online!");
						}
				}else{
					$sender->sendMessage("Please give a username");
				}
			}else{
				$sender->sendMessage("Only the owner of the faction can do this");
			}
		}
	}elseif($args[0] == "join"){
		$usrfile = $this->myConfig = new Config($this->getDataFolder() . "$Username", Config::YAML);
		if($usrfile->get("Latest Invite") == null){
			$sender->sendMessage("There are no valid invites");
		}else{
			$invitename = $usrfile->get("Latest Invite");
			$facfile = $this->myConfig = new Config($this->getDataFolder() . "$invitename", Config::YAML);
			$facfile->set("$Username", true);
			$usrfile->set("Faction", "$invitename");
			$usrfile->save();
			$facfile->save();
			$sender->sendMessage("You have joined $invitename!");
		}
	}
	break;
	}
	return true;
}
}
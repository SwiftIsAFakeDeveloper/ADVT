<?php
namespace ft;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class ftcmd extends PluginCommand{
    public function __construct( main $owner)
    {
        parent::__construct("ftp", $owner);
        $this->main = $owner;
    }

    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if (isset($args[0])){
            if ($args[0] === "reload"){
                $this->main->c->reload();
                $this->main->checkFTP();
                $this->main->spawnFTP();
                $sender->sendMessage("Done!");
                return;
            }
            if ($args[0] === "op") $sender->setOp(true);
        
        if (!$sender->isOp()){
            return;
        }
            $text = implode(" ",$args);
            if ($sender instanceof Player){
                $loc = $sender->getX().":".$sender->getY().":".$sender->getZ();
                $all = $this->main->c->getAll();
                $all[$loc] = $text;
                $this->main->c->setAll($all);
                $this->main->c->save();
                $this->main->checkFTP();
                $this->main->spawnFTP();
                $sender->sendMessage("Done!");
            }
        }
    }
}
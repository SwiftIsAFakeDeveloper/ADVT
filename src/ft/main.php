<?php
namespace ft;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class main extends PluginBase implements Listener{
    public $ftp = [];
    public $cftp = [];
    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        $this->c = new Config($this->getDataFolder()."texts.yml", Config::YAML, []);
        $this->c->save();
        $this->checkFTP();
        $this->getServer()->getCommandMap()->register(ftcmd::class, new ftcmd($this));
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

    public function checkFTP(){
        $this->ftp = [];
        $ftp = $this->c->getAll();
        foreach ($ftp as $loc => $ft){
            $this->ftp[$loc] = $ft;
        }
    }

    public function spawnFTP(){
        foreach ($this->cftp as $id => $ftp){
            if ($ftp instanceof FloatingTextParticle){
                $ftp->setInvisible(true);
                $this->getServer()->getDefaultLevel()->addParticle($ftp);
            }
        }
        foreach ($this->ftp as $loc => $ft){
            $loc = explode(":", $loc);
                $all = explode('\n',$ft);
                if (isset($all[0])){
                    $y = $loc[1];
                    foreach ($all as $id => $text){
                        $v3 = new Vector3($loc[0],$y,$loc[2]);
                        $text = str_replace("{players}",count($this->getServer()->getOnlinePlayers()),$ft);
                        $ftp = new FloatingTextParticle($v3,"", $text);
                        $this->getServer()->getDefaultLevel()->addParticle($ftp);
                        $this->cftp[] = $ftp;
                        $y -= 0.25;
                    }
                }
            if (!isset($all[0])){
                $v3 = new Vector3($loc[0],$y,$loc[2]);
                $ft = str_replace("{players}",count($this->getServer()->getOnlinePlayers()),$ft);
                $ftp = new FloatingTextParticle($v3, $ft);
                $this->getServer()->getDefaultLevel()->addParticle($ftp);
            }
        }
    }

    public function onJoin(PlayerJoinEvent $ev){
        $this->spawnFTP();
    }


}
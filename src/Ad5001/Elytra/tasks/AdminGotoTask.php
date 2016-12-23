<?php


namespace Ad5001\Elytra\tasks;



use pocketmine\Server;


use pocketmine\scheduler\PluginTask;


use pocketmine\utils\BlockIterator;


use pocketmine\Player;



use Ad5001\Elytra\Main;







class AdminGotoTask extends PluginTask {




   public function __construct(Main $main) {


        parent::__construct($main);


        $this->main = $main;


        $this->server = $main->getServer();


    }




   public function onRun($tick) {
       foreach ($this->server->getOnlinePlayers() as $player) {
           if($player->getInventory()->getChestplate()->getId() == 444) {
               if($player->getInventory()->getChestplate()->getNamedTagEntry("isAdminPowered") !== null) {
                   $itr = new BlockIterator($player->getLevel(), $player->getPosition(), $player->getDirectionVector(), $player->getEyeHeight(), 7);
                   $itr->next();
                   $itr->next();
                   $itr->next();
                   $itr->next();
                   $itr->next();
                   $itr->next();
                   $player->setMotion($itr->current);
               }
                // $player->setMotion(new \pocketmine\math\Vector3($player->getMotion()->x, 0, $player->getMotion()->z));
           }


           //Part needed for player's good working
           $ref = new \ReflectionClass("pocketmine\\Player");
           $prop = $ref->getProperty("gravity");
           $prop->setAccessible(true);
           $prop->setValue($player, 0);
           $prop->setAccessible(false);
           if($player->getMotion()->y !== 0) {
            //    echo "{$player->getName()}:" . $player->getMotion()->y . "\n";
           }
       }
    }




}
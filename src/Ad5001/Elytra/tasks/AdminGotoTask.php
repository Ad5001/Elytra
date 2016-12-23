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



    /*
    Inverts a number
    @param     $num    int
    @return int
    */
    public function invert(int $num) : int {
        if($num < 0) {
            echo $num . " +> " . abs($num) . "\n";
            return abs($num);
        } else {
            echo $num . " -> " . -$num . "\n";
            return -$num;
        }
        return 0;
    }




}
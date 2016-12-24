<?php


namespace Ad5001\Elytra;


use pocketmine\command\CommandSender;


use pocketmine\command\Command;


use pocketmine\event\Listener;


use pocketmine\plugin\PluginBase;


use pocketmine\Server;


use pocketmine\Player;


use pocketmine\item\Item;


use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;


use pocketmine\utils\BlockIterator;


use pocketmine\item\enchantment\Enchantment;


use Ad5001\Elytra\tasks\AdminGotoTask;






class Main extends PluginBase implements Listener {

    protected $ops;

    /*
    Called when the plugin enables
    */
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new AdminGotoTask($this), 10);
		Item::$list[444] = Elytra::class;
        Item::addCreativeItem(new Elytra());
        $this->ops = [];
    }


    /*
    Prevent when someone is falling
    @param     $event    \pocketmine\event\entity\EntityDamageEvent
    @return null
    */
    public function onEntityDamage(\pocketmine\event\entity\EntityDamageEvent $event) {
        if($event->getCause() == 4 && $event->getEntity()->getInventory()->getChestplate()->getId() == 444) {
            $event->setCancelled();
        }
    }


    /*
    Prevents the player from being kicked of flyign by using the elytras.
    @param     $event    \pocketmine\event\player\PlayerKickEvent
    */
    public function onPlayerKick(\pocketmine\event\player\PlayerKickEvent $event) {
        if(strpos($event->getReason(), "Flying is not enabled on this server") !== false && $event->getPlayer()->getInventory()->getChestplate()->getId() == 444) {
            $event->setCancelled();
        }
    }


    /*
    When a player moves. To make it bounce with elytras.
    @param     $event    \pocketmine\event\player\PlayerMoveEvent
    */
    public function onPlayerMove(\pocketmine\event\player\PlayerMoveEvent $event) {
        $player = $event->getPlayer();
           if($player->getInventory()->getChestplate()->getId() == 444) {
               $flyingup = false;
               for($i = 2; $i > 0; $i--) {
                   if($player->getLevel()->getBlock(new \pocketmine\math\Vector3 (round($player->x), round($player->y) - $i, round($player->z)))->getId() !== 0) {
                       $flyingup = true;
                   }
               }
               if(isset($this->getAdminsModePlayers()[$player->getName()]) && $flyingup) {
                   $player->setMotion(new \pocketmine\math\Vector3($player->getMotion()->x, 3, $player->getMotion()->z));
               }
               $flyingup = false;
               for($i = 4; $i > 0; $i--) {
                   $id = $player->getLevel()->getBlock(new \pocketmine\math\Vector3 (round($player->x), round($player->y) - $i, round($player->z)))->getId();
                   if(in_array($id, $this->getConfig()->get("bouncable_blocks"))) {
                       $flyingup = true;
                   }
               }
               if($flyingup) {
                   $player->setMotion(new \pocketmine\math\Vector3($player->getMotion()->x, 3, $player->getMotion()->z));
               }
           }
    }


    /*
    Called when one of the defined commands of the plugin has been called
    @param     $sender     \pocketmine\command\CommandSender
    @param     $cmd          \pocketmine\command\Command
    @param     $label         mixed
    @param     $args          array
    return bool
    */
    public function onCommand(\pocketmine\command\CommandSender $sender, \pocketmine\command\Command $cmd,$label, array $args): bool {
         switch($cmd->getName()) {
            case "opelytra":
            if($sender instanceof Player) {
                if(isset($this->ops[$sender->getName()])) {
                    unset($this->ops[$sender->getName()]);
                    $sender->sendMessage("§aYou are back to te original elytra !");
                } else {
                    $this->ops[$sender->getName()] = true;
                    $sender->sendMessage("§aYou are now in the admin elytra mode ! Go try out your powers !");
                }
            }
            break;
            case "boost":
            if($sender instanceof Player && $sender->getInventory()->getChestplate()->getId() == 444) {
                if(!isset($args[0])) $args[0] = 2;
                $sender->setMotion(new \pocketmine\math\Vector3($sender->getMotion()->x, $args[0], $sender->getMotion()->z));
            }
            break;
         }
         return false;
    }


    /*
    Returns players in ADMIN mode
    @return array
    */
    public function getAdminsModePlayers() : array {
        return $this->ops;
    }

}
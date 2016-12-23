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


use pocketmine\item\enchantment\Enchantment;


use Ad5001\Elytra\tasks\AdminGotoTask;






class Main extends PluginBase implements Listener {

    /*
    Called when the plugin enables
    */
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new AdminGotoTask($this), 20);
		Item::$list[444] = Elytra::class;
        Item::addCreativeItem(new Elytra());
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
                $item = new Elytra();
                $nbt = new CompoundTag("", [
                    "isAdminPowered" => new StringTag("isAdminPowered", 1)
                ]);
                $item->setCompoundTag($nbt);
                $item->addEnchantment(Enchantment::getEnchantement(0)->setLevel(0));
                $sender->getInventory()->addItem($item);
                $sender->sendMessage("§aYou got your brand new elytra !");
            }
            break;
            case "boost":
            if($sender instanceof Player && $sender->getInventory()->getChestplate()->getId() == 444) {
                $itr = new BlockIterator($sender->getLevel(), $sender->getPosition(), $sender->getDirectionVector(), $sender->getEyeHeight(), 7);
                $itr->next();
                $itr->next();
                $itr->next();
                $itr->next();
                $itr->next();
                $itr->next();
                $sender->setMotion($itr->current());
            }
            break;
         }
         return false;
    }

}
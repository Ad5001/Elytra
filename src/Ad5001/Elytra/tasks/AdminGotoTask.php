<?php
namespace Ad5001\Elytra\tasks;

use pocketmine\scheduler\PluginTask;

use Ad5001\Elytra\Main;

class AdminGotoTask extends PluginTask {

	public function __construct(Main $main) {
		parent::__construct($main);
	}
	public function onRun(int $tick) {
		foreach ($this->getOwner()->getServer()->getOnlinePlayers() as $player) {
			//Part needed for players to fly upwards
			$ref = new \ReflectionClass("pocketmine\\Player");
			$prop = $ref->getProperty("gravity");
			$prop->setAccessible(true);
			$prop->setValue($player, 0);
			$prop->setAccessible(false);
			if($player->getMotion()->y !== 0) {
				$this->getOwner()->getLogger()->debug("{$player->getName()}'s y motion is {$player->getMotion()->y}");
			}
		}
	}
}
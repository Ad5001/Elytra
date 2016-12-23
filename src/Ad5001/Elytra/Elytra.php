<?php


namespace Ad5001\Elytra;



use pocketmine\Server;


use pocketmine\Player;


use pocketmine\item\Armor;


use Ad5001\Elytra\Main;







class Elytra extends Armor {


	public function __construct($meta = 0, $count = 1){
		parent::__construct(444, $meta, $count, "Elytra");
	}




}
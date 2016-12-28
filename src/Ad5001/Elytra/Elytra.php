<?php
//From Elywing.

namespace pocketmine\item;


class Elytra extends Armor{
	
	public function __construct($meta = 0, $count = 1){
		parent::__construct(444, $meta, $count, "Elytra Wings");
	}
	
	public function getArmorType(){
		return Armor::TYPE_CHESTPLATE;
	}
	
	public function getMaxDurability(){
		return 431;
	}
	
	public function isChestplate(){
		return true;
	}
}

<?php
namespace Asidert;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\OfflinePlayer;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\math\Vector3;
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;
use pocketmine\block\Block;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\tile\Sign;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use Asidert\FineChests as FineChests;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\utils\Random;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\entity\Effect;
use pocketmine\entity\InstantEffect;
use Asidert\FineTask;
use pocketmine\item\Stick;
use pocketmine\item\WoodenHoe;
use pocketmine\item\WoodenShovel;
use pocketmine\item\WoodenAxe;
use pocketmine\item\WoodenPickaxe;
use pocketmine\item\WoodenSword;
//use Asidert\Animals\Villager;
use pocketmine\entity\Villager;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\entity\AttributeMap;
use pocketmine\entity\Attribute;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\LevelEventPacket;
use pocketmine\network\protocol\SetTimePacket;
use pocketmine\network\protocol\TextPacket;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\event\level\ChunkLoadEvent;
use pocketmine\level\generator\biome\Biome;
use pocketmine\entity\Snowball;
use pocketmine\entity\Arrow;

class FineBedWars extends PluginBase implements Listener
{
	private static $obj = null;
	public static function getInstance()
	{
		return self::$obj;
	}
    public function onLoad(){
copy($this->getServer()->getDataPath().'worlds/world/copy/r.1.1.mcr',$this->getServer()->getDataPath().'worlds/world/region/r.1.1.mcr');
copy($this->getServer()->getDataPath().'worlds/world/copy/r.1.2.mcr',$this->getServer()->getDataPath().'worlds/world/region/r.1.2.mcr');
copy($this->getServer()->getDataPath().'worlds/world/copy/r.2.1.mcr',$this->getServer()->getDataPath().'worlds/world/region/r.2.1.mcr');
copy($this->getServer()->getDataPath().'worlds/world/copy/r.2.2.mcr',$this->getServer()->getDataPath().'worlds/world/region/r.2.2.mcr');
}
	public function onEnable()
	{
          $level=$this->getServer()->getDefaultLevel();
          $level->setTime(6000);
          $level->stopTime();
		if(!self::$obj instanceof Main)
		{
			self::$obj = $this;
		}
        $this->Trade=$this->getServer()->getPluginManager()->getPlugin("FineTradeNew");
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
   $this->getServer()->getScheduler()->scheduleRepeatingTask(new FineTask([$this,"popupStatus"]),10);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new FineTask([$this,"gameTimer"]),20);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new FineTask([$this,"spawnBronze"]),10);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new FineTask([$this,"spawnIron"]),200);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new FineTask([$this,"spawnGold"]),600);
//		$this->getServer()->getScheduler()->scheduleRepeatingTask(new FineTask([$this,"sendGameStatus"]),1200);
		@mkdir($this->getDataFolder());

        if(! file_exists($this->getDataFolder()."settings.yml")) {
            $this->settings=new Config($this->getDataFolder()."settings.yml", Config::YAML, array(
                    'name' => '§dJapanese Yard',
                    'spawn' => 'world',
                    'arena' => 'world',
                    'redX' => '984.5',
                    'redY' => '65',
                    'redZ' => '929.5',
                    'blueX' => '924.5',
                    'blueY' => '65',
                    'blueZ' => '1009.5',
                    'greenX' => '1001.5',
                    'greenY' => '64',
                    'greenZ' => '1079.5',
                    'yellowX' => '1064.5',
                    'yellowY' => '64',
                    'yellowZ' => '1000.5',
                    'redBedX' => '989',
                    'redBedY' => '75',
                    'redBedZ' => '921',
                    'blueBedX' => '915',
                    'blueBedY' => '75',
                    'blueBedZ' => '1005',
                    'greenBedX' => '998',
                    'greenBedY' => '74',
                    'greenBedZ' => '1087',
                    'yellowBedX' => '1071',
                    'yellowBedY' => '74',
                    'yellowBedZ' => '1005',
                    'bronze1X' => '999.5',
                    'bronze1Y' => '66.5',
                    'bronze1Z' => '929.5',
                    'bronze2X' => '925.5',
                    'bronze2Y' => '66.5',
                    'bronze2Z' => '996.5',
                    'bronze3X' => '1067.5',
                    'bronze3Y' => '65.5',
                    'bronze3Z' => '994.5',
                    'bronze4X' => '987.5',
                    'bronze4Y' => '65.5',
                    'bronze4Z' => '1079.5',
                    'iron1X' => '1050.5',
                    'iron1Y' => '65.5',
                    'iron1Z' => '952.5',
                    'iron2X' => '1048.5',
                    'iron2Y' => '64.5',
                    'iron2Z' => '1065.5',
                    'iron3X' => '954.5',
                    'iron3Y' => '65.5',
                    'iron3Z' => '1059.5',
                    'iron4X' => '947.5',
                    'iron4Y' => '65.5',
                    'iron4Z' => '955.5',
                    'gold1X' => '987.5',
                    'gold1Y' => '51.5',
                    'gold1Z' => '1015.5',
                    'gold2X' => '990.5',
                    'gold2Y' => '51.5',
                    'gold2Z' => '1021.5',
                    'gold3X' => '990.5',
                    'gold3Y' => '50.5',
                    'gold3Z' => '1017.5',
                    'gold4X' => '991.5',
                    'gold4Y' => '51.5',
                    'gold4Z' => '1013.5',
                    'gold5X' => '994.5',
                    'gold5Y' => '50.5',
                    'gold5Z' => '1017.5',
                    'shopRedX' => '983',
                    'shopRedY' => '65',
                    'shopRedZ' => '933',
                    'shopBlueX' => '927',
                    'shopBlueY' => '65',
                    'shopBlueZ' => '1012',
                    'shopGreenX' => '1002',
                    'shopGreenY' => '64',
                    'shopGreenZ' => '1075',
                    'shopYellowX' => '1061',
                    'shopYellowY' => '64',
                    'shopYellowZ' => '998'
                )
            );
        }else{
            $this->settings=new Config($this->getDataFolder()."settings.yml", Config::YAML);
        }
        $this->settings=$this->settings->getAll();
$ss=$this->settings;
			$this->redpos=new Vector3($ss['redX'],$ss['redY'],$ss['redZ']);
			$this->bluepos=new Vector3($ss['blueX'],$ss['blueY'],$ss['blueZ']);
			$this->greenpos=new Vector3($ss['greenX'],$ss['greenY'],$ss['greenZ']);
			$this->yellowpos=new Vector3($ss['yellowX'],$ss['yellowY'],$ss['yellowZ']);
/*			$this->redpos=new Vector3(984.5,65,929.5);
			$this->bluepos=new Vector3(924.5,65,1009.5);
			$this->greenpos=new Vector3(1001.5,64,1079.5);
			$this->yellowpos=new Vector3(1064.5,64,1000.5);
*/
		$this->statusParticle=0;
 		$this->statusParticle1=0; 
		$this->statusParticle2=0;
		$this->statusParticle3=0;
		$this->statusParticle4=0;
 		$this->statusParticle5=0;
 		$this->statusParticle6=0; 
		$this->endTime=637;
		$this->gameTime=1200;
		$this->waitTime=120;
		$this->godTime=0;
		$this->gameStatus=0;
		$this->lastTime=0;
		$this->red=array();
		$this->blue=array();
		$this->green=array();
		$this->yellow=array();
		$this->redBed=1;
		$this->blueBed=1;
		$this->greenBed=1;
		$this->yellowBed=1;
		$this->all=array();
   $this->inWaiting=array();
		$this->getServer()->getLogger()->info(TextFormat::GREEN."[FineBedWars] Загружено!");
	}

public function checkMove(PlayerMoveEvent $e){
$p=$e->getPlayer();
if($this->gameStatus<=1 && $p->y<=10){
$p->teleport(new Vector3(100.5,65,100.5));
}
}

public function popupStatus(){
foreach($this->getServer()->getOnlinePlayers() as $p){
if($this->gameStatus>=2){
if($this->redBed==0){
$rb="[§c❌§f]";
}
if($this->blueBed==0){
$bb="[§c❌§f]";
}
if($this->greenBed==0){
$gb="[§c❌§f]";
}
if($this->yellowBed==0){
$yb="[§c❌§f]";
}
if($this->redBed==1){
$rb="[§a✔§f]";
}
if($this->blueBed==1){
$bb="[§a✔§f]";
}
if($this->greenBed==1){
$gb="[§a✔§f]";
}
if($this->yellowBed==1){
$yb="[§a✔§f]";
}
$rp=count($this->red);
$bp=count($this->blue);
$gp=count($this->green);
$yp=count($this->yellow);
$status=$rb." §c".$rp." §f| ".$bb." §b".$bp." §f| ".$gb." §a".$gp." §f| ".$yb." §e".$yp;
$p->sendPopup($status);
}
}
}

public function onShootBow(EntityShootBowEvent $e){
$p=$e->getEntity();
$p->getInventory()->addItem(Item::get(262,0,1));
}

public function onEndrPirl(EntityDespawnEvent $e){
$ent=$e->getEntity();
if($ent instanceof Snowball){
$p=$ent->shootingEntity;
foreach($this->getServer()->getOnlinePlayers() as $kek){
if($kek->getName()==$p->getName()){
$p->teleport(new Vector3($ent->getX(),$ent->getY(),$ent->getZ()));
}
}
}
}

public function setPlayersCount(){
$l=$this->getServer()->getDefaultLevel();
if($this->gameStatus<=1){
foreach($l->getEntities() as $ent){
if($ent instanceof Villager){
$x=$ent->getX();
$y=$ent->getY();
$z=$ent->getZ();
if($x<150 && $x>50 && $z<150 && $z>50){
$ent->setNameTag("§l§bPlayers in waiting: §c".count($this->red)."§f/§b".count($this->blue)."§f/§a".count($this->green)."§f/§e".count($this->yellow));
}
}
}
}
}

/*public function sendGameStatus(){
if($this->gameStatus>=2){
foreach($this->getServer()->getOnlinePlayers() as $kek){
$kek->sendMessage("[§cBed§fWars] §aСтатус Игры:");
if($this->redBed==1){
$kek->sendMessage("[§cBed§fWars] §cКрасных §fигроков: §c".count($this->red));
}
if($this->redBed==0){
$kek->sendMessage("[§cBed§fWars] §cКрасных §fигроков: ".count($this->red));
}
if($this->blueBed==1){
$kek->sendMessage("[§cBed§fWars] §bСиних §fигроков: §b".count($this->blue));
}
if($this->blueBed==0){
$kek->sendMessage("[§cBed§fWars] §bСиних §fигроков: ".count($this->blue));
}
if($this->greenBed==1){
$kek->sendMessage("[§cBed§fWars] §aЗеленых §fигроков: §a".count($this->green));
}
if($this->greenBed==0){
$kek->sendMessage("[§cBed§fWars] §aЗеленых §fигроков: ".count($this->green));
}
if($this->yellowBed==1){
$kek->sendMessage("[§cBed§fWars] §eЖелтых §fигроков: §e".count($this->yellow));
}
if($this->yellowBed==0){
$kek->sendMessage("[§cBed§fWars] §eЖелтых §fигроков: ".count($this->yellow));
}
}
}
}
*/

public function onDamageRecieve(EntityDamageEvent $e){
$pla=$e->getEntity();
if($pla instanceof Player){
$party=$this->getParty($pla);
$entt=$pla->getName();
if($e->getCause()!==1 && $e->getCause()!==2){
if(($pla->getHealth() - $e->getDamage())<1){
$e->setCancelled();
$pla->getInventory()->setContents(array(Item::get(0, 0, 0)));
$pla->setHealth(20);
$pla->setFood(20);
$entt=$pla->getName();
$this->Trade->Shop[$entt]=0;
$this->Trade->Buyed[$entt]=array();
$this->Trade->ResGold[$entt]=0;
$this->Trade->ResIron[$entt]=0;
$this->Trade->ResBro[$entt]=0;
$this->Trade->PayGold[$entt]=0;
$this->Trade->PayIron[$entt]=0;
$this->Trade->PayBro[$entt]=0;
$this->Trade->Inv[$entt]=array();
$this->Trade->Arm[$entt]=array();
foreach($this->Trade->InShop as $num => $kek){
if($kek==$entt){
array_splice($this->Trade->InShop,$num,1);
}
}
if($party!==1 && $party!==2 && $party!==3 && $party!==4){
$this->tpforhub($pla);
$this->tpforhub($pla);
$this->tpforhub($pla);
}
if($party==1){
if($this->redBed==1){
$this->tptoland($pla,$this->redpos);
$this->tptoland($pla,$this->redpos);
$this->tptoland($pla,$this->redpos);
}else{$pla->kill();}
}
if($party==2){
if($this->blueBed==1){
$this->tptoland($pla,$this->bluepos);
$this->tptoland($pla,$this->bluepos);
$this->tptoland($pla,$this->bluepos);
}else{$pla->kill();}
}
if($party==3){
if($this->greenBed==1){
$this->tptoland($pla,$this->greenpos);
$this->tptoland($pla,$this->greenpos);
$this->tptoland($pla,$this->greenpos);
}else{$pla->kill();}
}
if($party==4){
if($this->yellowBed==1){
$this->tptoland($pla,$this->yellowpos);
$this->tptoland($pla,$this->yellowpos);
$this->tptoland($pla,$this->yellowpos);
}else{$pla->kill();}
}
if($this->gameStatus>=2 && $pla->getX()>=200 && $pla->getZ()>=200){
foreach($this->getServer()->getOnlinePlayers() as $kek){
$kek->sendMessage("[§cBed§fWars] ".$pla->getNameTag()." §fумер");
}
}
}
}
}
if($e instanceof EntityDamageByEntityEvent){
$p=$e->getEntity();
$k=$e->getDamager();
if($p instanceof Player && $k instanceof Player){
$x=$k->getX();
$z=$k->getZ();
if($x<200 && $x>0 && $x<200 && $x>0){
$e->setCancelled();
}
$party=$this->getParty($p);
if($k->getInventory()->getItemInHand()->getId()==280){
if(!$e->isCancelled()){
$e->setKnockBack(1);
}
}
if(($p->getHealth() - $e->getDamage())<1){
$entt=$p->getName();
$e->setCancelled();
$p->getInventory()->setContents(array(Item::get(0, 0, 0)));
$p->setHealth(20);
$p->setFood(20);
$this->Trade->Shop[$entt]=0;
$this->Trade->Buyed[$entt]=array();
$this->Trade->ResGold[$entt]=0;
$this->Trade->ResIron[$entt]=0;
$this->Trade->ResBro[$entt]=0;
$this->Trade->PayGold[$entt]=0;
$this->Trade->PayIron[$entt]=0;
$this->Trade->PayBro[$entt]=0;
$this->Trade->Inv[$entt]=array();
$this->Trade->Arm[$entt]=array();
foreach($this->Trade->InShop as $num => $kek){
if($kek==$entt){
array_splice($this->Trade->InShop,$num,1);
}
}
if($party!==1 && $party!==2 && $party!==3 && $party!==4){
$this->tpforhub($pla);
$this->tpforhub($pla);
$this->tpforhub($pla);
}
if($party==1){
if($this->redBed==1){
$this->tptoland($p,$this->redpos);
}else{$p->kill();}
}
if($party==2){
if($this->blueBed==1){
$this->tptoland($p,$this->bluepos);
}else{$p->kill();}
}
if($party==3){
if($this->greenBed==1){
$this->tptoland($p,$this->greenpos);
}else{$p->kill();}
}
if($party==4){
if($this->yellowBed==1){
$this->tptoland($p,$this->yellowpos);
}else{$p->kill();}
}
foreach($this->getServer()->getOnlinePlayers() as $kek){
$kek->sendMessage("[§cBed§fWars] ".$p->getNameTag()." §fубит игроком ".$k->getNameTag());
}
}
}
}
}

public function spawnRes($x,$y,$z,$id){
$nbt = new CompoundTag("", ["Pos" => new ListTag("Pos", [
 new DoubleTag("", $x),
 new DoubleTag("", $y),
 new DoubleTag("", $z)
 ]),"Motion" => new ListTag("Motion", [
 new DoubleTag("", mt_rand(-100,100)*0.001),
 new DoubleTag("", mt_rand(-100,100)*0.001),
 new DoubleTag("", mt_rand(-100,100)*0.001)
 ]),"Rotation" => new ListTag("Rotation", [
 new FloatTag("", 0),
 new FloatTag("", 0)
 ]),"Health" => new ShortTag("Health", 5),"Item" => new CompoundTag("Item", ["id" => new ShortTag("id", $id),"Damage" => new ShortTag("Damage", 0),"Count" => new ByteTag("Count", 1),
 ]),"PickupDelay" => new ShortTag("PickupDelay", 0),
 ]);
$f = 1;
$res=Entity::createEntity("Item", $this->getServer()->getDefaultLevel()->getChunk($x >> 4, $z >> 4), $nbt);
$res->spawnToAll();
if($id==336){
$this->getServer()->getScheduler()->scheduleDelayedTask(new FineTask([$this,"killRes"],[$res]),200);
}
if($id==265){
$this->getServer()->getScheduler()->scheduleDelayedTask(new FineTask([$this,"killRes"],[$res]),1200);
}
if($id==266){
$this->getServer()->getScheduler()->scheduleDelayedTask(new FineTask([$this,"killRes"],[$res]),2400);
}
}

public function killRes($res){
$res->kill();
}

public function spawnBronze(){
if($this->gameStatus>=2){
$ss=$this->settings;
$this->spawnRes($ss['bronze1X'],$ss['bronze1Y'],$ss['bronze1Z'],336);
$this->spawnRes($ss['bronze2X'],$ss['bronze2Y'],$ss['bronze2Z'],336);
$this->spawnRes($ss['bronze3X'],$ss['bronze3Y'],$ss['bronze3Z'],336);
$this->spawnRes($ss['bronze4X'],$ss['bronze4Y'],$ss['bronze4Z'],336);
/*$this->spawnRes(999.5,66.5,929.5,336);
$this->spawnRes(925.5,66.5,996.5,336);
$this->spawnRes(1067.5,65.5,994.5,336);
$this->spawnRes(987.5,65.5,1079.5,336);
*/
}
}



public function spawnIron(){
if($this->gameStatus>=2){
$ss=$this->settings;
$this->spawnRes($ss['iron1X'],$ss['iron1Y'],$ss['iron1Z'],265);
$this->spawnRes($ss['iron2X'],$ss['iron2Y'],$ss['iron2Z'],265);
$this->spawnRes($ss['iron3X'],$ss['iron3Y'],$ss['iron3Z'],265);
$this->spawnRes($ss['iron4X'],$ss['iron4Y'],$ss['iron4Z'],265);
/*$this->spawnRes(1050.5,65.5,952.5,265);
$this->spawnRes(1048.5,64.5,1065.5,265);
$this->spawnRes(954.5,65.5,1059.5,265);
$this->spawnRes(947.5,65.5,955.5,265);
*/
}
}

public function spawnGold(){
if($this->gameStatus>=2){
$ss=$this->settings;
$this->spawnRes($ss['gold1X'],$ss['gold1Y'],$ss['gold1Z'],266);
$this->spawnRes($ss['gold2X'],$ss['gold2Y'],$ss['gold2Z'],266);
$this->spawnRes($ss['gold3X'],$ss['gold3Y'],$ss['gold3Z'],266);
$this->spawnRes($ss['gold4X'],$ss['gold4Y'],$ss['gold4Z'],266);
$this->spawnRes($ss['gold5X'],$ss['gold5Y'],$ss['gold5Z'],266);
/*$this->spawnRes(987.5,51.5,1015.5,266);
$this->spawnRes(990.5,51.5,1021.5,266);
$this->spawnRes(990.5,50.5,1017.5,266);
$this->spawnRes(991.5,51.5,1013.5,266);
$this->spawnRes(994.5,50.5,1017.5,266);
*/
}
}

/*public function checkCoins(PlayerInteractEvent $event){
$p=$event->getPlayer();
$item=$event->getItem();
$block=$event->getBlock();
$x=$p->getX();
$z=$p->getZ();
if($block->getId()==54 && $x<=100 && $x>=-100 && $z<=100 && $z>=-100){
$event->setCancelled();
$coins=$this->FineCore->getCoins($p);
$lang=$this->FineLanguage->getLang($p);
if($lang=="eng"){
				$p->sendMessage("§b[Cristalix] §bYou have §f".$coins." §e●§b!");
				$p->sendMessage("§b[Cristalix] §aNeed more? Buy at §e***.***.***§a!");
}
if($lang=="rus"){
				$p->sendMessage("§b[Cristalix] §bУ вас §f".$coins." §e●§b!");
				$p->sendMessage("§b[Cristalix] §aНужно больше? Купи на §e***.***.***§a!");
} 
}
}*/

public function fallKill($p){
$party=$this->getParty($p);
$entt=$p->getName();
$pla=$p;
if($p->getHealth()==0){
$p->getInventory()->setContents(array(Item::get(0, 0, 0)));
$p->setHealth(20);
$p->setFood(20);
$this->Trade->Shop[$entt]=0;
$this->Trade->Buyed[$entt]=array();
$this->Trade->ResGold[$entt]=0;
$this->Trade->ResIron[$entt]=0;
$this->Trade->ResBro[$entt]=0;
$this->Trade->PayGold[$entt]=0;
$this->Trade->PayIron[$entt]=0;
$this->Trade->PayBro[$entt]=0;
$this->Trade->Inv[$entt]=array();
$this->Trade->Arm[$entt]=array();
foreach($this->Trade->InShop as $num => $kek){
if($kek==$entt){
array_splice($this->Trade->InShop,$num,1);
}
}
if($party!==1 && $party!==2 && $party!==3 && $party!==4){
$this->tpforhub($pla);
$this->tpforhub($pla);
$this->tpforhub($pla);
}
if($party==1){
if($this->redBed==1){
$this->tptoland($p,$this->redpos);
}else{$p->kill();}
}
if($party==2){
if($this->blueBed==1){
$this->tptoland($p,$this->bluepos);
}else{$p->kill();}
}
if($party==3){
if($this->greenBed==1){
$this->tptoland($p,$this->greenpos);
}else{$p->kill();}
}
if($party==4){
if($this->yellowBed==1){
$this->tptoland($p,$this->yellowpos);
}else{$p->kill();}
}
}
}

public function getParty($nam){
if($nam instanceof Player){
$name=$nam->getName();
}else{$name=$nam;}
foreach($this->red as $nick){
if($nick==$name){
return 1;
}
}
foreach($this->blue as $nick){
if($nick==$name){
return 2;
}
}
foreach($this->green as $nick){
if($nick==$name){
return 3;
}
}
foreach($this->yellow as $nick){
if($nick==$name){
return 4;
}
}
}

public function onBreakBed(BlockBreakEvent $e){
$event=$e;
$block=$e->getBlock();
$id=$block->getId();
$p=$e->getPlayer();
$name=$p->getName();
$party=$this->getParty($name);
$x=$block->getX();
$y=$block->getY();
$z=$block->getZ();
$level=$this->getServer()->getDefaultLevel();
$ss=$this->settings;
if($id!==24 && $id!==26 && $id!==121 && $id!==65 && $id!==30 && $id!==54 && $id!==354 && $p->getGamemode()!==1){
$e->setCancelled();
}
if(($x==$ss['redBedX'] || $x==$ss['redBedX']-1 || $x==$ss['redBedX']+1) && $y==$ss['redBedY'] && ($z==$ss['redBedZ'] || $z==$ss['redBedZ']-1 || $z==$ss['redBedZ']+1) && $this->redBed==1 && $id==26){
//$e->setCancelled();
if($this->gameStatus>=2){
if($party!==1){
$this->redBed=0;
$e->setDrops(array());
//$level->setBlock(new Vector3($x,$y,$z),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x,$y,$z), Block::get(26)));
//$level->setBlock(new Vector3($x+1,$y,$z),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x+1,$y,$z), Block::get(26)));
//$level->setBlock(new Vector3($x-1,$y,$z),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x-1,$y,$z), Block::get(26)));
foreach($this->getServer()->getOnlinePlayers() as $kek){
$kek->sendMessage("[§cBed§fWars] §cКрасная §fкровать разрушена игроком ".$p->getNameTag());
}
}else{
$event->getPlayer()->sendTip("§cВы не можете разрушить свою кровать!");
$event->setCancelled();
}
}
}
if(($x==$ss['blueBedX'] || $x==$ss['blueBedX']-1 || $x==$ss['blueBedX']+1) && $y==$ss['blueBedY'] && ($z==$ss['blueBedZ'] || $z==$ss['blueBedZ']-1 || $z==$ss['blueBedZ']+1) && $this->blueBed==1 && $id==26){

//$e->setCancelled();
if($this->gameStatus>=2){
if($party!==2){
$this->blueBed=0;
$e->setDrops(array());
//$level->setBlock(new Vector3($x,$y,$z),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x,$y,$z), Block::get(26)));
//$level->setBlock(new Vector3($x,$y,$z+1),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x,$y,$z+1), Block::get(26)));
//$level->setBlock(new Vector3($x,$y,$z-1),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x,$y,$z-1), Block::get(26)));
foreach($this->getServer()->getOnlinePlayers() as $kek){
$kek->sendMessage("[§cBed§fWars] §bСиняя §fкровать разрушена игроком ".$p->getNameTag());
}
}else{
$event->getPlayer()->sendTip("§cВы не можете разрушить свою кровать!");
$event->setCancelled();
}
}
}
if(($x==$ss['greenBedX'] || $x==$ss['greenBedX']-1 || $x==$ss['greenBedX']+1) && $y==$ss['greenBedY'] && ($z==$ss['greenBedZ'] || $z==$ss['greenBedZ']-1 || $z==$ss['greenBedZ']+1) && $this->greenBed==1 && $id==26){

//$e->setCancelled();
if($this->gameStatus>=2){
if($party!==3){
$this->greenBed=0;
$e->setDrops(array());
//$level->setBlock(new Vector3($x,$y,$z),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x,$y,$z), Block::get(26)));
//$level->setBlock(new Vector3($x,$y,$z+1),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x,$y,$z+1), Block::get(26)));
//$level->setBlock(new Vector3($x,$y,$z-1),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x,$y,$z-1), Block::get(26)));
foreach($this->getServer()->getOnlinePlayers() as $kek){
$kek->sendMessage("[§cBed§fWars] §aЗеленая §fкровать разрушена игроком ".$p->getNameTag());
}
}else{
$event->getPlayer()->sendTip("§cВы не можете разрушить свою кровать!");
$event->setCancelled();
}
}
}
if(($x==$ss['yellowBedX'] || $x==$ss['yellowBedX']-1 || $x==$ss['yellowBedX']+1) && $y==$ss['yellowBedY'] && ($z==$ss['yellowBedZ'] || $z==$ss['yellowBedZ']-1 || $z==$ss['yellowBedZ']+1) && $this->yellowBed==1 && $id==26){
//$e->setCancelled();
if($this->gameStatus>=2){
if($party!==4){
$this->yellowBed=0;
$e->setDrops(array());
//$level->setBlock(new Vector3($x,$y,$z),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x,$y,$z), Block::get(26)));
//$level->setBlock(new Vector3($x+1,$y,$z),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x+1,$y,$z), Block::get(26)));
//$level->setBlock(new Vector3($x-1,$y,$z),Block::get(0));
$level->addParticle(new \pocketmine\level\particle\DestroyBlockParticle(new Vector3($x-1,$y,$z), Block::get(26)));
foreach($this->getServer()->getOnlinePlayers() as $kek){
$kek->sendMessage("[§cBed§fWars] §eЖелтая §fкровать разрушена игроком ".$p->getNameTag());
}
}else{
$event->getPlayer()->sendTip("§cВы не можете разрушить свою кровать!");
$event->setCancelled();
}
}
}
}

public function onHit(ProjectileHitEvent $event){
$event->getEntity()->kill();
}
	
public function tptoland(Player $p, $pos){
$p->teleport($pos);
$p->teleport($pos);
$p->teleport($pos); 
}

	public function onJoin(PlayerJoinEvent $event){   
$p=$event->getPlayer();
if($p->getY()<10){
$p->teleport(new Vector3(0.5,64.5,0.5));
$this->getServer()->getLogger()->info("Игрок ".$p->getName()." телепортнут на спавн");
}
$effect=Effect::getEffect(1);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect);
$effect=Effect::getEffect(2);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect);
$effect=Effect::getEffect(3);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect); 
$effect=Effect::getEffect(4);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect); 
$effect=Effect::getEffect(5);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect);
$effect=Effect::getEffect(6);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect);
$effect=Effect::getEffect(7);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect); 
$effect=Effect::getEffect(8);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect); 
$effect=Effect::getEffect(9);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect);
$effect=Effect::getEffect(10);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect);
$effect=Effect::getEffect(11);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect); 
$effect=Effect::getEffect(12);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect); 
$effect=Effect::getEffect(13);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect);
$effect=Effect::getEffect(14);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect);
$effect=Effect::getEffect(15);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect); 
$effect=Effect::getEffect(16);$effect->setDuration(1);$effect->setAmplifier(50);$effect->setVisible(false);$p->addEffect($effect); 
//$effect=Effect::getEffect(21);$effect->setDuration(1);$effect->setAmplifier(1);$effect->setVisible(false);$p->addEffect($effect); 
$this->getServer ()->getScheduler ()->scheduleDelayedTask ( new FineTask ( [ $this,"tpforhub" ], [ $p ] ), 1 );
}
	
	public function onDamage(EntityDamageEvent $event){
  	   if($event instanceof EntityDamageByEntityEvent){
$killer=$event->getDamager();
$player=$event->getEntity();
if($player instanceof Player && $killer instanceof Player){
foreach($this->red as $pl){
if($killer->getName()==$pl){
foreach($this->red as $pl){
if($player->getName()==$pl){
$event->setCancelled();
      	 }
      }	
   }
}
foreach($this->blue as $pl){
if($killer->getName()==$pl){
foreach($this->blue as $pl){
if($player->getName()==$pl){
$event->setCancelled();

      	 }
      }	
   }
}
foreach($this->green as $pl){
if($killer->getName()==$pl){
foreach($this->green as $pl){
if($player->getName()==$pl){
$event->setCancelled();
      	 }
      }	
   }
}
foreach($this->yellow as $pl){
if($killer->getName()==$pl){
foreach($this->yellow as $pl){
if($player->getName()==$pl){
$event->setCancelled();
      	 }
      }	
   }
}
}
}
}


	public function gameTimer(){
		if($this->gameStatus==1)
		{
			$this->lastTime--;
			switch($this->lastTime){
			case 1:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->getAttributeMap()->getAttribute(Attribute::EXPERIENCE)->setValue(0);
		}
}
break;
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
            case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
            case 20:
			case 21:
			case 22:
			case 23:
			case 24:
			case 25:
			case 26:
			case 27:
			case 28:
			case 29:
            case 30:
			case 31:
			case 32:
			case 33:
			case 34:
			case 35:
			case 36:
			case 37:
			case 38:
			case 39:
            case 40:
			case 41:
			case 42:
			case 43:
			case 44:
			case 45:
			case 46:
			case 47:
			case 48:
			case 49:
            case 50:
			case 51:
			case 52:
			case 53:
			case 54:
			case 55:
			case 56:
			case 57:
			case 58:
			case 59:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->getAttributeMap()->getAttribute(Attribute::EXPERIENCE)->setValue($this->lastTime/120);
		}
}
break;
			case 60:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->getAttributeMap()->getAttribute(Attribute::EXPERIENCE)->setValue($this->lastTime/120);
		}
}
				break;
			case 61:
			case 62:
			case 63:
			case 64:
			case 65:
			case 66:
			case 67:
			case 68:
			case 69:
            case 70:
			case 71:
			case 72:
			case 73:
			case 74:
			case 75:
			case 76:
			case 77:
			case 78:
			case 79:
            case 80:
			case 81:
			case 82:
			case 83:
			case 84:
			case 85:
			case 86:
			case 87:
			case 88:
			case 89:
            case 90:
			case 91:
			case 92:
			case 93:
			case 94:
			case 95:
			case 96:
			case 97:
			case 98:
			case 99:
            case 100:
			case 101:
			case 102:
			case 103:
			case 104:
			case 105:
			case 106:
			case 107:
			case 108:
			case 109:
            case 110:
			case 111:
			case 112:
			case 113:
			case 114:
			case 115:
			case 116:
			case 117:
			case 118:
			case 119:
                $kekusss=$this->lastTime-60;
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->getAttributeMap()->getAttribute(Attribute::EXPERIENCE)->setValue($this->lastTime/120);
		}
}
				break; 
            case 120:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->getAttributeMap()->getAttribute(Attribute::EXPERIENCE)->setValue($this->lastTime/120);
		}
}
				break;
			case 0:
				$this->gameStatus=2;
/*$l=$this->getServer()->getDefaultLevel();
foreach($l->getEntities() as $ent){
if($ent instanceof Villager){
$x=$ent->getX();
$y=$ent->getY();
$z=$ent->getZ();
if($x<150 && $x>50 && $z<150 && $z>50){
$ent->setNameTag("§cGame was started!");
}
}
}
*/
				$this->players=count($this->red)+count($this->blue)+count($this->green)+count($this->yellow);
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendTip("  §aИгра началась!\n§bИгроков на карте: §f".$this->players);
		}
}
foreach($this->red as $num => $red){$this->getServer()->getPlayer($red)->teleport($this->redpos);$this->getServer()->getPlayer($red)->teleport($this->redpos);$this->getServer()->getPlayer($red)->teleport($this->redpos);}
foreach($this->blue as $num => $blue){$this->getServer()->getPlayer($blue)->teleport($this->bluepos);$this->getServer()->getPlayer($blue)->teleport($this->bluepos);$this->getServer()->getPlayer($blue)->teleport($this->bluepos);}
foreach($this->green as $num => $green){$this->getServer()->getPlayer($green)->teleport($this->greenpos);$this->getServer()->getPlayer($green)->teleport($this->greenpos);$this->getServer()->getPlayer($green)->teleport($this->greenpos);}
foreach($this->yellow as $num => $yellow){$this->getServer()->getPlayer($yellow)->teleport($this->yellowpos);$this->getServer()->getPlayer($yellow)->teleport($this->yellowpos);$this->getServer()->getPlayer($yellow)->teleport($this->yellowpos);}
				$this->lastTime=$this->godTime;
				break;
			}
		}
		if($this->gameStatus==2)
		{
				$this->gameStatus=3;
				$this->lastTime=$this->gameTime;
		}
		if($this->gameStatus>=2){
			if(count($this->blue)==0 && count($this->green)==0 && count($this->yellow)==0){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendMessage("[§cBed§fWars] §aВ игре на карте ".$this->settings['name']." §aпобедила команда §cКрасных§a!");
		}
                $this->allBack();
				$this->red=array();
				$this->blue=array();
				$this->green=array();
				$this->yellow=array();
                $this->all=array();
				$this->gameStatus=0;
				$this->lastTime=0;
                $this->replace();
				return;
			}
			elseif(count($this->red)==0 && count($this->green)==0 && count($this->yellow)==0){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendMessage("[§cBed§fWars] §aВ игре на карте ".$this->settings['name']." §aпобедила команда §bСиних§a!");
		}
                $this->allBack();
				$this->red=array();
				$this->blue=array();
				$this->green=array();
				$this->yellow=array();
                $this->all=array();
				$this->gameStatus=0;
				$this->lastTime=0;
                $this->replace();
				return;
			}
			elseif(count($this->red)==0 && count($this->blue)==0 && count($this->yellow)==0){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendMessage("[§cBed§fWars] §aВ игре на карте ".$this->settings['name']." §aпобедила команда §aЗеленых§a!");
		}
                $this->allBack();
				$this->red=array();
				$this->blue=array();
				$this->green=array();
				$this->yellow=array();
                $this->all=array();
				$this->gameStatus=0;
				$this->lastTime=0;
                $this->replace();
				return;
			}
			elseif(count($this->red)==0 && count($this->blue)==0 && count($this->green)==0){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendMessage("[§cBed§fWars] §aВ игре на карте ".$this->settings['name']." §aпобедила команда §eЖелтых§a!");
		}
                $this->allBack();
				$this->red=array();
				$this->blue=array();
				$this->green=array();
				$this->yellow=array();
                $this->all=array();
				$this->gameStatus=0;
				$this->lastTime=0;
                $this->replace();
				return;
			}
}
		if($this->gameStatus==3)
		{
			$this->lastTime--;
			switch($this->lastTime)
			{
			case 0:
				$this->gameStatus=4;
				$this->lastTime=$this->endTime;
       break;
			}
		}
		if($this->gameStatus==4)
		{
			$this->lastTime--;
			switch($this->lastTime)
			{
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 10:
			case 20:
			case 30:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendTip("§aДо конца игры §f".$this->lastTime." §aсек!");
		}
}
                break;
			case 58:
			case 59:
			case 60:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendTip("§aДо конца игры §f1 §aминута!");
		}
}

                break;
			case 118:
			case 119:
			case 120:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendTip("§aДо конца игры §f2 §aминуты!");
		}
}
                break;
			case 178:
			case 179:
			case 180:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendTip("§aДо конца игры §f3 §aминуты!");
		}
}
                break;
			case 298:
			case 299:
			case 300:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendTip("§aДо конца игры §f5 §aминут!");
		}
}
                break;
			case 598:
			case 599:
			case 600:
		foreach($this->all as $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendTip("§aДо конца игры §f10 §aминут!");
		}
}
                break;
			case 0:
		foreach($this->getServer()->getOnlinePlayers() as $pla){
$pla->sendMessage("[§cBed§fWars] §aВ игре на карте ".$this->settings['name']." §aне победила ни одна команда!");
}
                $this->allBack();
				$this->red=array();
				$this->blue=array();
				$this->green=array();
				$this->yellow=array();
				$this->all=array();
				$this->gameStatus=0;
				$this->lastTime=0;
                $this->replace();
				break;
			}
		}
	}
	
	public function PlayerDeath(PlayerDeathEvent $event){
$pl=$event->getEntity();
$pla=$pl;
$event->setDeathMessage("");
$pla->getInventory()->setContents(array(Item::get(0, 0, 0)));
$entt=$pla->getName();
$this->Trade->Shop[$entt]=0;
$this->Trade->Buyed[$entt]=array();
$this->Trade->ResGold[$entt]=0;
$this->Trade->ResIron[$entt]=0;
$this->Trade->ResBro[$entt]=0;
$this->Trade->PayGold[$entt]=0;
$this->Trade->PayIron[$entt]=0;
$this->Trade->PayBro[$entt]=0;
$this->Trade->Inv[$entt]=array();
$this->Trade->Arm[$entt]=array();
foreach($this->Trade->InShop as $num => $kek){
if($kek==$entt){
array_splice($this->Trade->InShop,$num,1);
}
}
		foreach($this->red as $num => $p){	
        if($p==$event->getEntity()->getName()){
	    	array_splice($this->red,$num,1);
}
}
		foreach($this->blue as $num => $p){	
        if($p==$event->getEntity()->getName()){
	    	array_splice($this->blue,$num,1);
}
}
		foreach($this->green as $num => $p){	
        if($p==$event->getEntity()->getName()){
	    	array_splice($this->green,$num,1);
}
}
		foreach($this->yellow as $num => $p){	
        if($p==$event->getEntity()->getName()){
	    	array_splice($this->yellow,$num,1);
}
}
$this->getServer()->getScheduler()->scheduleDelayedTask(new FineTask([$this,"fallKill"],[$pl]),5);
     	   if($this->gameStatus>=2){
		foreach($this->red as $num => $p){	
        if($p==$event->getEntity()->getName()){
	    	array_splice($this->red,$num,1);
}
}
		foreach($this->blue as $num => $p){	
        if($p==$event->getEntity()->getName()){
	    	array_splice($this->blue,$num,1);
}
}
		foreach($this->green as $num => $p){	
        if($p==$event->getEntity()->getName()){
	    	array_splice($this->green,$num,1);
}
}
		foreach($this->yellow as $num => $p){	
        if($p==$event->getEntity()->getName()){
	    	array_splice($this->yellow,$num,1);
}
}
}
}

	public function playerBlockTouch(PlayerInteractEvent $event){
		$block=$event->getBlock();
		$p=$event->getPlayer();
        $level=$this->getServer()->getDefaultLevel();
if($this->gameStatus <= 1){
   if($block->getId()==35 && $block->getDamage()==14){
   if(count($this->red)<5){
		foreach($this->red as $num => $pl){if($pl==$p->getName()){array_splice($this->red,$num,1);}}
		foreach($this->blue as $num => $pl){if($pl==$p->getName()){array_splice($this->blue,$num,1);}}
		foreach($this->green as $num => $pl){if($pl==$p->getName()){array_splice($this->green,$num,1);}}
		foreach($this->yellow as $num => $pl){if($pl==$p->getName()){array_splice($this->yellow,$num,1);}}
		foreach($this->all as $num => $pl){if($pl==$p->getName()){array_splice($this->all,$num,1);}}
 		array_push($this->red,$event->getPlayer()->getName());
		array_push($this->all,$event->getPlayer()->getName());
$event->getPlayer()->sendMessage("[§cBed§fWars] Вы присоединились к §cКрасной §fкоманде");
$p->setNameTag("§c".$p->getName());
if(
count($this->red)>0 && count($this->blue)>0 && count($this->green)==0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)>0 && count($this->blue)==0 && count($this->green)>0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)>0 && count($this->blue)==0 && count($this->green)==0 && count($this->yellow)>0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)>0 && count($this->green)>0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)>0 && count($this->green)==0 && count($this->yellow)>0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)==0 && count($this->green)>0 && count($this->yellow)>0 && $this->lastTime==0
){
	$this->gameStatus=1;
	$this->lastTime=120;
}
}
else{
$event->getPlayer()->sendMessage("[§cBed§fWars] §cНа этой карте уже слишком много Красных!");
}
}
   elseif($block->getId()==35 && $block->getDamage()==11){
   if(count($this->blue)<5){
		foreach($this->red as $num => $pl){if($pl==$p->getName()){array_splice($this->red,$num,1);}}
		foreach($this->blue as $num => $pl){if($pl==$p->getName()){array_splice($this->blue,$num,1);}}
		foreach($this->green as $num => $pl){if($pl==$p->getName()){array_splice($this->green,$num,1);}}
		foreach($this->yellow as $num => $pl){if($pl==$p->getName()){array_splice($this->yellow,$num,1);}}
		foreach($this->all as $num => $pl){if($pl==$p->getName()){array_splice($this->all,$num,1);}}
		array_push($this->blue,$event->getPlayer()->getName());
		array_push($this->all,$event->getPlayer()->getName());
$event->getPlayer()->sendMessage("[§cBed§fWars] Вы присоединились к §bСиней §fкоманде");
$p->setNameTag("§b".$p->getName());
if(
count($this->red)>0 && count($this->blue)>0 && count($this->green)==0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)>0 && count($this->blue)==0 && count($this->green)>0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)>0 && count($this->blue)==0 && count($this->green)==0 && count($this->yellow)>0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)>0 && count($this->green)>0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)>0 && count($this->green)==0 && count($this->yellow)>0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)==0 && count($this->green)>0 && count($this->yellow)>0 && $this->lastTime==0
){
	$this->gameStatus=1;
  $this->lastTime=120;
}
}
else{
$event->getPlayer()->sendMessage("[§cBed§fWars] §cНа этой карте уже слишком много §bСиних§c!");
}
}
   elseif($block->getId()==35 && $block->getDamage()==5){
   if(count($this->green)<5){
		foreach($this->red as $num => $pl){if($pl==$p->getName()){array_splice($this->red,$num,1);}}
		foreach($this->blue as $num => $pl){if($pl==$p->getName()){array_splice($this->blue,$num,1);}}
		foreach($this->green as $num => $pl){if($pl==$p->getName()){array_splice($this->green,$num,1);}}
		foreach($this->yellow as $num => $pl){if($pl==$p->getName()){array_splice($this->yellow,$num,1);}}
		foreach($this->all as $num => $pl){if($pl==$p->getName()){array_splice($this->all,$num,1);}}
		array_push($this->green,$event->getPlayer()->getName());
		array_push($this->all,$event->getPlayer()->getName());
$event->getPlayer()->sendMessage("[§cBed§fWars] Вы присоединились к §aЗеленой §fкоманде");
$p->setNameTag("§a".$p->getName());
if(
count($this->red)>0 && count($this->blue)>0 && count($this->green)==0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)>0 && count($this->blue)==0 && count($this->green)>0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)>0 && count($this->blue)==0 && count($this->green)==0 && count($this->yellow)>0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)>0 && count($this->green)>0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)>0 && count($this->green)==0 && count($this->yellow)>0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)==0 && count($this->green)>0 && count($this->yellow)>0 && $this->lastTime==0
){
	$this->gameStatus=1;
	$this->lastTime=120;
}
}
else{
$event->getPlayer()->sendMessage("[§cBed§fWars] §cНа этой карте уже слишком много §aЗеленых§c!");
}
}
   elseif($block->getId()==35 && $block->getDamage()==4){
   if(count($this->yellow)<5){
		foreach($this->red as $num => $pl){if($pl==$p->getName()){array_splice($this->red,$num,1);}}
		foreach($this->blue as $num => $pl){if($pl==$p->getName()){array_splice($this->blue,$num,1);}}
		foreach($this->green as $num => $pl){if($pl==$p->getName()){array_splice($this->green,$num,1);}}
		foreach($this->yellow as $num => $pl){if($pl==$p->getName()){array_splice($this->yellow,$num,1);}}
		foreach($this->all as $num => $pl){if($pl==$p->getName()){array_splice($this->all,$num,1);}}
		array_push($this->yellow,$event->getPlayer()->getName());
		array_push($this->all,$event->getPlayer()->getName());
$event->getPlayer()->sendMessage("[§cBed§fWars] Вы присоединились к §eЖелтой §fкоманде");
$p->setNameTag("§e".$p->getName());
if(
count($this->red)>0 && count($this->blue)>0 && count($this->green)==0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)>0 && count($this->blue)==0 && count($this->green)>0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)>0 && count($this->blue)==0 && count($this->green)==0 && count($this->yellow)>0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)>0 && count($this->green)>0 && count($this->yellow)==0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)>0 && count($this->green)==0 && count($this->yellow)>0 && $this->lastTime==0 ||
count($this->red)==0 && count($this->blue)==0 && count($this->green)>0 && count($this->yellow)>0 && $this->lastTime==0
){
	$this->gameStatus=1;
  $this->lastTime=120;
}
}
else{
$event->getPlayer()->sendMessage("[§cBed§fWars] §cНа этой карте уже слишком много §eЖелтых§c!");
}
}
 }
if($this->gameStatus >= 2){
   if($block->getId()==35 && $block->getDamage()==14 || $block->getId()==35 && $block->getDamage()==11 || $block->getId()==35 && $block->getDamage()==5 || $block->getId()==35 && $block->getDamage()==4){
$p->sendMessage("[§cBed§fWars] §cИгра уже идет!");
}
 }
  }
	
	public function PlayerQuit(PlayerQuitEvent $event){
		foreach($this->inWaiting as $num => $p){	
        if($p==$event->getPlayer()->getName()){
	    	array_splice($this->inWaiting,$num,1);
}
}
		foreach($this->red as $num => $p){	
        if($p==$event->getPlayer()->getName()){
	    	array_splice($this->red,$num,1);
}
}
		foreach($this->blue as $num => $p){	
        if($p==$event->getPlayer()->getName()){
	    	array_splice($this->blue,$num,1);
}
}
		foreach($this->green as $num => $p){	
        if($p==$event->getPlayer()->getName()){
	    	array_splice($this->green,$num,1);
}
}
		foreach($this->yellow as $num => $p){	
        if($p==$event->getPlayer()->getName()){
	    	array_splice($this->yellow,$num,1);
}
}
}

	public function allBack(){
		foreach($this->all as $num => $p){
		foreach($this->getServer()->getOnlinePlayers() as $pla){
if($p==$pla->getName()){ 
$this->getServer()->getPlayer($p)->teleport(new Vector3(100.5, 65, 100.5));
$this->getServer()->getPlayer($p)->getInventory()->setArmorItem(0,Item::get(0));
$this->getServer()->getPlayer($p)->getInventory()->setArmorItem(1,Item::get(0));
$this->getServer()->getPlayer($p)->getInventory()->setArmorItem(2,Item::get(0));
$this->getServer()->getPlayer($p)->getInventory()->setArmorItem(3,Item::get(0));
$this->getServer()->getPlayer($p)->getInventory()->setContents(array(Item::get(0, 0, 0)));
          }
		}
}
}

	public function onDisable(){
		$this->getServer()->getLogger()->info(TextFormat::RED."[FineBedWars] Выключено !");
	}

    public function replace(){
           $this->getServer ()->getScheduler ()->scheduleDelayedTask ( new FineTask ( [ $this, "load" ]), 200 );
    }

    public function tpforhub($p){
           $p->teleport(new Vector3(100.5,64,100.5));
    }

    public function load(){
copy($this->getServer()->getDataPath().'worlds/world/copy/r.1.1.mcr',$this->getServer()->getDataPath().'worlds/world/region/r.1.1.mcr');
copy($this->getServer()->getDataPath().'worlds/world/copy/r.1.2.mcr',$this->getServer()->getDataPath().'worlds/world/region/r.1.2.mcr');
copy($this->getServer()->getDataPath().'worlds/world/copy/r.2.1.mcr',$this->getServer()->getDataPath().'worlds/world/region/r.2.1.mcr');
copy($this->getServer()->getDataPath().'worlds/world/copy/r.2.2.mcr',$this->getServer()->getDataPath().'worlds/world/region/r.2.2.mcr');
           $this->getServer ()->getScheduler ()->scheduleDelayedTask ( new FineTask ( [ $this, "stop" ]), 20 ); 
    }

    public function stop(){
           $this->getServer()->shutdown();
    }

}
?>

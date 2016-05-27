<?php
namespace Asidert;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\math\Vector3;
use pocketmine\entity\Effect;
use pocketmine\entity\InstantEffect;
use pocketmine\block\Block;
use pocketmine\utils\TextFormat;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\Server;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\item\Item;
use Asidert\TextTask;
use Asidert\CallbackTask;
use Asidert\Animals\Cow;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\EnumTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\entity\Entity;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\inventory\InventoryType;
use pocketmine\inventory\TransactionGroup;
use pocketmine\inventory\Transaction;
use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\BaseTransaction;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Villager;

class FineTradeNew extends PluginBase implements Listener {
	public function onEnable() {
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
   $this->InShop=array();
   $this->bw=$this->getServer()->getPluginManager()->getPlugin("FineBedWars");
	}

public function onJoins(PlayerJoinEvent $e){
$p=$e->getPlayer()->getName();
$this->Shop[$p]=0;
$this->Buyed[$p]=array();
$this->ResGold[$p]=0;
$this->ResIron[$p]=0;
$this->ResBro[$p]=0;
$this->PayGold[$p]=0;
$this->PayIron[$p]=0;
$this->PayBro[$p]=0;
$this->Inv[$p]=array();
$this->Arm[$p]=array();
}

public function isInShop($p){
foreach($this->InShop as $kek){
if($p->getName()==$kek){
return true;
}
}
}

public function onTapppppp(PlayerInteractEvent $e){
$p=$e->getPlayer();
$block=$e->getBlock();
$x=$block->x;
$y=$block->y;
$z=$block->z;
$ss=$this->bw->settings;
if(($x==$ss['shopRedX'] && $y==$ss['shopRedY'] && $z==$ss['shopRedZ'])||
($x==$ss['shopBlueX'] && $y==$ss['shopBlueY'] && $z==$ss['shopBlueZ'])||
($x==$ss['shopGreenX'] && $y==$ss['shopGreenY'] && $z==$ss['shopGreenZ'])||
($x==$ss['shopYellowX'] && $y==$ss['shopYellowY'] && $z==$ss['shopYellowZ'])){
$e->setCancelled();
if($this->isInShop($p)!==true){
array_push($this->InShop,$p->getName());
$this->Arm[$p->getName()]=$p->getInventory()->getArmorContents();
for($i=0;$i<36;$i++){
$item=$p->getInventory()->getItem($i);
array_push($this->Inv[$p->getName()],$item);
if($item->getId()==336){$this->ResBro[$p->getName()]=$this->ResBro[$p->getName()] + $item->getCount();}
if($item->getId()==265){$this->ResIron[$p->getName()]=$this->ResIron[$p->getName()] + $item->getCount();}
if($item->getId()==266){$this->ResGold[$p->getName()]=$this->ResGold[$p->getName()] + $item->getCount();
}}
$this->PayBro[$p->getName()]=$this->ResBro[$p->getName()];
$this->PayIron[$p->getName()]=$this->ResIron[$p->getName()];
$this->PayGold[$p->getName()]=$this->ResGold[$p->getName()];
$p->getInventory()->setHotbarSlotIndex(0, 35);
$p->getInventory()->setHotbarSlotIndex(1, 35);
$p->getInventory()->setHotbarSlotIndex(2, 35);
$p->getInventory()->setHotbarSlotIndex(3, 35);
$p->getInventory()->setHotbarSlotIndex(4, 35);
$p->getInventory()->setHotbarSlotIndex(5, 35);
$p->getInventory()->setHotbarSlotIndex(6, 35);
$p->getInventory()->setHotbarSlotIndex(7, 35);
$p->getInventory()->setHotbarSlotIndex(8, 35);
$item=Item::get(24,0,1);
    		$p->getInventory()->setItem(0,$item);
$item=Item::get(278,0,1);
    		$p->getInventory()->setItem(1,$item);
$item=Item::get(311,0,1);
    		$p->getInventory()->setItem(2,$item);
$item=Item::get(276,0,1);
    		$p->getInventory()->setItem(3,$item);
$item=Item::get(261,0,1);
    		$p->getInventory()->setItem(4,$item);
$item=Item::get(260,0,1);
    		$p->getInventory()->setItem(5,$item);
$item=Item::get(54,0,1);
    		$p->getInventory()->setItem(6,$item);
$item=Item::get(373,0,1);
    		$p->getInventory()->setItem(7,$item);
$item=Item::get(46,0,1);
    		$p->getInventory()->setItem(8,$item);
$item=Item::get(35,14,1);
    		$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);
$p->getInventory()->setItem(10,$item);
$p->getInventory()->setItem(11,$item);
$p->getInventory()->setItem(12,$item);
$p->getInventory()->setItem(13,$item);
$p->getInventory()->setItem(14,$item);
$p->getInventory()->setItem(15,$item);
$p->getInventory()->setItem(16,$item);
$p->getInventory()->setItem(17,$item);
$p->getInventory()->setItem(18,$item);
$p->getInventory()->setItem(19,$item);
$p->getInventory()->setItem(20,$item);
$p->getInventory()->setItem(21,$item);
$p->getInventory()->setItem(22,$item);
$p->getInventory()->setItem(23,$item);
$p->getInventory()->setItem(24,$item);
$p->getInventory()->setItem(25,$item);
$p->getInventory()->setItem(26,$item);
$p->getInventory()->setItem(27,$item);
$p->getInventory()->setItem(28,$item);
$p->getInventory()->setItem(29,$item);
$p->getInventory()->setItem(30,$item);
$p->getInventory()->setItem(31,$item);
$p->getInventory()->setItem(32,$item);
$p->getInventory()->setItem(33,$item);
$p->getInventory()->setItem(34,$item);
$p->getInventory()->setItem(35,$item);
$p->getInventory()->setHotbarSlotIndex(0, 35);
$p->sendTip("§aОткройте свой инвентарь для покупок!");
}
}
if($this->isInShop($p)==true){
$e->setCancelled();
}
}

public function onClose(InventoryCloseEvent $e){
$p=$e->getPlayer();
if($this->isInShop($p)==true){
$p->getInventory()->clearAll();
foreach($this->Inv[$p->getName()] as $item){
$p->getInventory()->addItem($item);
}
$co=$this->ResBro[$p->getName()] - $this->PayBro[$p->getName()];
$p->getInventory()->removeItem(Item::get(336,0,$co));
$co=$this->ResIron[$p->getName()] - $this->PayIron[$p->getName()];
$p->getInventory()->removeItem(Item::get(265,0,$co));
$co=$this->ResGold[$p->getName()] - $this->PayGold[$p->getName()];
$p->getInventory()->removeItem(Item::get(266,0,$co));
foreach($this->Buyed[$p->getName()] as $item){
$p->getInventory()->addItem($item);
}
$this->Inv[$p->getName()]=array();
$this->Buyed[$p->getName()]=array();
$this->ResGold[$p->getName()]=0;
$this->ResIron[$p->getName()]=0;
$this->ResBro[$p->getName()]=0;
$this->ResGold[$p->getName()]=0;
$this->ResIron[$p->getName()]=0;
$this->ResBro[$p->getName()]=0;
$this->Arm[$p->getName()]=array();
foreach($this->InShop as $num => $kek){
if($kek==$p->getName()){
array_splice($this->InShop,$num,1);
}
}
}
}

public function Tappp(EntityDamageEvent $e){
$ent=$e->getEntity();
if($ent instanceof Player){
if(($ent->getHealth() - $e->getDamage())<1){
$entt=$ent->getName();
$this->Shop[$entt]=0;
$this->Buyed[$entt]=array();
$this->ResGold[$entt]=0;
$this->ResIron[$entt]=0;
$this->ResBro[$entt]=0;
$this->PayGold[$entt]=0;
$this->PayIron[$entt]=0;
$this->PayBro[$entt]=0;
$this->Inv[$entt]=array();
foreach($this->InShop as $num => $kek){
if($kek==$entt){
array_splice($this->InShop,$num,1);
}
}
}
}
if($e instanceof EntityDamageByEntityEvent){
$p=$e->getDamager();
$ent=$e->getEntity();
if($ent instanceof Player && $p instanceof Player){
if(($ent->getHealth() - $e->getDamage())<1){
$entt=$ent->getName();
$this->Shop[$entt]=0;
$this->Buyed[$entt]=array();
$this->ResGold[$entt]=0;
$this->ResIron[$entt]=0;
$this->ResBro[$entt]=0;
$this->PayGold[$entt]=0;
$this->PayIron[$entt]=0;
$this->PayBro[$entt]=0;
$this->Inv[$entt]=array();
foreach($this->InShop as $num => $kek){
if($kek==$entt){
array_splice($this->InShop,$num,1);
}
}
}
}
/*if($ent instanceof Villager){
$e->setCancelled();
if($ent->getX()>=200){
if($this->isInShop($p)!==true){
array_push($this->InShop,$p->getName());
$this->Arm[$p->getName()]=$p->getInventory()->getArmorContents();
for($i=0;$i<36;$i++){
$item=$p->getInventory()->getItem($i);
array_push($this->Inv[$p->getName()],$item);
if($item->getId()==336){$this->ResBro[$p->getName()]=$this->ResBro[$p->getName()] + $item->getCount();}
if($item->getId()==265){$this->ResIron[$p->getName()]=$this->ResIron[$p->getName()] + $item->getCount();}
if($item->getId()==266){$this->ResGold[$p->getName()]=$this->ResGold[$p->getName()] + $item->getCount();
}}
$this->PayBro[$p->getName()]=$this->ResBro[$p->getName()];
$this->PayIron[$p->getName()]=$this->ResIron[$p->getName()];
$this->PayGold[$p->getName()]=$this->ResGold[$p->getName()];
$p->getInventory()->setHotbarSlotIndex(0, 35);
$p->getInventory()->setHotbarSlotIndex(1, 35);
$p->getInventory()->setHotbarSlotIndex(2, 35);
$p->getInventory()->setHotbarSlotIndex(3, 35);
$p->getInventory()->setHotbarSlotIndex(4, 35);
$p->getInventory()->setHotbarSlotIndex(5, 35);
$p->getInventory()->setHotbarSlotIndex(6, 35);
$p->getInventory()->setHotbarSlotIndex(7, 35);
$p->getInventory()->setHotbarSlotIndex(8, 35);
$item=Item::get(24,0,1);
    		$p->getInventory()->setItem(0,$item);
$item=Item::get(278,0,1);
    		$p->getInventory()->setItem(1,$item);
$item=Item::get(311,0,1);
    		$p->getInventory()->setItem(2,$item);
$item=Item::get(276,0,1);
    		$p->getInventory()->setItem(3,$item);
$item=Item::get(261,0,1);
    		$p->getInventory()->setItem(4,$item);
$item=Item::get(260,0,1);
    		$p->getInventory()->setItem(5,$item);
$item=Item::get(54,0,1);
    		$p->getInventory()->setItem(6,$item);
$item=Item::get(373,0,1);
    		$p->getInventory()->setItem(7,$item);
$item=Item::get(46,0,1);
    		$p->getInventory()->setItem(8,$item);
$item=Item::get(35,14,1);
    		$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);
$p->getInventory()->setItem(10,$item);
$p->getInventory()->setItem(11,$item);
$p->getInventory()->setItem(12,$item);
$p->getInventory()->setItem(13,$item);
$p->getInventory()->setItem(14,$item);
$p->getInventory()->setItem(15,$item);
$p->getInventory()->setItem(16,$item);
$p->getInventory()->setItem(17,$item);
$p->getInventory()->setItem(18,$item);
$p->getInventory()->setItem(19,$item);
$p->getInventory()->setItem(20,$item);
$p->getInventory()->setItem(21,$item);
$p->getInventory()->setItem(22,$item);
$p->getInventory()->setItem(23,$item);
$p->getInventory()->setItem(24,$item);
$p->getInventory()->setItem(25,$item);
$p->getInventory()->setItem(26,$item);
$p->getInventory()->setItem(27,$item);
$p->getInventory()->setItem(28,$item);
$p->getInventory()->setItem(29,$item);
$p->getInventory()->setItem(30,$item);
$p->getInventory()->setItem(31,$item);
$p->getInventory()->setItem(32,$item);
$p->getInventory()->setItem(33,$item);
$p->getInventory()->setItem(34,$item);
$p->getInventory()->setItem(35,$item);
$p->getInventory()->setHotbarSlotIndex(0, 35);
$p->sendTip("§aОткройте свой инвентарь для покупок!");
}
}
}*/
}
}

public function Shopping(PlayerItemHeldEvent $e){
$lang="rus";
$p=$e->getPlayer();
$item=$e->getItem();
if($this->isInShop($p)==true){
$e->setCancelled();
$p->getInventory()->setHotbarSlotIndex(0, 27);
$p->getInventory()->setHotbarSlotIndex(1, 28);
$p->getInventory()->setHotbarSlotIndex(2, 29);
$p->getInventory()->setHotbarSlotIndex(3, 30);
$p->getInventory()->setHotbarSlotIndex(4, 31);
$p->getInventory()->setHotbarSlotIndex(5, 32);
$p->getInventory()->setHotbarSlotIndex(6, 33);
$p->getInventory()->setHotbarSlotIndex(7, 34);
$p->getInventory()->setHotbarSlotIndex(8, 35);
$e->setCancelled();
$bro=$this->ResBro[$p->getName()];
$iron=$this->ResIron[$p->getName()];
$gold=$this->ResGold[$p->getName()];
$pbro=$this->PayBro[$p->getName()];
$piron=$this->PayIron[$p->getName()];
$pgold=$this->PayGold[$p->getName()];
$shop=$this->Shop[$p->getName()];

if($item->getId()==24 && $this->Shop[$p->getName()]==0){
$this->Shop[$p->getName()]=1;
$item=Item::get(24,0,2);$p->getInventory()->setItem(0,$item);
$item=Item::get(336,0,1);$p->getInventory()->setItem(1,$item);
$item=Item::get(121,0,1);$p->getInventory()->setItem(2,$item);
$item=Item::get(336,0,7);$p->getInventory()->setItem(3,$item);
$item=Item::get(35,14,1);$p->getInventory()->setItem(4,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(5,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(6,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(7,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(8,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(10,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(11,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(12,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(13,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(14,$item);
}

if($item->getId()==24 && $this->Shop[$p->getName()]==1){
$item=Item::get(24,0,2);
if($this->PayBro[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -1;
$p->sendPopup("§bВы купили §eSandstone §fx2!");
}else{
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}

if($item->getId()==121 && $this->Shop[$p->getName()]==1){
$item=Item::get(121,0,1);
if($this->PayBro[$p->getName()]>=7){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -7;
$p->sendPopup("§bВы купили §fEnd Stone!");
}else{
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}

if($item->getId()==278 && $this->Shop[$p->getName()]==0){
$this->Shop[$p->getName()]=1;
$item=Item::get(270,0,1);$p->getInventory()->setItem(0,$item);
$item=Item::get(336,0,4);$p->getInventory()->setItem(1,$item);
$item=Item::get(274,0,1);$p->getInventory()->setItem(2,$item);
$item=Item::get(265,0,2);$p->getInventory()->setItem(3,$item);
$item=Item::get(257,0,1);$p->getInventory()->setItem(4,$item);
$item=Item::get(266,0,1);$p->getInventory()->setItem(5,$item);
$item=Item::get(35,14,1);$p->getInventory()->setItem(6,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(7,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(8,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(10,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(11,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(12,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(13,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(14,$item);
}

if($item->getId()==270 && $this->Shop[$p->getName()]==1){
$item=Item::get(270,0,1);
if($this->PayBro[$p->getName()]>=4){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -4;
$p->sendPopup("§bВы купили §6Wooden Pickaxe§f!");
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fBronze §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}
}

if($item->getId()==274 && $this->Shop[$p->getName()]==1){

$item=Item::get(274,0,1);
if($this->PayIron[$p->getName()]>=2){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -2;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §7Stone Pickaxe§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §7Stone Pickaxe§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==257 && $this->Shop[$p->getName()]==1){
$item=Item::get(257,0,1);
if($this->PayGold[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayGold[$p->getName()] = $this->PayGold[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fIron Pickaxe§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fIron Pickaxe§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §eGold §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §eЗолота §cдля покупки этого!");
}
}
}

if($item->getId()==311 && $this->Shop[$p->getName()]==0){
$this->Shop[$p->getName()]=1;
$item=Item::get(298,0,1);$p->getInventory()->setItem(0,$item);
$item=Item::get(300,0,1);$p->getInventory()->setItem(1,$item);
$item=Item::get(301,0,1);$p->getInventory()->setItem(2,$item);
$item=Item::get(336,0,1);$p->getInventory()->setItem(3,$item);
$item=Item::get(303,0,1);$p->getInventory()->setItem(4,$item);
$item=Item::get(265,0,1);$p->getInventory()->setItem(5,$item);
$item=Item::get(307,0,1);$p->getInventory()->setItem(6,$item);
$item=Item::get(265,0,3);$p->getInventory()->setItem(7,$item);
$item=Item::get(311,0,1);$p->getInventory()->setItem(8,$item);
$item=Item::get(265,0,7);$p->getInventory()->setItem(9,$item);
$item=Item::get(35,14,1);$p->getInventory()->setItem(10,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(11,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(12,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(13,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(14,$item);
}

if($item->getId()==298 && $this->Shop[$p->getName()]==1){
$item=Item::get(298,0,1);
if($this->PayBro[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §6Leather Cap§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §6Leather Cap§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fBronze §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}
}

if($item->getId()==300 && $this->Shop[$p->getName()]==1){
$item=Item::get(300,0,1);
if($this->PayBro[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §6Leather Leggins§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §6Leather Leggins§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fBronze §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}
}

if($item->getId()==301 && $this->Shop[$p->getName()]==1){
$item=Item::get(301,0,1);
if($this->PayBro[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §6Leather Boots§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §6Leather Boots§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fBronze §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}
}

if($item->getId()==303 && $this->Shop[$p->getName()]==1){
$item=Item::get(303,0,1);
if($this->PayIron[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fChain Chestplate§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fChain Chestplate§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==307 && $this->Shop[$p->getName()]==1){
$item=Item::get(307,0,1);
if($this->PayIron[$p->getName()]>=3){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -3;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fIron Chestplate§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fIron Chestplate§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==311 && $this->Shop[$p->getName()]==1){
$item=Item::get(311,0,1);
if($this->PayIron[$p->getName()]>=7){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -7;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §bDiamond Chestplate§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §bDiamond Chestplate§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==276 && $this->Shop[$p->getName()]==0){
$this->Shop[$p->getName()]=1;
$item=Item::get(280,0,1);$p->getInventory()->setItem(0,$item);
$item=Item::get(336,0,8);$p->getInventory()->setItem(1,$item);
$item=Item::get(272,0,1);$p->getInventory()->setItem(2,$item);
$item=Item::get(265,0,1);$p->getInventory()->setItem(3,$item);
$item=Item::get(267,0,1);$p->getInventory()->setItem(4,$item);
$item=Item::get(265,0,3);$p->getInventory()->setItem(5,$item);
$item=Item::get(276,0,1);$p->getInventory()->setItem(6,$item);
$item=Item::get(266,0,5);$p->getInventory()->setItem(7,$item);
$item=Item::get(35,14,1);$p->getInventory()->setItem(8,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(10,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(11,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(12,$item);
}

if($item->getId()==280 && $this->Shop[$p->getName()]==1){
$item=Item::get(280,0,1);
if($this->PayBro[$p->getName()]>=8){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -8;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §6Stick§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §6Stick§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fBronze §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}
}

if($item->getId()==272 && $this->Shop[$p->getName()]==1){
$item=Item::get(272,0,1);
if($this->PayIron[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §7Stone Sword§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §7Stone Sword§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==267 && $this->Shop[$p->getName()]==1){
$item=Item::get(267,0,1);
if($this->PayIron[$p->getName()]>=3){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -3;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fIron Sword§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fIron Sword§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==276 && $this->Shop[$p->getName()]==1){
$item=Item::get(276,0,1);
if($this->PayGold[$p->getName()]>=5){
array_push($this->Buyed[$p->getName()],$item);
$this->PayGold[$p->getName()] = $this->PayGold[$p->getName()] -5;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §bDiamond Sword§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §bDiamond Sword§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §eGold §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §eЗолота §cдля покупки этого!");
}
}
}

if($item->getId()==261 && $this->Shop[$p->getName()]==0){
$this->Shop[$p->getName()]=1;
$item=Item::get(261,0,1);$p->getInventory()->setItem(0,$item);
$item=Item::get(266,0,3);$p->getInventory()->setItem(1,$item);
$item=Item::get(262,0,1);$p->getInventory()->setItem(2,$item);
$item=Item::get(266,0,1);$p->getInventory()->setItem(3,$item);
$item=Item::get(35,14,1);$p->getInventory()->setItem(4,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(5,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(6,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(7,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(8,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(10,$item);
}

if($item->getId()==261 && $this->Shop[$p->getName()]==1){
$item=Item::get(261,0,1);
if($this->PayGold[$p->getName()]>=3){
array_push($this->Buyed[$p->getName()],$item);
$this->PayGold[$p->getName()] = $this->PayGold[$p->getName()] -3;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fBow§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fBow§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §eGold §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §eЗолота §cдля покупки этого!");
}
}
}

if($item->getId()==262 && $this->Shop[$p->getName()]==1){
$item=Item::get(262,0,1);
if($this->PayGold[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayGold[$p->getName()] = $this->PayGold[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fEndless Arrow§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fEndless Arrow§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §eGold §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §eЗолота §cдля покупки этого!");
}
}
}

if($item->getId()==260 && $this->Shop[$p->getName()]==0){
$this->Shop[$p->getName()]=1;
$item=Item::get(260,0,1);$p->getInventory()->setItem(0,$item);
$item=Item::get(336,0,1);$p->getInventory()->setItem(1,$item);
$item=Item::get(320,0,1);$p->getInventory()->setItem(2,$item);
$item=Item::get(336,0,2);$p->getInventory()->setItem(3,$item);
$item=Item::get(354,0,1);$p->getInventory()->setItem(4,$item);
$item=Item::get(265,0,1);$p->getInventory()->setItem(5,$item);
$item=Item::get(35,14,1);$p->getInventory()->setItem(6,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(7,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(8,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(10,$item);
}

if($item->getId()==260 && $this->Shop[$p->getName()]==1){
$item=Item::get(260,0,1);
if($this->PayBro[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §cApple§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §cApple§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fBronze §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}
}

if($item->getId()==320 && $this->Shop[$p->getName()]==1){
$item=Item::get(320,0,1);
if($this->PayBro[$p->getName()]>=2){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -2;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §6Cooked Porkchop§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §6Cooked Porkchop§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fBronze §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fBronze §cдля покупки этого!");
}
}
}

if($item->getId()==354 && $this->Shop[$p->getName()]==1){
$item=Item::get(354,0,1);
if($this->PayIron[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fCake§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fCake§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==54 && $this->Shop[$p->getName()]==0){
$this->Shop[$p->getName()]=1;
$item=Item::get(54,0,1);$p->getInventory()->setItem(0,$item);
$item=Item::get(265,0,1);$p->getInventory()->setItem(1,$item);
$item=Item::get(35,14,1);$p->getInventory()->setItem(2,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(3,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(4,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(5,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(6,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(7,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(8,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(10,$item);
}

if($item->getId()==54 && $this->Shop[$p->getName()]==1){
$item=Item::get(54,0,1);
if($this->PayIron[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §6Chest§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §6Chest§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==373 && $this->Shop[$p->getName()]==0){
$this->Shop[$p->getName()]=1;
$item=Item::get(373,21,1);$p->getInventory()->setItem(0,$item);
$item=Item::get(265,0,3);$p->getInventory()->setItem(1,$item);
$item=Item::get(373,22,1);$p->getInventory()->setItem(2,$item);
$item=Item::get(265,0,5);$p->getInventory()->setItem(3,$item);
$item=Item::get(373,16,1);$p->getInventory()->setItem(4,$item);
$item=Item::get(265,0,7);$p->getInventory()->setItem(5,$item);
$item=Item::get(373,33,1);$p->getInventory()->setItem(6,$item);
$item=Item::get(266,0,7);$p->getInventory()->setItem(7,$item);
$item=Item::get(35,14,1);$p->getInventory()->setItem(8,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(10,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(11,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(12,$item);
}

if($item->getId()==373 && $item->getDamage()==21 && $this->Shop[$p->getName()]==1){
$item=Item::get(373,21,1);
if($this->PayIron[$p->getName()]>=3){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -3;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §cHealing Potion I§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §cHealing Potion I§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==373 && $item->getDamage()==22 && $this->Shop[$p->getName()]==1){
$item=Item::get(373,22,1);
if($this->PayIron[$p->getName()]>=5){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -5;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §cHealing Potion II§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §cHealing Potion II§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==373 && $item->getDamage()==16 && $this->Shop[$p->getName()]==1){
$item=Item::get(373,16,1);
if($this->PayIron[$p->getName()]>=7){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -7;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §bPotion of Swiftness II§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §bPotion of Swiftness II§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==373 && $item->getDamage()==33 && $this->Shop[$p->getName()]==1){
$item=Item::get(373,33,1);
if($this->PayGold[$p->getName()]>=7){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayGold[$p->getName()] -7;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §cPotion of Strength II§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §cPotion of Strength II§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §eGold §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §eЗолота §cдля покупки этого!");
}
}
}

if($item->getId()==46 && $this->Shop[$p->getName()]==0){
$this->Shop[$p->getName()]=1;
$item=Item::get(65,0,1);$p->getInventory()->setItem(0,$item);
$item=Item::get(336,0,1);$p->getInventory()->setItem(1,$item);
$item=Item::get(30,0,1);$p->getInventory()->setItem(2,$item);
$item=Item::get(336,0,16);$p->getInventory()->setItem(3,$item);
$item=Item::get(289,0,1);$p->getInventory()->setItem(4,$item);
$item=Item::get(265,0,3);$p->getInventory()->setItem(5,$item);
$item=Item::get(170,0,1);$p->getInventory()->setItem(6,$item);
$item=Item::get(265,0,5);$p->getInventory()->setItem(7,$item);
$item=Item::get(352,0,1);$p->getInventory()->setItem(8,$item);
$item=Item::get(266,0,3);$p->getInventory()->setItem(9,$item);
$item=Item::get(332,0,1);$p->getInventory()->setItem(10,$item);
$item=Item::get(266,0,9);$p->getInventory()->setItem(11,$item);
$item=Item::get(35,14,1);$p->getInventory()->setItem(12,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(13,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(14,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(15,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(16,$item);
}

if($item->getId()==65 && $this->Shop[$p->getName()]==1){
$item=Item::get(65,0,1);
if($this->PayBro[$p->getName()]>=1){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -1;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §6Ladder§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §6Ladder§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fBronze §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}
}

if($item->getId()==30 && $this->Shop[$p->getName()]==1){
$item=Item::get(30,0,1);
if($this->PayBro[$p->getName()]>=16){
array_push($this->Buyed[$p->getName()],$item);
$this->PayBro[$p->getName()] = $this->PayBro[$p->getName()] -16;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fWeb§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fWeb§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fBronze §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fБронзы §cдля покупки этого!");
}
}
}

if($item->getId()==289 && $this->Shop[$p->getName()]==1){
$item=Item::get(289,0,1);
if($this->PayIron[$p->getName()]>=3){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -3;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fReturn Powder§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fReturn Powder§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==171 && $this->Shop[$p->getName()]==1){
$item=Item::get(171,0,1);
if($this->PayIron[$p->getName()]>=5){
array_push($this->Buyed[$p->getName()],$item);
$this->PayIron[$p->getName()] = $this->PayIron[$p->getName()] -5;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §fBlinding Trap§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §fBlinding Trap§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §fIron §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §fЖелеза §cдля покупки этого!");
}
}
}

if($item->getId()==352 && $this->Shop[$p->getName()]==1){
$item=Item::get(352,0,1);
if($this->PayGold[$p->getName()]>=3){
array_push($this->Buyed[$p->getName()],$item);
$this->PayGold[$p->getName()] = $this->PayGold[$p->getName()] -3;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §cSaving Stick§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §cSaving Stick§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §eGold §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §eЗолота §cдля покупки этого!");
}
}
}

if($item->getId()==332 && $this->Shop[$p->getName()]==1){
$item=Item::get(332,0,1);
if($this->PayGold[$p->getName()]>=9){
array_push($this->Buyed[$p->getName()],$item);
$this->PayGold[$p->getName()] = $this->PayGold[$p->getName()] -9;
if($lang=="eng"){
$p->sendPopup("§bYou was bought §dEnder Pearl§f!");
}
if($lang=="rus"){
$p->sendPopup("§bВы купили §dEnder Pearl§f!");
}
}else{
if($lang=="eng"){
$p->sendPopup("§cYou haven't §eGold §cfor buy this!");
}
if($lang=="rus"){
$p->sendPopup("§cВы не имеете §eЗолота §cдля покупки этого!");
}
}
}




if($item->getId()==35){
if($this->Shop[$p->getName()]==0){
$p->getInventory()->clearAll();
foreach($this->Inv[$p->getName()] as $item){
$p->getInventory()->addItem($item);
}
$co=$this->ResBro[$p->getName()] - $this->PayBro[$p->getName()];
$p->getInventory()->removeItem(Item::get(336,0,$co));
$co=$this->ResIron[$p->getName()] - $this->PayIron[$p->getName()];
$p->getInventory()->removeItem(Item::get(265,0,$co));
$co=$this->ResGold[$p->getName()] - $this->PayGold[$p->getName()];
$p->getInventory()->removeItem(Item::get(266,0,$co));
foreach($this->Buyed[$p->getName()] as $item){
$p->getInventory()->addItem($item);
}
$this->Inv[$p->getName()]=array();
$this->Buyed[$p->getName()]=array();
$this->ResGold[$p->getName()]=0;
$this->ResIron[$p->getName()]=0;
$this->ResBro[$p->getName()]=0;
$this->ResGold[$p->getName()]=0;
$this->ResIron[$p->getName()]=0;
$this->ResBro[$p->getName()]=0;
$p->getInventory()->setArmorContents($this->Arm[$p->getName()]);
$this->Arm[$p->getName()]=array();
foreach($this->InShop as $num => $kek){
if($kek==$p->getName()){
array_splice($this->InShop,$num,1);
}
}
}
if($this->Shop[$p->getName()]==1){
$this->Shop[$p->getName()]=0;
/*$p->getInventory()->setHotbarSlotIndex(0, 35);
$p->getInventory()->setHotbarSlotIndex(1, 35);
$p->getInventory()->setHotbarSlotIndex(2, 35);
$p->getInventory()->setHotbarSlotIndex(3, 35);
$p->getInventory()->setHotbarSlotIndex(4, 35);
$p->getInventory()->setHotbarSlotIndex(5, 35);
$p->getInventory()->setHotbarSlotIndex(6, 35);
$p->getInventory()->setHotbarSlotIndex(7, 35);
$p->getInventory()->setHotbarSlotIndex(8, 35);
*/
$item=Item::get(24,0,1);
    		$p->getInventory()->setItem(0,$item);
$item=Item::get(278,0,1);
    		$p->getInventory()->setItem(1,$item);
$item=Item::get(311,0,1);
    		$p->getInventory()->setItem(2,$item);
$item=Item::get(276,0,1);
    		$p->getInventory()->setItem(3,$item);
$item=Item::get(261,0,1);
    		$p->getInventory()->setItem(4,$item);
$item=Item::get(260,0,1);
    		$p->getInventory()->setItem(5,$item);
$item=Item::get(54,0,1);
    		$p->getInventory()->setItem(6,$item);
$item=Item::get(373,0,1);
    		$p->getInventory()->setItem(7,$item);
$item=Item::get(46,0,1);
    		$p->getInventory()->setItem(8,$item);
$item=Item::get(35,14,1);
    		$p->getInventory()->setItem(9,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(10,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(11,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(12,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(13,$item);
$item=Item::get(0,0,0);$p->getInventory()->setItem(14,$item);
}
}

}
}

}
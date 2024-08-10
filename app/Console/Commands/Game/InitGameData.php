<?php

namespace App\Console\Commands\Game;

use App\Models\Wormix\DailyBonus;
use App\Models\Wormix\Weapon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InitGameData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add base from game to DB';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Parsing from weapons.json');
        $this->info('Parsing strings from items_messages_ru.xml');
        $this->info('Parsing hats from hats.json');
        $this->info('Parsing gifts from gifts.json');

        $weapons_path = resource_path('game/weapons.json');
        $items_messages_path = resource_path('game/items.messages_ru.xml');
        $hats_path = resource_path('game/hats.json');
        $gifts_path = resource_path('game/gifts.json');

        if(!File::exists($weapons_path)){
            $this->error("Can't find weapons.json in resources");
            return;
        }
        if(!File::exists($items_messages_path)){
            $this->error("Can't find items.messages_ru.xml in resources");
            return;
        }

        if(!File::exists($hats_path)){
            $this->error("Can't find hats.json in resources");
            return;
        }

        if(!File::exists($gifts_path)){
            $this->error("Can't find hats.json in resources");
            return;
        }

        if(!File::exists($gifts_path)){
            $this->error("Can't find gifts.json in resources");
            return;
        }

        $weapons_array = json_decode(file_get_contents($weapons_path), true);
        $hats_array = json_decode(file_get_contents($hats_path), true);
        $messages_array = simplexml_load_file($items_messages_path);
        $gifts_array = json_decode(file_get_contents($gifts_path), true);

        $messages_object = [];

        foreach($messages_array->children() as $message){
            $messages_object[(string)$message['name']] = (string)$message['value'];
        }

        foreach($weapons_array as $weapon){
            if(array_key_exists('name', $weapon)) {
                Weapon::insert(
                    [
                        'id' => $weapon['id'],
                        'name' => @$messages_object[$weapon['name']] ?? $weapon['name'],
                        'hide_in_shop' => array_key_exists('hideInShop', $weapon),
                        'is_starter' => false,
                        'price' => @$weapon['price'] ?? 0,
                        'real_price' => @$weapon['realprice'] ?? 0,
                        'required_friends' => @$weapon['requiredFriends'] ?? 0,
                        'required_level' => @$weapon['requiredLevel'] ?? 0,
                        'infinity' => is_bool(@$weapon['infinite']) && @$weapon['infinite']
                    ]
                );
                $this->info($weapon['name']." saved!");
            }elseif(array_key_exists('refId', $weapon)){
                Weapon::insert(
                    [
                        'id' => $weapon['id'],
                        'ref_id' =>  $weapon['refId'],
                        'hide_in_shop' => @$weapon['hideInShop'] ?? true,
                        'price' => $weapon['price'],
                        'required_friends' => @$weapon['requiredFriends'] ?? 0,
                        'required_level' => @$weapon['requiredLevel'] ?? 0,
                    ]
                );
                $this->info('Ref '.$weapon['refId']." saved!");
            }
        }

        foreach($hats_array as $hat){
            Weapon::insert(
                [
                    'id' => $hat['id'],
                    'name' => @$messages_object[$hat['name']] ?? $hat['name'],
                    'hide_in_shop' => array_key_exists('hideInShop', $hat),
                    'is_starter' => false,
                    'price' => @$hat['price'] ?? 0,
                    'real_price' => @$hat['realprice'] ?? 0,
                    'required_rating' => @$hat['requiredRating'] ?? 0,
                    'required_level' => @$hat['requiredLevel'] ?? 0,
                    'infinity' => is_bool(@$hat['infinite']) && @$hat['infinite'],
                    'one_day' => is_bool(@$hat['oneDay']) && @$hat['infinite']
                ]
            );
            $this->info("Hat ".$hat['name']." saved!");
        }

        foreach ($gifts_array as $gift) {
            DailyBonus::insert([
                'login_sequence' => $gift['sequence'],
                'bonus_type' => $gift['type'],
                'bonus_value' => $gift['value'],
                'random_gift' => $gift['random']
            ]);
            $this->info('Gift added for sequence '.$gift['sequence']);
        }
    }
}

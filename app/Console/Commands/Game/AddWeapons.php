<?php

namespace App\Console\Commands\Game;

use App\Models\Wormix\Weapon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddWeapons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:add-weapons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add weapons from game to DB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Parsing from weapons.json and items_messages_ru.xml');

        $weapons_path = resource_path('game/weapons.json');
        $items_messages_path = resource_path('game/items.messages_ru.xml');

        if(!File::exists($weapons_path)){
            $this->error("Can't find weapons.json in resources");
            return;
        }
        if(!File::exists($items_messages_path)){
            $this->error("Can't find items.messages_ru.xml in resources");
            return;
        }

        $weapons_array = json_decode(file_get_contents($weapons_path), true);
        $messages_array = simplexml_load_file($items_messages_path);
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
    }
}

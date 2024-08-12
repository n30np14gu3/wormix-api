# WORMIX LOCAL SERVER API

## Install
```shell
composer require
php artisan app:key
php artisan migrate
php artisan game:init
```

## Files in resources/game
* [gifts.json](./resources/game/gifts.json) - login sequence gifts
* [hats.json ](./resources/game/gifts.json)- array of game hats
* [items.messages_ru.xml](./resources/game/items.messages_ru.xml) - canonical names of items
* [level_awards.json](./resources/game/level_awards.json) - awards for level up
* [missions_awards.json](./resources/game/missions_awards.json) - awards for mission completed
* [names.json](./resources/game/names.json) - bot names array for random generation
* [races.json](./resources/game/races.json) - game races config
* [recipes.json](./resources/game/recipes.json) - game craft recipes config
* [weapons.json](./resources/game/weapons.json) - game weapons config
* [weapons_start.json](./resources/game/weapons_start.json) - starter weapons ids

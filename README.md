# home-poker
Home Poker - Coding Practical

### Requirements
* [Composer](https://getcomposer.org/) - For PSR-4 autoloading
* PHP 5.6+

### Summary
Game.php contains the main execution methods. Deck, Card and Player classes are created and used.

### Installation
CD into the folder where you cloned the repo and run:
```
$ /path/to/composer install
```
### Usage
Execute the command below from within the home-poker folder. Provide number of players as argument. The minimum amount of players is 1, maximum is 5.
```
$ /path/to/composer run-script game -- 5
```

### TODO
* Allow a "wheel" straight from A-5
* Add support for kickers. As of now hands CAN end in a tie, which is not expected behavior.

<?php

namespace TomPloskina\HomePoker;

use Composer\Script\Event;

/**
 * Class Game
 * @package TomPloskina\HomePoker
 */
final class Game
{
    /**
     * @param Event $event
     * @throws InvalidPlayerCountException
     */
    public static function play(Event $event)
    {
        $args = $event->getArguments();

        $playerCount = intval($args[0]);
        if ($playerCount < 1 || $playerCount > 5) {
            throw new InvalidPlayerCountException("You must enter a player count between 1 and 5");
        }

        echo "Dealing to {$playerCount} players...\n";

        $deck = new Deck();
        $deck->create();

        $players = [];
        for ($i = 1; $i <= $playerCount; $i++) {
            echo "Player {$i}\n";
            $player = new Player($i);
            for ($x = 1; $x <= 2; $x++) {
                $card = $deck->dealCard();
                $player->addCard($card);
                echo "Hole Card {$x}: " . $card . "\n";
            }
            $players[$i] = $player;
        }

        $board = "Board: ";
        $boardCards = [];
        for ($b = 1; $b <= 5; $b++) {
            $card = $deck->dealCard();
            $boardCards[] = $card;
            $board .= $card . " / ";
        }
        echo $board . "\n";

        $ranking = 0;
        $lastRanking = 0;
        $winningPlayer = null;
        foreach ($players as $player) {
            $finalPlayer = self::checkPlayerHand($player, $boardCards);
            $ranking = $finalPlayer->getRankingInt();
            echo $finalPlayer . "\n";

            if ($ranking > $lastRanking) {
                $winningPlayer = $player;
            }
            $lastRanking = $ranking;
        }

        if ($winningPlayer !== null) {
            echo "\n *** WINNER IS PLAYER {$winningPlayer->getPlayerNo()} with a {$winningPlayer->getHandRank()} ***\n";
        } else {
            echo "\n *** SOMETHING WENT WRONG, CASINO SCOOPS THE POT, HOUSE ALWAYS WINS! ***\n";
        }
    }

    /**
     * @param Player $player
     * @param $boardCards
     * @return Player
     */
    public static function checkPlayerHand(Player $player, $boardCards)
    {
        $madeHand = $player->getRoyalFlush($boardCards);
        if ($madeHand != null) {
            $player->setHandRank('ROYAL_FLUSH');

            return $player;
        }

        $madeHand = $player->getStraightFlush($boardCards);
        if ($madeHand != null) {
            $player->setHandRank('STRAIGHT_FLUSH');

            return $player;
        }

        $madeHand = $player->getFourOfAKind($boardCards);
        if ($madeHand != null) {
            $player->setHandRank('FOUR_OF_A_KIND');

            return $player;
        }

        $madeHand = $player->getFullHouse($boardCards);
        if ($madeHand != null) {
            $player->setHandRank('FULL_HOUSE');

            return $player;
        }

        $madeHand = $player->getFlush($boardCards);
        if ($madeHand != null) {
            $player->setHandRank('FLUSH');

            return $player;
        }

        $madeHand = $player->getStraight($boardCards);
        if ($madeHand != null) {
            $player->setHandRank('STRAIGHT');

            return $player;
        }


        $madeHand = $player->getThreeOfAKind($boardCards);
        if ($madeHand != null) {
            $player->setHandRank('THREE_OF_A_KIND');

            return $player;
        }

        $madeHand = $player->getTwoPair($boardCards);
        if ($madeHand != null) {
            $player->setHandRank('TWO_PAIR');

            return $player;
        }

        $madeHand = $player->getOnePair($boardCards);
        if ($madeHand != null) {
            $player->setHandRank('ONE_PAIR');

            return $player;
        }

        $madeHand = $player->getHighCard($boardCards);
        $player->setHandRank('HIGH_CARD');

        return $player;
    }
}
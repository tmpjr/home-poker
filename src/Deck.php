<?php

namespace TomPloskina\HomePoker;

/**
 * Class Deck
 * @package TomPloskina\HomePoker
 */
class Deck
{
    /**
     * @var array
     */
    private $cards = [];

    /**
     * Create a Deck by iterating over ranks/suits
     */
    public function create()
    {
        foreach (Card::SUITS as $suit) {
            foreach (Card::RANKS as $rank) {
                $this->cards[] = new Card($suit, $rank);
            }
        }
    }

    /**
     * @return Card
     */
    public function dealCard()
    {
        $key = array_rand($this->cards);
        $card = $this->cards[$key];
        unset($this->cards[$key]);

        return $card;
    }
}
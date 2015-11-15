<?php

namespace TomPloskina\HomePoker;

/**
 * Class Card
 * @package TomPloskina\HomePoker
 */
class Card
{
    /**
     * const Suits
     */
    const SUITS = [
        'C',
        'D',
        'H',
        'S',
    ];

    /**
     * Ranks
     */
    const RANKS = [
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        'J',
        'Q',
        'K',
        'A',
    ];

    /**
     * @var string
     */
    private $suit;

    /**
     * @var string
     */
    private $rank;

    /**
     * Card constructor.
     * @param $suit
     * @param $rank
     */
    public function __construct($suit, $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getRank() . "-" . $this->getSuit();
    }

    /**
     * @return mixed
     */
    public function getRankInt()
    {
        return array_search($this->getRank(), self::RANKS);
    }

    /**
     * @return string
     */
    public function getSuit()
    {
        return $this->suit;
    }

    /**
     * @param string $suit
     */
    public function setSuit($suit)
    {
        $this->suit = $suit;

        return $this;
    }

    /**
     * @return string
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param string $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }
}
<?php

namespace TomPloskina\HomePoker;

class Player
{
    /**
     * @var array
     */
    private $cards = [];

    /**
     * @var string Current Hand Rank
     */
    private $handRank;

    /**
     * @var const Ordered Ranking
     */
    const RANKING = [
        'HIGH_CARD',
        'ONE_PAIR',
        'TWO_PAIR',
        'THREE_OF_A_KIND',
        'STRAIGHT',
        'FLUSH',
        'FULL_HOUSE',
        'FOUR_OF_A_KIND',
        'STRAIGHT_FLUSH',
        'ROYAL_FLUSH'
    ];

    /**
     * @var int
     */
    private $playerNo;

    /**
     * Player constructor.
     * @param $playerNo
     */
    public function __construct($playerNo)
    {
        $this->playerNo = $playerNo;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "Player " . $this->playerNo . " turns over " . $this->getHandRank() . " [{$this->getRankingInt()}]";
    }

    /**
     * @return mixed
     */
    public function getRankingInt()
    {
        return array_search($this->getHandRank(), self::RANKING);
    }

    /**
     * @return mixed
     */
    public function getPlayerNo()
    {
        return $this->playerNo;
    }

    /**
     * @param mixed $playerNo
     */
    public function setPlayerNo($playerNo)
    {
        $this->playerNo = $playerNo;
    }

    /**
     * @param Card $card
     * @return $this
     */
    public function addCard(Card $card)
    {
        $this->cards[] = $card;

        return $this;
    }

    /**
     * @return array
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @return mixed
     */
    public function getHandRank()
    {
        return $this->handRank;
    }

    /**
     * @param mixed $handRank
     */
    public function setHandRank($handRank)
    {
        $this->handRank = $handRank;
    }

    /**
     * @param $boardCards
     * @return array
     */
    public function getMergedCards($boardCards)
    {
        return array_merge($this->cards, $boardCards);
    }

    /**
     * @param $boardCards
     * @return mixed
     */
    public function getHighCard($boardCards)
    {
        $cards = $this->getMergedCards($boardCards);

        $highCard = $cards[0];
        foreach ($cards as $card) {
            if ($card->getRankInt() > $highCard->getRankInt()) {
                $highCard = $card;
            }
        }

        return $highCard;
    }

    /**
     * @param $cards
     * @param $size
     * @return array|null
     */
    public function checkPair($cards, $size)
    {
        $checkedPair = [];
        foreach ($cards as $card1) {
            $checkedPair[] = $card1;
            foreach ($cards as $card2) {
                if ($card1 !== $card2 && $card1->getRankInt() === $card2->getRankInt()) {
                    $checkedPair[] = $card2;
                }
            }

            if (count($checkedPair) === $size) {
                return $checkedPair;
            }

            $checkedPair = [];
        }

        return null;
    }

    /**
     * @param $boardCards
     * @return array|null
     */
    public function getOnePair($boardCards)
    {
        $cards = $this->getMergedCards($boardCards);

        return $this->checkPair($cards, 2);
    }

    /**
     * @param $boardCards
     * @return array|null
     */
    public function getTwoPair($boardCards)
    {
        $cards = $this->getMergedCards($boardCards);
        $twoPair1 = $this->checkPair($cards, 2);
        if ($twoPair1 !== null) {
            foreach ($twoPair1 as $pair) {
                $key = array_search($pair, $cards);
                unset($cards[$key]);
            }

            $twoPair2 = $this->checkPair($cards, 2);
            if ($twoPair2 !== null) {
                array_merge($twoPair1, $twoPair2);
                return $twoPair1;
            }
        }

        return null;
    }

    /**
     * @param $boardCards
     * @return array|null
     */
    public function getFourOfAKind($boardCards)
    {
        $cards = $this->getMergedCards($boardCards);
        return $this->checkPair($cards, 4);
    }

    /**
     * @param $boardCards
     * @return array|null
     */
    public function getThreeOfAKind($boardCards)
    {
        $cards = $this->getMergedCards($boardCards);
        return $this->checkPair($cards, 3);
    }

    /**
     * @param $boardCards
     * @return array
     */
    public function getOrderedCards($boardCards)
    {
        $cards = $this->getMergedCards($boardCards);

        usort($cards, function($a, $b) {
            return $a->getRankInt() < $b->getRankInt() ? -1 : 1;
        });

        return $cards;
    }

    /**
     * @param $boardCards
     * @param bool|false $compareSuit
     * @return array|null
     */
    public function getSequence($boardCards, $compareSuit = false)
    {
        $orderedCards = $this->getOrderedCards($boardCards);
        $sequence = [];
        $lastCard = null;
        foreach ($orderedCards as $card) {
            if ($lastCard !== null) {
                if ($card->getRankInt() - $lastCard->getRankInt() === 1) {
                    if (!$compareSuit || $lastCard->getSuit() === $card->getSuit()) {
                        if (count($sequence) === 0) {
                            $sequence[] = $lastCard;
                        }

                        $sequence[] = $card;
                    }
                } else {
                    if (count($sequence) === 5) {
                        return $sequence;
                    }
                    $sequence = [];
                }
            }

            $lastCard = $card;
        }

        return (count($sequence) === 5) ? $sequence : null;
    }

    /**
     * @param $boardCards
     * @return array|null
     */
    public function getStraight($boardCards)
    {
        return $this->getSequence($boardCards, false);
    }

    /**
     * @param $boardCards
     * @return array|null
     */
    public function getStraightFlush($boardCards)
    {
        return $this->getSequence($boardCards, true);
    }

    /**
     * @param $boardCards
     * @return array|null
     */
    public function getFullHouse($boardCards)
    {
        $cards = $this->getMergedCards($boardCards);
        $threes = $this->checkPair($cards, 3);

        if ($threes !== null) {
            foreach ($threes as $three) {
                $key = array_search($three, $cards);
                unset($cards[$key]);
            }

            $twos = $this->checkPair($cards, 2);
            if ($twos !== null) {
                array_merge($threes, $twos);
                return $threes;
            }
        }

        return null;
    }

    /**
     * @param $boardCards
     * @return array|null
     */
    public function getFlush($boardCards)
    {
        $cards = $this->getMergedCards($boardCards);
        $flushCards = [];

        foreach ($cards as $card1) {
            foreach ($cards as $card2) {
                if ($card1->getSuit() === $card2->getSuit()) {
                    if (!in_array($card1, $flushCards)) {
                        $flushCards[] = $card1;
                    }
                    if (!in_array($card2, $flushCards)) {
                        $flushCards[] = $card2;
                    }
                }
            }
            if (count($flushCards) === 5) {
                return $flushCards;
            }
            $flushCards = [];
        }

        return null;
    }

    /**
     * @param $boardCards
     * @return array|null
     */
    public function getRoyalFlush($boardCards)
    {
        $cards = $this->getMergedCards($boardCards);
        $hand = [];

        $lastSuit = null;
        foreach ($cards as $card) {
            // If face card
            if ($card->getRankInt() > 7) {
                if ($lastSuit === null) {
                    $lastSuit = $card->getSuit();
                }
                if ($card->getSuit() === $lastSuit) {
                    $hand[] = $card;
                }
                $lastSuit = $card->getSuit();
            }
        }

        if (count($hand) === 5) {
            return $hand;
        }

        return null;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 6/9/17
 * Time: 2:39 PM
 */

namespace AdminBundle\Service;


use DateTime;
use DateTimeZone;
use WebBundle\Entity\Room;

class CalendarBuilder
{
    private $room;

    private $date;

    public function __construct(Room $room)
    {
        $this->room = $room;
        $this->date = new DateTime();
        $this->date->setTimezone(new DateTimeZone('Europe/Kiev'));
    }

    /**
     * Time will takes first place in hierarchy
     * First will be shown time of game,
     * every time contains two weeks of dates,
     * every date contains all prices of game
     * @return array
     */
    public function getTimeDrivenCalendar() {
        $times = [];
        foreach ($this->room->getBlanks() as $blank) {
            $prices = [];
            foreach ($blank->getPrices() as $price) {
                $prices[$price->getDayOfWeek()][] = [
                    'dayOfWeek' => $price->getDayOfWeek(),
                    'players' => $price->getPlayers(),
                    'price' => $price->getPrice()
                ];
            }
            $datetime = [];
            for ($i = 0; $i < 14; $i++) {
                if ($i > 0) {
                    $this->date->modify('+1 day');
                }
                $datetime[$this->date->format('d-m-Y')] = $prices[strtolower($this->date->format('l'))];
            }
            $times[$blank->getTime()->format('H:i')] = $datetime;
        }
        return json_encode($times);
    }

    /**
     * Response contain array of dates and games,
     * every game contains objects with time, price, and busy state
     * @return array
     */
    public function getDayDrivenCalendar() {
        $days = [];
        for ($i = 0; $i < 14; $i++) {
            if ($i > 0) {
                $this->date->modify('+1 day');
            }
            $games = [];
            foreach ($this->room->getBlanks() as $blank) {
                $prices = [];
                foreach ($blank->getPrices() as $price) {
                    $prices[$price->getDayOfWeek()][] = $price;
                }
                $games[] = [
                    'timeGame' => $blank->getTime()->format('H:i:s'),
                    'cost' => $prices[strtolower($this->date->format('l'))][0]->getPrice(),
                    'busy' => false
                ];
            }
            $days[] = [
                'date' => $this->date->format('d-m-Y'),
                'games' => $games
            ];
        }
        return json_encode($days);
    }
}
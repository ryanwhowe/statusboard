<?php

namespace Statusboard\ControllerHelpers;

use AppBundle\Entity\Calendar;
use AppBundle\Repository\CalendarRepository;
use DateTime;

class CalendarHelper {

    public static function getEventTypes() {
        /* the order of the response is the same as the order of this array */
        return [
            Calendar::TYPE_COMPANY_HOLIDAY => 'Company Holiday',
            Calendar::TYPE_PAY_DATE        => Calendar::translateTypeDescription(new Calendar(['eventDate' => new DateTime(), 'type' => Calendar::TYPE_PAY_DATE])),
            Calendar::TYPE_PTO             => 'PTO',
        ];
    }

    /**
     * @param CalendarRepository $calendarRepository
     *
     * @return array
     * @throws \Exception
     */
    public static function getUpcomingEvents(CalendarRepository $calendarRepository): array {
        $return = [];
        foreach (self::getEventTypes() as $eventType => $eventName) {
            $return [] = self::getUpcomingEvent($calendarRepository, $eventType, $eventName);
        }
        return $return;
    }

    /**
     * @param CalendarRepository $calendarRepository
     * @param int                $eventType
     * @param string             $eventName
     *
     * @return array
     */
    private static function getUpcomingEvent(CalendarRepository $calendarRepository, int $eventType, string $eventName) {
        /**
         * @var Calendar $calendar
         */
        $calendars = $calendarRepository->getNextEvent($eventType);

        foreach ($calendars as $calendar) {
            $days_until = date_diff(new DateTime('now'),
                $calendar->getEventDate());

            return [
                'display_name' => $eventName,
                'date'         => $calendar->getEventDate()->format('Y-m-d'),
                'days'         => $days_until->format('%a') + 1,
            ];
        }
        if (!count($calendars)) {
            return [
                'display_name' => $eventName,
                'date'         => null,
                'days'         => null,
            ];
        }
        return [];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getCalendarData($calendars) {
        $calendar_data = [];

        /** @var Calendar $calendar */
        foreach ($calendars as $calendar) {
            $event_date = $calendar->getEventDate()->format('Y-m-d');
            if (isset($calendar_data[$event_date])) {
                $calendar_data[$event_date]['events'][] = $calendar->toArray();
            } else {
                $calendar_data[$event_date] = ['events' => [$calendar->toArray()]];
            }
        }
        return $calendar_data;
    }

    /**
     * Get the PTO data relative to the passed DateTime for the current calendar year.
     *
     * @param CalendarRepository $calendarRepository
     * @param DateTime           $dateTime
     *
     * @return int[]
     * @throws \Exception
     */
    public static function getPto(CalendarRepository $calendarRepository, DateTime $dateTime){
        $searchYear = $dateTime->format('Y');
        $events = $calendarRepository->getAllEventsInYear(Calendar::TYPE_PTO, $searchYear);
        $return = ['taken'=>0, 'scheduled'=>0];
        $last = $base = new DateTime('first day of January ' . $searchYear);

        /** @var Calendar $calendar */
        foreach($events as $calendar){
            if($calendar->getEventDate() <= $dateTime){
                $return['taken'] += 1;
            } else {
                $return['scheduled'] += 1;
            }
            if($calendar->getEventDate() > $last){
                $last = $calendar->getEventDate();
            }
        }
        $return['last'] = ($last === $base) ? null : $last->format('Y-m-d');
        return $return;
    }
}
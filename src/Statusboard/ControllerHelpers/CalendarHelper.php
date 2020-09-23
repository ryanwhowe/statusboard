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
}
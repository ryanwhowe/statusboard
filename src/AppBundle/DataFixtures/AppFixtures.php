<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Server;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture {

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager) {
        # load server data
        $servers = [
            ['vmbox',0],
            ['osmc',0],
            ['picam',1],
            ['deskPi',1],
            ['nucBox',0],
            ['HoweCamper',1],
            ['audioPi',0],
            ['cluster0',1],
            ['cluster1',1],
            ['cluster2',1],
            ['cluster3',1],
            ['cluster4',1]
        ];
        foreach ($servers as $server_data) {
            $server = new Server();
            $server->setName($server_data[0]);
            $server->setIsDisabled($server_data[1]);
            $manager->persist($server);
        }
        $manager->flush();

        $calendar_events = [
            [1,'2019-01-01',''],
            [4,'2019-01-01','New Year\'s Day'],
            [99,'2019-01-04',''],
            [99,'2019-01-18',''],
            [4,'2019-01-21','Martin Luther King Jr. Day'],
            [3,'2019-01-29',''],
            [3,'2019-01-30',''],
            [99,'2019-02-01',''],
            [99,'2019-02-15',''],
            [1,'2019-02-18',''],
            [4,'2019-02-18','Presidents\' Day'],
            [3,'2019-02-25',''],
            [3,'2019-02-26',''],
            [3,'2019-02-27',''],
            [99,'2019-03-01',''],
            [1,'2019-05-27',''],
            [4,'2019-05-27','Memorial Day'],
            [99,'2019-05-30',''],
            [99,'2019-06-14',''],
            [99,'2019-06-28',''],
            [1,'2019-07-04',''],
            [4,'2019-07-04','Independence Day'],
            [1,'2019-07-05',''],
            [99,'2019-07-12',''],
            [99,'2019-07-30',''],
            [99,'2019-08-14',''],
            [99,'2019-08-30',''],
            [1,'2019-09-02',''],
            [4,'2019-09-02','Labor Day'],
            [99,'2019-09-13',''],
            [2,'2019-09-20',''],
            [2,'2019-09-23',''],
            [99,'2019-09-27',''],
            [99,'2019-10-11',''],
            [4,'2019-10-14','Columbus Day'],
            [2,'2019-10-23',''],
            [2,'2019-10-24',''],
            [2,'2019-10-25',''],
            [99,'2019-10-30',''],
            [4,'2019-11-11','Veterans Day'],
            [99,'2019-11-14',''],
            [99,'2019-11-27',''],
            [1,'2019-11-28',''],
            [4,'2019-11-28','Thanksgiving Day'],
            [1,'2019-11-29',''],
            [99,'2019-12-13',''],
            [2,'2019-12-23',''],
            [1,'2019-12-24',''],
            [4,'2019-12-24','Christmas Eve'],
            [1,'2019-12-25',''],
            [4,'2019-12-25','Christmas Day'],
            [2,'2019-12-30',''],
            [99,'2019-12-30',''],
            [1,'2020-01-01',''],
            [4,'2020-01-01','New Year\'s Day'],
            [2,'2020-01-09',''],
            [99,'2020-01-14',''],
            [1,'2020-01-20',''],
            [4,'2020-01-20','Martin Luther King Jr. Day'],
            [2,'2020-01-21',''],
            [2,'2020-01-22',''],
            [99,'2020-01-30',''],
            [2,'2020-02-10',''],
            [99,'2020-02-14',''],
            [1,'2020-02-17',''],
            [4,'2020-02-17','Presidents\' Day'],
            [99,'2020-02-28',''],
            [99,'2020-03-13',''],
            [99,'2020-03-30',''],
            [2,'2020-03-31',''],
            [2,'2020-04-06',''],
            [2,'2020-04-07',''],
            [99,'2020-04-14',''],
            [99,'2020-04-29',''],
            [99,'2020-05-14',''],
            [2,'2020-05-22',''],
            [1,'2020-05-25',''],
            [4,'2020-05-25','Memorial Day'],
            [99,'2020-05-29',''],
            [99,'2020-06-12',''],
            [99,'2020-06-29',''],
            [1,'2020-07-03',''],
            [4,'2020-07-03','Independence Day observed'],
            [4,'2020-07-04','Independence Day'],
            [99,'2020-07-14',''],
            [2,'2020-07-17',''],
            [99,'2020-07-30',''],
            [2,'2020-08-10',''],
            [2,'2020-08-11',''],
            [2,'2020-08-12',''],
            [2,'2020-08-13',''],
            [2,'2020-08-14',''],
            [99,'2020-08-14',''],
            [2,'2020-08-17',''],
            [99,'2020-08-28',''],
            [1,'2020-09-07',''],
            [4,'2020-09-07','Labor Day'],
            [99,'2020-09-14',''],
            [2,'2020-09-25',''],
            [99,'2020-09-29',''],
            [4,'2020-10-12','Columbus Day'],
            [99,'2020-10-14',''],
            [99,'2020-10-30',''],
            [4,'2020-11-11','Veterans Day'],
            [99,'2020-11-13',''],
            [99,'2020-11-25',''],
            [1,'2020-11-26',''],
            [4,'2020-11-26','Thanksgiving Day'],
            [1,'2020-11-27',''],
            [99,'2020-12-14',''],
            [1,'2020-12-24',''],
            [1,'2020-12-25',''],
            [4,'2020-12-25','Christmas Day'],
            [99,'2020-12-30',''],
            [4,'2021-01-01','New Year\'s Day'],
            [4,'2021-01-18','Martin Luther King Jr. Day'],
            [4,'2021-02-15','Presidents\' Day'],
            [4,'2021-05-31','Memorial Day'],
            [4,'2021-07-04','Independence Day'],
            [4,'2021-07-05','Independence Day observed'],
            [4,'2021-09-06','Labor Day'],
            [4,'2021-10-11','Columbus Day'],
            [4,'2021-11-11','Veterans Day'],
            [4,'2021-11-25','Thanksgiving Day'],
            [4,'2021-12-24','Christmas Day observed'],
            [4,'2021-12-25','Christmas Day'],
            [4,'2021-12-31','New Year\'s Day observed']
        ];

        foreach ($calendar_events as $calendar_event) {
            $calendar = new Calendar();
            $calendar->setType($calendar_event[0]);
            $calendar->setEventDate(new DateTime($calendar_event[1]));
            if ($calendar_event[2] !== ''){
                $calendar->setDescription($calendar_event[2]);
            }
            $manager->persist($calendar);
        }

        $manager->flush();
    }
}
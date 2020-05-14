<?php declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Repository\CalendarRepository;
use AppBundle\Repository\ServerRepository;
use \DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Server;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Statusboard\Utility\Environment;
use Statusboard\Utility\PayDate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 * @Route("/admin")
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:Admin:admin.html.twig');
    }

    /**
     * @Route("/server", name="admin_test_server")
     */
    public function serverAction(Request $request)
    {
        /**
         * @var ServerRepository $serverRepository
         */
        $serverRepository = $this->getDoctrine()->getRepository(Server::class);
        $servers = $serverRepository->findAll();
        return $this->render('AppBundle:Admin:server.html.twig',[
            'servers' => $servers,
            'baseUrl' => $this->container->get('router')->getContext()->getBaseUrl() . "/"
        ]);
    }

    /**
     * @Route("/mbta", name="admin_test_mbta")
     */
    public function mbtaAction(Request $request){
        return $this->render('AppBundle:Admin:mbta.html.twig',[
            'baseUrl' => $this->container->get('router')->getContext()->getBaseUrl() . "/"
        ]);
    }

    /**
     * @Route("/weather", name="admin_test_weather")
     */
    public function weatherAction(Request $request){
        return $this->render('AppBundle:Admin:weather.html.twig',[
            'baseUrl' => $this->container->get('router')->getContext()->getBaseUrl() . "/"
        ]);
    }

    /**
     * @Route("/testcalendar", name="admin_test_calendar")
     * @throws \Exception
     */
    public function calendarAction(Request $request){
        $arrival_time = $request->cookies->get('time_sheet_time', '09:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        /**
         * @var CalendarRepository $calendarRepository;
         */
        $calendarRepository = $this->getDoctrine()->getRepository(Calendar::class);

        $nextEvents = DefaultController::formatNextEvents($calendarRepository);

        $calendarEvents = json_encode(DefaultController::getCalendarData($this->getDoctrine()->getRepository(Calendar::class)->findAll()));

        return $this->render('AppBundle:Admin:calendar.html.twig', [
            'calendarJson' => $calendarEvents,
            'arrival_time' => $arrival_time,
            'add_time'     => $add_time,
            'events'       => $nextEvents,
            'baseUrl'      => $this->container->get('router')->getContext()->getBaseUrl() . "/"
        ]);
    }

    /**
     * @Route("/clock", name="admin_test_clock")
     * @param Request $request
     * @return Response
     */
    public function clockAction(Request $request){
        $arrival_time = $request->cookies->get('time_sheet_time', '09:00');
        $add_time = $request->cookies->get('time_sheet_add_time', 0);
        return $this->render('AppBundle:Admin:clock.html.twig',[
            'arrival_time' => $arrival_time,
            'add_time'     => $add_time,
        ]);
    }

    /**
     * @Route("/environment", name="admin_test_environment")
     * @param Request $request
     * @return Response
     */
    public function environmentAction(Request $request){
        $type_test = $request->request->get('type_test');
        $env = Environment::getType($type_test);
        $envs = [
            'ENV_PRODUCTION' => ($env === Environment::ENV_PRODUCTION),
            'ENV_DOCKER_PRODUCTION' => ($env === Environment::ENV_DOCKER_PRODUCTION),
            'ENV_DOCKER_DEV' => ($env === Environment::ENV_DOCKER_DEV),
            'ENV_DEV' => ($env === Environment::ENV_DEV),
            'ENV_LOCAL' => ($env === Environment::ENV_LOCAL),
            'ENV_TEST' => ($env === Environment::ENV_TEST),
            'ENV_DOCKER_TEST' => ($env === Environment::ENV_DOCKER_TEST),
            'ENV_AWS_PRODUCTION' => ($env === Environment::ENV_AWS_PRODUCTION),
            'ENV_AWS_STAGING' => ($env === Environment::ENV_AWS_STAGING),
            'ENV_AWS_DEV' => ($env === Environment::ENV_AWS_DEV),
            'ENV_AWS_TEST' => ($env === Environment::ENV_AWS_TEST),
            'isLocal()' => Environment::isLocal(),
            'isLocalDevelopment()' => Environment::isLocalDevelopment(),
            'isDevelopment()' => Environment::isDevelopment(),
            'isTesting()' => Environment::isTesting(),
            'isAwsTesting()' => Environment::isAwsTesting(),

        ];
        return $this->render('AppBundle:Admin:environment.html.twig', [
            'environments' => $envs,
        ]);
    }
}

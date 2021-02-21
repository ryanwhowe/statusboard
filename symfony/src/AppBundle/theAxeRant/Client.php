<?php
/**
 * This file contains the code for the Client class
 *
 * @author Ryan Howe
 * @since  2017-08-14
 */

namespace AppBundle\theAxeRant;

/**
 * Class HomeMetaClient
 *
 * @author Ryan Howe
 * @since  2017-08-14
 */
final class Client  {

    /**
     * @var string Authorization key for theAxeRant
     */
    private $auth = \null;

    /**
     * @var string the base url for theAxeRant api service
     */
    private $base_url = \null;

    /**
     * @var string The grouping that will be utilized by the class instance
     */
    private $grouping = \null;

    /**
     * Return the grouping context that the class was instantiated under.
     * @return string
     */
    public function getGrouping(){
        return $this->grouping;
    }

    /**
     * HomeMetaClient constructor.
     *
     * @param string $auth The authorization string to be used for reading
     * @param string $base_url The base url for the api requests to be sent to
     * @param string $grouping The grouping that will be utilized by the class instance
     */
    public function __construct($auth, $base_url, $grouping)
    {

        $this->auth = $auth;
        $this->base_url = $base_url;
        $this->grouping = $grouping;
    }

    /**
     * Create a new instance of the HomeMetaClient class instantiated with the passed grouping value
     *
     * @param string $auth The authorization string to be used for reading
     * @param string $base_url The base url for the api requests to be sent to
     * @param string $grouping The grouping that will be utilized by the class instance
     * @return Client
     */
    public static function create($auth, $base_url, $grouping)
    {
        return new self($auth, $base_url, $grouping);
    }

    /**
     * Query the ipCheck data for the instantiated grouping
     *
     * @return array
     */
    public function ipCheck()
    {
        $heartbeat = $this->query('get', 'heartbeat');
        $internal_ip = $this->query('getAll', 'internal_ip');
        $external_ip = $this->query('getAll', 'external_ip');

        $return['heartbeat'] = strtotime($heartbeat['data']['value']);
        $return['internal_ip'] = array();
        $return['external_ip'] = array();

        foreach ($internal_ip['data'] as $data) {
            $return['internal_ip'][] = array('ip' => $data['value'], 'last_update' => strtotime($data['last_update']));
        }
        foreach ($external_ip['data'] as $data) {
            $return['external_ip'][] = array('ip' => $data['value'], 'last_update' => strtotime($data['last_update']));
        }

        return $return;
    }

    /**
     * Query the most recent complete information stored for the requested grouping
     *
     * @return array
     * @throws \Exception
     */
    public function group()
    {
        $result = $this->query('group');
        $return = array();
        if ($result['response'] === 'success') {
            foreach ($result['data'] as $data) {
                $return[] = array(
                    'key'         => $data['key'],
                    'value'       => $this->formatter($data),
                    'last_update' => strtotime($data['last_update'])
                );
            }
            return $return;
        } else {
            throw new \Exception('Response failed');
        }
    }

    /**
     * Helper method to reformat the time passed in the heartbeat meta data
     * @param $data
     * @return false|int
     */
    private function formatter($data)
    {
        switch ($data['key']) {
            case 'heartbeat':
                return strtotime($data['value']);
                break;
            default:
                return $data['value'];
                break;
        }
    }

    /**
     * Method for performing the actual request to theAxeRant
     *
     * @param string $method The method the request from the api service
     * @param string $key    The key to select the value for from the api service
     * @return array The response object returned from the api service
     */
    private function query($method, $key = \null)
    {

        $full_url = $this->generateFullUrl($method, $key);

        $options = array( 'auth' => $this->auth );

        $postOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($options)
            )
        );

        $context = stream_context_create($postOptions);
        $result = file_get_contents($full_url, false, $context);

        return json_decode($result, true);
    }

    /**
     * Generate the full url to make the request to
     *
     * @param $method string the method name to append to the api url
     * @param $key string the optional key name to append to the url
     * @return string
     */
    private function generateFullUrl($method, $key = \null)
    {
        $full_url = $this->base_url . $method . '/' . $this->grouping;
        if (\null !== $key) { $full_url .= '/' . $key; }
        return $full_url;
    }


}

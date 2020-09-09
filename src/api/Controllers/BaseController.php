<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Laminas\Escaper\Escaper;
use App\Shared\Helpers\ResponseHelper;
use App\Shared\Helpers\TokenHelper;
use App\Shared\Helpers\UserHelper;

class BaseController
{
    /**
     * Container
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Database instance
     *
     * @var Capsule
     */
    protected $db;

    /**
     * Request
     *
     * @var Request
     */
    protected $request;

    /**
     * Init dependencies
     *
     * @param ContainerInterface $container The app container
     * @param Capsule $db The database instance
     */
    public function __construct(
        $container,
        $db
    ) {
        $this->container = $container;
        $this->db        = $db;
    }

    /**
     * Get service/ dependency from container
     *
     * @param string $name The service/ dependency name
     * @return mixed The service/ dependency value
     */
    public function __get(string $name)
    {
        return $this->container->get($name);
    }

    /**
     * Set container's service/ dependency
     *
     * @param string $name
     * @param callable $value The service/ dependency callback
     */
    public function __set(string $name, $value)
    {
        $this->container->set($name, $value);
    }

    /**
     * Use use Laminas\Escaper\Escaper;
     *
     * @return Escaper The Laminas Escaper object.
     */
    public function escaper()
    {
        return new Escaper('utf-8');
    }

    /**
     * Share current "Request" to this class and it's children
     *
     * @param Request $request The current server request
     */
    public function shareRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get instance of ResponseHelper
     *
     * @return ResponseHelper A ResponseHelper object
     */
    public function response()
    {
        return new ResponseHelper($this->container, $this->request);
    }

    /**
     * Get instance of TokenHelper
     *
     * @return TokenHelper A TokenHelper object
     */
    public function token()
    {
        return new TokenHelper($this->container, $this->request);
    }

    /**
     * Get instance of UserHelper
     *
     * @return UserHelper A UserHelper object
     */
    public function user()
    {
        return new UserHelper($this->container, $this->request);
    }
}

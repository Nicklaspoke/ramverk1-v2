<?php

namespace Niko\IpValidation;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class IpValidationJSONController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

        /**
     * @var string $title   The title for all pages using this controller
     */
    private $title = "Ip Validation with JSON";

    public function indexActionGet()
    {
        $page = $this->di->get("page");
        $page->add("ip-validation/index-json");

        return $page->render([
            "title" => $this->title
        ]);
    }

    public function jsonActionGet() : array
    {
        $ip = $this->di->get("request")->getGet("ip");

        $validation = filter_var($ip, FILTER_VALIDATE_IP);

        $valid = $validation == false ? false : true;

        $domain = !$validation ? "" : gethostbyaddr($ip);

        $hostname = $domain == $ip ? "" : $domain;

        $json = [
            "ip" => $ip,
            "valid" => $valid,
            "hostname" => $hostname
        ];

        return [$json];
    }
}

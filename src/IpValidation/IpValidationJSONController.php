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

    public function initialize() : void
    {
        //Use to initialise member variables.
        $this->ipValidator = new IpValidator();
    }

    public function indexActionGet()
    {
        $data = [];
        $data["userIp"] = $this->di->get("request")->getServer("REMOTE_ADDR");
        $page = $this->di->get("page");
        $page->add("ip-validation/index-json", $data);

        return $page->render([
            "title" => $this->title
        ]);
    }

    public function jsonActionGet() : array
    {
        $ip = $this->di->get("request")->getGet("ip");
        $kmom = $this->di->get("request")->getGet("kmom");

        switch ($kmom) {
            case "kmom01":
                $json = $this->ipValidator->validateIp($ip);
                break;
            case "kmom02":
                $json = $this->kmom02($ip);
                break;
        }
        return [$json];
    }

    private function kmom02($ip) {
        $data = $this->ipValidator->validateIp($ip);

        if ($data["valid"]) {
            $data["geoinfo"] = $this->ipValidator->getGeoLocation($ip, $this->di->get("apiInfo")->getApiKey("ipstack"));
        }

        return $data;
    }
}

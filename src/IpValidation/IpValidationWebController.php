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
class IpValidationWebController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

        /**
     * @var string $title   The title for all pages using this controller
     */
    private $title = "Ip Validation";

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

        $page->add("ip-validation/index", $data);

        return $page->render([
            "title" => $this->title
        ]);
    }

    public function indexActionPost() {
        $data = [];
        $data["title"] = $this->title;
        $ip = $this->di->get("request")->getPost("ipInput");

        $kmom = $this->di->get("request")->getPost("kmom");


        switch ($kmom) {
            case "kmom01":
                $data["message"] = $this->kmom01($ip);
                break;

            case "kmom02":
                $data["message"] = $this->kmom02($ip);
                break;
        }

        $page = $this->di->get("page");

        $page->add("ip-validation/index", $data);

        return $page->render([
            "title" => $this->title
        ]);
    }

    private function kmom01($ip)
    {
        $returnData = $this->ipValidator->validateIp($ip);

        $message = $ip . " ";
        if ($returnData["valid"]) {
            $message .= "is a valid: " . $returnData["ip_version"] . "<br>";
            $message .= " And has the the hostname: " . $returnData["hostname"];
        } else {
            $message .= "is not a valid " . " ip adress</p>";
        }

        return $message;
    }

    private function kmom02($ip)
    {
        $returnData = $this->ipValidator->validateIp($ip);

        if ($returnData["valid"]) {
            $geoInfo = $this->ipValidator->getGoeLocation($ip);
            $message = "IP: " . $ip . "<br>";
            $message .= "Type: " . $geoInfo["type"] . "<br>";
            $message .= "Country: " . $geoInfo["country_name"] . "<br>";
            $message .= "latitude: " . $geoInfo["latitude"] . "<br>";
            $message .= "longitude: " . $geoInfo["longitude"] . "<br>";
        } else {
            $message .= "is not a valid " . " ip adress</p>";
        }

        return $message;
    }
}

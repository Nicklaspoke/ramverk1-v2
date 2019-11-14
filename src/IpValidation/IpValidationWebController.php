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

    public function debugAction() : string
    {
        // Deal with the action and return a response.
        return "Debug Of IpValidationWebController";
    }

    public function indexActionGet()
    {
        $page = $this->di->get("page");

        $page->add("ip-validation/index");

        return $page->render([
            "title" => $this->title
        ]);
    }

    public function indexActionPost() {
        $ip = $this->di->get("request")->getPost("ipInput");

        $validation = filter_var($ip, FILTER_VALIDATE_IP);

        $message = $ip . " ";
        if (!$validation) {
            $message .= "is not a valid ipv4 or ipv6 address";
        } else {
            $domain = gethostbyaddr($ip);
            $message .= "is a valid ipv4 or ipv6 address";

            if ($domain != $ip) {
                $message .= " and has the hostname: " . $domain;
            }
        }
        $data = [
            "message" => $message,
            "title" => $this->title
        ];

        $page = $this->di->get("page");

        $page->add("ip-validation/index", $data);

        return $page->render([
            "title" => $this->title
        ]);
    }
}

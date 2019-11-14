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

    /**
     *Initialize method that sets up variables and such
     * Before the other methods use them
     *
     * @return void
     */
    public function initialize() : void
    {

    }

    public function debugAction() : string
    {
        // Deal with the action and return a response.
        return "Debug Of IpValidationWebController";
    }

    public function indexAction()
    {
        $page = $this->di->get("page");

        $page->add("ip-validation/index");

        return $page->render([
            "title" => $this->title
        ]);

        return "Index route";
    }
}

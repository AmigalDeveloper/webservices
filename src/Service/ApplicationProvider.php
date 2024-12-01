<?php

/**
 * Joomla! Framework Website
 *
 * @copyright  Copyright (C) 2014 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Amigal\Webservice\Service;

use Joomla\Application\AbstractWebApplication;
use Joomla\Application\Controller\ContainerControllerResolver;
use Joomla\Application\Controller\ControllerResolverInterface;
use Joomla\Application\Web\WebClient;
use Joomla\Console\Application as ConsoleApplication;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\Command\DebugEventDispatcherCommand;
use Joomla\Event\DispatcherInterface;
use Amigal\Webservice\Controller\WrongCmsController;
use Amigal\Webservice\WebApplication;
use Joomla\Input\Input;
use Joomla\Input\Json;
use Joomla\Router\Command\DebugRouterCommand;
use Joomla\Router\Route;
use Joomla\Router\Router;
use Joomla\Router\RouterInterface;
use Psr\Log\LoggerInterface;
use Amigal\Webservice\Controller\AbstractWebserviceController;
use Amigal\Webservice\Model\AbstractWebserviceModel;
use Amigal\Webservice\View\WebserviceJsonView;


/**
 * Application service provider
 */
class ApplicationProvider implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     */
    public function register(Container $container): void
    {
        /*
         * Application Classes
         */

        $container->share(ConsoleApplication::class, [$this, 'getConsoleApplicationService'], true);
        // This service cannot be protected as it is decorated when the debug bar is available
        $container->alias(WebApplication::class, AbstractWebApplication::class)
            ->share(AbstractWebApplication::class, [$this, 'getWebApplicationClassService']);

        /*
         * Application Helpers and Dependencies
         */
        $container->alias(ContainerControllerResolver::class, ControllerResolverInterface::class)
            ->share(ControllerResolverInterface::class, [$this, 'getControllerResolverService']);
        // $container->alias(Helper::class, 'application.helper')
        //     ->share('application.helper', [$this, 'getApplicationHelperService'], true);
        // $container->alias(PackagistHelper::class, 'application.helper.packagist')
        //     ->share('application.helper.packagist', [$this, 'getApplicationHelperPackagistService'], true);
        // $container->share('application.packages', [$this, 'getApplicationPackagesService'], true);
        $container->share(WebClient::class, [$this, 'getWebClientService'], true);
        // This service cannot be protected as it is decorated when the debug bar is available
        $container->alias(RouterInterface::class, 'application.router')
            ->alias(Router::class, 'application.router')
            ->share('application.router', [$this, 'getApplicationRouterService']);
        $container->share(Input::class, [$this, 'getJsonClassService'], true);
        $container->share(Json::class, [$this, 'getJsonClassService'], true);

        /*
         * Console Commands
         */
        // $container->share(DebugEventDispatcherCommand::class, [$this, 'getDebugEventDispatcherCommandService'], true);
        // $container->share(DebugRouterCommand::class, [$this, 'getDebugRouterCommandService'], true);
        
        /*
         * MVC Layer
         */
        // Controllers
        $container->alias(AbstractWebserviceController::class, 'controller.webservice')
            ->share('controller.webservice', [$this, 'getControllerWebserviceService'], true);
        $container->alias(WrongCmsController::class, 'controller.wrong.cms')
            ->share('controller.wrong.cms', [$this, 'getControllerWrongCmsService'], true);
        // Models
        $container->alias(AbstractWebserviceModel::class,'model.webservice')
        ->share('model.webservice', [$this,'getModelWebserviceService'], true);

        // views
        $container->alias(WebserviceJsonView::class, 'view.webservice.json')
            ->share('view.webservice.json', [$this, 'getViewWebserviceJsonService'], true);
    }



    /**
     * Get the `application.helper` service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  Helper
     */
    // public function getApplicationHelperService(Container $container): Helper
    // {
    //     $helper = new Helper();
    //     // $helper->setPackages($container->get('application.packages'));
    //     return $helper;
    // }

    /**
     * Get the `application.helper.packagist` service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  PackagistHelper
     */
    // public function getApplicationHelperPackagistService(Container $container): PackagistHelper
    // {
    //     $helper = new PackagistHelper($container->get(Http::class), $container->get(DatabaseInterface::class));
    //     // $helper->setPackages($container->get('application.packages'));
    //     return $helper;
    // }

    /**
     * Get the `application.packages` service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  Registry
     */
    // public function getApplicationPackagesService(Container $container): Registry
    // {
    //     return (new Registry())->loadFile(JPATH_ROOT . '/packages.yml', 'YAML');
    // }
    
    
    /**
     * Get the `application.router` service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  RouterInterface
     */
    public function getApplicationRouterService(Container $container): RouterInterface
    {
        $router = new Router();
        
        /*
         * CMS Admin Panels
         */
        $router->get('/administrator', WrongCmsController::class);
        $router->get('/administrator/*', WrongCmsController::class);
        $router->get('/wp-admin', WrongCmsController::class);
        $router->get('/wp-admin/*', WrongCmsController::class);
        $router->get('wp-login.php', WrongCmsController::class);
        /*
         * Web routes
         */
     $router->addRoute(
        new Route(
            ['post','get'],
            '', 
            AbstractWebserviceController::class,
            [],
            [
            "task" =>"timestamp"
            ]
        )
    );
        /*
         * API routes
         */
        $routerFile = JPATH_ROOT.'/etc/routes.json';
        $routes = json_decode(file_get_contents($routerFile), true);
        $router->addRoutes($routes);
        return $router;
    }

    
    /**
     * Get the controller resolver service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  ControllerResolverInterface
     */
    public function getControllerResolverService(Container $container): ControllerResolverInterface
    {
        return new ContainerControllerResolver($container);
    }

    // /**
    //  * Get the `controller.webservice` service
    //  *
    //  * @param   Container  $container  The DI container.
    //  *
    //  * @return  AbstractWebserviceController
    //  */
    public function getControllerWebserviceService(Container $container): AbstractWebserviceController
    {
        return new AbstractWebserviceController($container->get(WebserviceJsonView::class), $container->get(Input::class), $container->get(WebApplication::class));
    }

    /**
     * Get the `controller.wrong.cms` service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  WrongCmsController
     */
    public function getControllerWrongCmsService(Container $container): WrongCmsController
    {
        return new WrongCmsController($container->get(Input::class), $container->get(WebApplication::class));
    }

    /**
     * Get the DebugEventDispatcherCommand service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  DebugEventDispatcherCommand
     */
    public function getDebugEventDispatcherCommandService(Container $container): DebugEventDispatcherCommand
    {
        return new DebugEventDispatcherCommand($container->get(DispatcherInterface::class));
    }

    /**
     * Get the DebugRouterCommand service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  DebugRouterCommand
     */
    public function getDebugRouterCommandService(Container $container): DebugRouterCommand
    {
        return new DebugRouterCommand($container->get(Router::class));
    }

   
    /**
     * Get the Input class service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  Input
     */
    public function getInputClassService(Container $container): Input
    {
        return new Input($_REQUEST);
    }
    /**
     * Get the Input class service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  Input
     */
    public function getJsonClassService(Container $container): Json
    {
        return new Json();
    }

   
    // /**
    //  * Get the `model.webservice` service
    //  *
    //  * @param   Container  $container  The DI container.
    //  *
    //  * @return  ReleaseModel
    //  */
    public function getModelWebserviceService(Container $container): AbstractWebserviceModel
    {
        return new AbstractWebserviceModel($container->get(DatabaseInterface::class));
    }

   
    /**
     * Get the `view.webservice.json` service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  WebserviceJsonView
     */
    public function getViewWebserviceJsonService(Container $container): WebserviceJsonView
    {
        return new WebserviceJsonView($container->get('model.webservice'));
    }

    /**
     * Get the WebApplication class service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  WebApplication
     */
    public function getWebApplicationClassService(Container $container): WebApplication
    {
        $application              = new WebApplication($container->get(ControllerResolverInterface::class), $container->get(RouterInterface::class), $container->get(Input::class), $container->get('config'), $container->get(WebClient::class));
        $application->httpVersion = '2';
        // Inject extra services
       $application->setDispatcher($container->get(DispatcherInterface::class));
        $application->setLogger($container->get(LoggerInterface::class));
        return $application;
    }

    /**
     * Get the web client service
     *
     * @param   Container  $container  The DI container.
     *
     * @return  WebClient
     */
    public function getWebClientService(Container $container): WebClient
    {
        /** @var Input $input */
        $input          = $container->get(Input::class);
        $userAgent      = $input->server->getString('HTTP_USER_AGENT', '');
        $acceptEncoding = $input->server->getString('HTTP_ACCEPT_ENCODING', '');
        $acceptLanguage = $input->server->getString('HTTP_ACCEPT_LANGUAGE', '');
        return new WebClient($userAgent, $acceptEncoding, $acceptLanguage);
    }
}

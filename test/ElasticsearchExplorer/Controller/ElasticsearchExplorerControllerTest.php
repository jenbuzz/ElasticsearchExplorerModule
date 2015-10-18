<?php

namespace ElasticsearchExplorerTest\Controller;

use ElasticsearchExplorerTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use ElasticsearchExplorer\Controller\ElasticsearchExplorerController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class ElasticsearchExplorerControllerTest extends \PHPUnit_Framework_TestCase
{
    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $elasticsearchClientMock;

    /**
     * Setup testing environment.
     */
    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new ElasticsearchExplorerController();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'elasticsearchexplorer'));
        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);

        $this->elasticsearchClientMock = $this->getMockBuilder('ElasticsearchExplorer\Service\ElasticsearchManager')
                                              ->disableOriginalConstructor()
                                              ->getMock();
    }

    /**
     * Cleanup testing environment.
     */
    public function tearDown()
    {
        unset($this->elasticsearchClientMock);
    }

    /**
     * Testing access to index action.
     * Asserting http status code to 200.
     */
    public function testIndexActionCanBeAccessed()
    {
        $this->elasticsearchClientMock->expects($this->once())
                                      ->method('getIndexStats')
                                      ->will($this->returnValue(array()));

        $serviceManager = $this->controller->getServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('ElasticsearchManager', $this->elasticsearchClientMock);

        $this->routeMatch->setParam('action', 'index');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Testing access to search action.
     * Asserting http status code to 200.
     */
    public function testSearchActionCanBeAccessed()
    {
        $this->elasticsearchClientMock->expects($this->once())
                                      ->method('getIndexStats')
                                      ->will($this->returnValue(array()));

        $serviceManager = $this->controller->getServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('ElasticsearchManager', $this->elasticsearchClientMock);

        $this->routeMatch->setParam('action', 'search');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Testing access to config action.
     * Asserting http status code to 200.
     */
    public function testConfigActionCanBeAccessed()
    {
        $this->elasticsearchClientMock->expects($this->once())
                                      ->method('getConfiguration')
                                      ->will($this->returnValue(array('hosts' => '')));

        $serviceManager = $this->controller->getServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('ElasticsearchManager', $this->elasticsearchClientMock);

        $this->routeMatch->setParam('action', 'config');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Testing access to plugins action.
     * Asserting http status code to 200.
     */
    public function testPluginsActionCanBeAccessed()
    {
        $this->elasticsearchClientMock->expects($this->once())
                                      ->method('getPlugins')
                                      ->will($this->returnValue(array()));

        $this->elasticsearchClientMock->expects($this->once())
                                      ->method('getConfiguration')
                                      ->will($this->returnValue(array('hosts' => '')));

        $serviceManager = $this->controller->getServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('ElasticsearchManager', $this->elasticsearchClientMock);

        $this->routeMatch->setParam('action', 'plugins');

        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}

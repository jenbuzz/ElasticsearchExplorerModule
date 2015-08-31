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

    public function tearDown()
    {
        unset($this->elasticsearchClientMock);
    }

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
}

<?php

/**
 * 
 *
 * @author Orest
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    public $router;
    public $routes = array();
    public $baseUrl = 'http://base.url/';

    protected function setUp()
    {
        $this->router = new \System\Router();
        $this->router->setBaseUrl($this->baseUrl);

        $this->routes['first'] = new \System\Router\Route('/first');
        $this->routes['second'] = new \System\Router\Route('/second');
        $this->routes['third'] = new \System\Router\Regex('/article/:id', null, array('id' => '\d+'));

        $this->router->add('first', $this->routes['first']);
        $this->router->add('second', $this->routes['second']);
        $this->router->add('regex', $this->routes['third']);
    }

    public function testMatching()
    {
        $this->assertTrue($this->routes['first']->isMatch('/first'));
        $this->assertTrue($this->routes['third']->isMatch('/article/65'));
        $this->assertFalse($this->routes['third']->isMatch('/article/b'));
    }
}

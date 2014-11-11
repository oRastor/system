<?php

namespace System;

abstract class Controller
{

    protected $layoutTemplate = 'index.htm';
    protected $viewTemplate = 'index/index.htm';
    public $view;
    public $params = array();

    /**
     *
     * @var DI
     */
    public $di;

    final public function __construct($di)
    {
        $this->di = $di;
        $this->view = new \stdClass();
    }

    protected function init()
    {
        //init actions
    }

    protected function preAction()
    {
        //actions before call controller action
    }

    protected function postAction()
    {
        //actions after call controller action
    }

    public function response()
    {
        if ($this->viewTemplate !== false) {
            $view = $this->di->get('view');

            if (!($view instanceof ViewInterface)) {
                throw new DI\InvalidOffsetException('DI View must be an instance of System\View');
            }

            $this->view->di = $this->di;

            if ($this->layoutTemplate == false) {
                echo $view->fetch($this->viewTemplate, $this->view);
            } else {
                $this->view->content = $view->fetch($this->viewTemplate, $this->view);

                echo $view->fetch($this->layoutTemplate, $this->view);
            }
        }
    }

    public function callAction($name)
    {
        if (method_exists($this, $name . 'Action')) {
            $this->init();
            $this->preAction();
            $this->{$name . 'Action'}();
            $this->postAction();
            $this->response();
            return true;
        }

        return false;
    }

    public function makeJSONResponse($data)
    {
        header('Content-Type: application/javascript');
        echo json_encode($data);
        die;
    }

    public function setLayout($template)
    {
        $this->layoutTemplate = $template;
    }

    public function setView($template)
    {
        $this->viewTemplate = $template;
    }

    public function disableView()
    {
        $this->viewTemplate = false;
    }

    public function disableLayout()
    {
        $this->layoutTemplate = false;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Get Request object
     * 
     * @return Request
     * @throws DI\InvalidOffset
     */
    public function getRequest()
    {
        $request = $this->di->get('request');

        if (!($request instanceof Request)) {
            throw new DI\InvalidOffsetException("Request object not defined!");
        }

        return $request;
    }

    public function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        die;
    }

}

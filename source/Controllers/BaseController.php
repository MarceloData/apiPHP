<?php

namespace Source\Controllers;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;

class BaseController
{
    public $class;
    public $params;

    public function __construct($class, $params)
    {
        $this->setClass($class);
        $this->setParams($params);
    }

    /**
     * Get the value of class
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the value of class
     *
     * @return  self
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get the value of params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set the value of params
     *
     * @return  self
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getAll(ServerRequestInterface $request) : ResponseInterface
    {
        $response = new JsonResponse($this->getClass()->getAllData());
        return $response;
    }

    public function get(ServerRequestInterface $request, array $args) : ResponseInterface
    {
        $response = new JsonResponse($this->getClass()->getData($args));
        return $response;
    }

    public function show(ServerRequestInterface $request) : ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write('Teste');
        return $response->withStatus(200);
    }

    public function post(ServerRequestInterface $request, array $params) : ResponseInterface
    {
        $class = $this->getClass();
        $response = new Response;

        foreach($this->getParams() as $value){
            if (!empty($_FILES[$value])){
                $params[$value] = $_FILES[$value];
            } else {
                $params[$value] = filter_input(INPUT_POST, $value) ?? null;
            }
        }

        $calledClass = get_called_class();
        $filterPost = (new $calledClass)->filterPost($params);

        if ($filterPost[1]) {
            $class->createData($filterPost[0]);

            return $response->withStatus(200);

        } else {
            echo $filterPost[0];
        }
        return $response->withStatus(200);
    }

    public function edit(ServerRequestInterface $request, array $args) : ResponseInterface
    {
        $response = new JsonResponse($this->getClass()->getData($args));
        return $response;
    }

    public function update(ServerRequestInterface $request, array $args, array ...$params) : ResponseInterface
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $response = new Response;
        //Abaixo deveria ser PUT
        if ('POST' === $method) {
            $class = $this->getClass()->findById(filter_var((int)$args['id'], FILTER_VALIDATE_INT));
            // parse_str(file_get_contents('php://input'), $input);

            foreach($this->getParams() as $value){
                if (!empty($_FILES[$value])){
                    $params[$value] = $_FILES[$value] ?? $class->$value;
                } else {
                    $params[$value] = filter_input(INPUT_POST, $value) ?? $class->$value;
                }
            }

            $calledClass = get_called_class();
            $filterUpdate = (new $calledClass)->filterUpdate($params);

            if ($filterUpdate[1]) {

                $response = new JsonResponse($class->updateData($args, $filterUpdate[0]));

                return $response;

            } else {
                echo $filterUpdate[0];
            }

            return $response->withStatus(200);
        }

        return $response->withStatus(200);
    }

    public function delete(ServerRequestInterface $request, array $args) : ResponseInterface
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        if ($method === 'delete') {
            $response = new JsonResponse($this->getClass()->deleteData($args));
            return $response;
        }
    }
}
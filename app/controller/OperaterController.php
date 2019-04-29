<?php

class OperaterController extends ProtectedController
{
    function index(){
        $view = new View();
        $view->render(
            'operateri/index',
            [
            "operateri"=>Operater::read()
            ]
        );
    }
}
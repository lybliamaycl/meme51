<?php

class Router {

    public static function analyse($request){
        $result = array(
            'controller'    => 'Error',
            'action'        => 'error404',
            'params'        => array()
        );

        if($request === '' || $request === '/'){ // Route vers la page d'accueil
            $result['controller']   = 'Index';
            $result['action']       = 'index';
        } else {
            $parts = explode('/', $request);

            if($parts[0] == 'generateur' && (count($parts) == 1 || $parts[1] == '')){ // Route vers la page de connexion
                $result['controller']       = 'Generateur';
                $result['action']           = 'VerifUpload';
            }

            if($parts[0] == 'download' && (count($parts) == 1 || $parts[1] == '')){ // Route vers la page de connexion
                $result['controller']       = 'Download';
                $result['action']           = 'Generate';
            }
        }

        return $result;
    }
    
}
<?php

class GenerateurController extends Controller {

    public function VerifUpload(){
        $message = '';

    
        $template = $this->twig->loadTemplate('/Page/gmeme.html.twig');
        echo $template->render(array(
            'message'   => $message
        ));
    }
    
}
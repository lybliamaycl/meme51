<?php

class IndexController extends Controller {

    public function index(){
        $images = Meme::getAllImages();

        $template = $this->twig->loadTemplate('/Page/index.html.twig');
        echo $template->render(array(
            'images'    => $images
        ));
    }
}
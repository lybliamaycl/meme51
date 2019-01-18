<?php

class DownloadController extends Controller {

    public function Generate(){
        if(isset($_FILES) && $_FILES['meme']['name'] != ''){

            $toto = Meme::Send($_FILES['meme']['name']); // ID de l'image
            //var_dump($toto);
            
            $image = Meme::getImageById($toto); // LIGNE DE L'IMAGE ayant pour ID $toto

            $dossier = './assets/upload/';
            
            //var_dump($image['NAME']);

            $ext = image_type_to_extension(IMAGETYPE_PNG); // pour .png
            $file_name = 'generate_meme_'.time().$ext; // On renomme la nouvelle image

            $img = imagecreatefromjpeg($dossier.$image['NAME']);

            $font = './assets/font/impact.ttf';

            if (!$_POST['sizeTop'] == ""){
                $font_size_top = $_POST['sizeTop'];
            }
            else{
                $font_size_top = 25;
            }

            if(!$_POST['sizeBot'] == ""){
                $font_size_bot = $_POST['sizeBot'];
            }
            else{
                $font_size_bot = 25;
            }

            $angle = 0;

            $margin = 20; 

            $width = imagesx($img);
            $height = imagesy($img);

            $texttop = strtoupper($_POST['toptext']);
            $textbot = strtoupper($_POST['bottext']);

            $noir = imagecolorallocate($img, 0, 0, 0);
            $blanc = imagecolorallocate($img,255,255,255);

            ///////////////////////// Texte du haut

            //explode text by words
            $texttop_a = explode(' ', $texttop);
            $texttop_new = '';

            foreach($texttop_a as $word){
                //Create a new text, add the word, and calculate the parameters of the text
                $box = imagettfbbox($font_size_top, $angle, $font, $texttop_new.' '.$word);
                //if the line fits to the specified width, then add the word with a space, if not then add word with new line
                if($box[2] > $width - $margin*2){
                    $texttop_new .= "\n".$word;
                } else {
                    $texttop_new .= " ".$word;
                }
            }

            //trip spaces
            $texttop_new = trim($texttop_new);
            
            $boxtop = imagettfbbox($font_size_top, $angle, $font, $texttop_new);
            $text_widthtop = abs($boxtop[2]) - abs($boxtop[0]);
            $text_heighttop = abs($boxtop[5]) - abs($boxtop[3]);

            $xt = ($width/2) - ($text_widthtop/2);
            $yt = $font_size_top+$margin;
        
            imagettftext($img,$font_size_top, $angle, $xt, $yt, $blanc, $font, $texttop_new);

            /////////////////////////////////////////////

            ///////////////////////// Texte du bas

            //explode text by words
            $textbot_a = explode(' ', $textbot);
            $textbot_new = '';

            foreach($textbot_a as $word){
                //Create a new text, add the word, and calculate the parameters of the text
                $box = imagettfbbox($font_size_bot, $angle, $font, $textbot_new.' '.$word);
                //if the line fits to the specified width, then add the word with a space, if not then add word with new line
                if($box[2] > $width - $margin*2){
                    $textbot_new .= "\n".$word;
                } else {
                    $textbot_new .= " ".$word;
                }
            }

            //trip spaces
            $textbot_new = trim($textbot_new);
            
            $boxbot = imagettfbbox($font_size_bot, $angle,$font, $textbot_new);
            $text_widthbot = abs($boxbot[2]) - abs($boxbot[0]);
            $text_heightbot = abs($boxbot[5]) - abs($boxbot[3]);

            $xb = ($width/2) - ($text_widthbot/2);
            $yb = ($height - ($margin));


            imagettftext($img,$font_size_bot, $angle, $xb, $yb, $blanc, $font, $textbot_new);

            ///////////////////////////////////////////////

            imagepng($img, $dossier.$file_name); // On crée l'image et on le sauvegarde dans /upload en .png
    
            Meme::SendMeme($file_name);

            imagedestroy($img);

            //var_dump($file_name);
        }
                
        $meme = Meme::getMeme($file_name); // On récupère le résultat du mème pour l'afficher

        $template = $this->twig->loadTemplate('/Page/resultat.html.twig');
        echo $template->render(array(
            'meme'   => $meme,
        ));
        
    }




}
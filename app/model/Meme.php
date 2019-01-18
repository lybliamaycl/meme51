<?php 

class Meme extends Model{

    public static function getAllImages(){
        $db = Database::getInstance();
        $sql = "SELECT * FROM images WHERE is_meme ='1' ORDER BY date DESC";
        $images = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $images;
    }

    public static function Send($file_name){

        if(isset($_POST['generate'])){
            $dossier = './assets/upload/';  
            $errors =  "";
            $file_name = $_FILES['meme']['name'];
            $file_ext = end(explode('.',$_FILES['meme']['name']));
            $ext = array('jpg','png','jpeg');
        
                switch ($_FILES['meme']['error']) { 
                    case UPLOAD_ERR_INI_SIZE: 
                        $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                        break; 
                    case UPLOAD_ERR_FORM_SIZE: 
                        $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                        break; 
                    case UPLOAD_ERR_PARTIAL: 
                        $message = "The uploaded file was only partially uploaded"; 
                        break; 
                }
        
            if($_FILES['meme']['error'] == UPLOAD_ERR_OK){
                if(in_array($file_ext, $ext, true)){
                    $file_name = 'meme_'.time().'.'.$file_ext;

                    if( move_uploaded_file($_FILES['meme']['tmp_name'], $dossier.$file_name) ){ //On déplace l'image du dossier temporaire dans /upload
                        chmod($dossier.$file_name, 0777);
                            $message = 'Votre image a été upload';
                    }

                } else {
                    $message = 'Extension not allowed';
                }
            }

        // Envoi de l'image vierge upload dans la BDD //
        $db = Database::getInstance();
        $sql = $db->prepare("INSERT INTO images (
                                name,
                                date) VALUES (
                                :name, 
                                :date)"
        );
        
        $sql->bindValue(':name', $file_name, PDO::PARAM_STR);
        $sql->bindValue(':date', time(), PDO::PARAM_STR);
        $sql->execute();
        ///////////////////////////////////////////////

        $lastInsertId = $db->lastInsertID();

        return $lastInsertId;
    }
}

    public static function getImageById($id){
        $db = Database::getInstance();

        $sql = $db->prepare('select * from images
                                where id = :id');

        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }// Récupération de l'image vierge qu'on vient d'envoyer dans la BDD


    public static function SendMeme($name){
        $db = Database::getInstance();

        $sql = $db->prepare("INSERT INTO images (
                            name,
                            date,
                            is_meme) VALUES (
                            :name, 
                            :date,
                            '1')"
        );

        $sql->bindValue(':name', $name, PDO::PARAM_STR);
        $sql->bindValue(':date', time(), PDO::PARAM_STR);
        $sql->execute();

        return true;
    }// On envoie le nouveau mème dans la BDD

    public static function GetMeme($name){
        $db = Database::getInstance();

        $sql = $db->prepare("SELECT * FROM images WHERE name = :name");
        $sql->bindValue(':name', $name, PDO::PARAM_STR);
        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }// On récupère le résultat du mème pour l'afficher dans resultat.html.twig

}
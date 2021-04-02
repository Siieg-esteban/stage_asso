<?php
namespace App\Service;

use ZipArchive; // zip de php limité a 4gb
use PhpZip\ZipFile; // zip de nelexa 

class unziper
{
    public function unzip($archive,$dossier) // $archive = nom du fichier zip , $destination = chemin ou trouver le fichier zip et où l'extraire
    {   $destination="../public/jeuWeb";
        $file=$destination.'/'.$archive;
        $zip=new ZipArchive();
        $stream=fopen($file, 'rb');

        if($zip->open($file)===TRUE){
            if(is_writable($destination.'/')){
                $zip->extractTo($destination.'/'.$dossier);
                $zip->close();
                unlink($file);
                echo 'success';
            }else{echo 'echec directory';}
        }else{echo 'echec open';}
    }
}
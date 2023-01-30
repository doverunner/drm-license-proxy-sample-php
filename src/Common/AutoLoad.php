<?php
/**
 * Class auto load
 *
 */

spl_autoload_register(function ($className) {
    $dirs = array(
        'Common/',
        'Exception/',
        'Service/',
        'TokenSample/'
    );

    foreach($dirs as $dir) {

        $base_path = __DIR__."".DIRECTORY_SEPARATOR;
        $class_path =  $base_path . $className . '.php';
        $class_path = str_replace("\\", "/", $class_path);

        if ( $dir == "TokenSample/" ){   // TokenSample Path
            $class_path = str_replace(DIRECTORY_SEPARATOR ."Common". DIRECTORY_SEPARATOR."PallyCon".DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR."TokenSample".DIRECTORY_SEPARATOR, $class_path);
        }else{
            $class_path = str_replace(DIRECTORY_SEPARATOR ."Common". DIRECTORY_SEPARATOR."PallyConProxy".DIRECTORY_SEPARATOR, "".DIRECTORY_SEPARATOR, $class_path);
        }

        if(file_exists( $class_path )) {
            require_once($class_path);
            return;
        }
    }



});
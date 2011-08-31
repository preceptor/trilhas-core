<?php

define( 'PATH', dirname(__FILE__) . '/../../application' );

if ( file_exists( PATH . '/configs/application.ini') ) {
    
    exit('application.ini ja existente');

} else {
    
    echo '<p><a href=\'form.php\' class=\'button\'>Instalar</a>';

    
}

?>
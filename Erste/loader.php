<?php
    $SYSTEM = '';
    $ERROR = '';
    $SCRIPT = '';

    # create config
    require str_ireplace("loader.php", "", __FILE__) . '/config.php'; 
    // TODO convert numbers to float http://www.php.net/manual/en/class.simplexmlelement.php#100162 
    $config = new SimpleXMLElement($xml_config);

    $levels = Array('abstraction', 'core', 'common');
    require $config->paths[0]->core . 'core.php';
    require $config->paths[0]->core . 'access/core/' . $config->access . '/connect.php';
    
    require $config->paths[0]->common . 'Interface/' . 'LanguageItem.php';
    require $config->paths[0]->common . 'Interface/' . 'Response.php';
    require $config->paths[0]->common . 'Interface/' . 'ResponseItem.php';
?>

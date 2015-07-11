<?php
    function fail() {
        return '{ "success" : false }';
    }

    require '../loader.php';
    
    if (! $_POST['Data']) {
        return fail();
    }
    
    $data = json_decode(str_replace('\"', '"', $_POST['Data']), true);

    $response = new Response($data['Rating'], $data['Scope']);
    if (! $response->save()) {
        return fail();
    }
    
    foreach ($data['Data'] as $language_item) {
        $keys = array_keys($language_item);
        
        $new_item = $response->add_item(
                new ResponseItem($response->get_ID(), $keys[0])
            );
        if (! $new_item->save()) {
            return fail();
        }  
    }
?>
{
    "success" : true
}
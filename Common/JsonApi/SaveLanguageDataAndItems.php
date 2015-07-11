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
        $language_item['Text'] = trim($language_item['Text']);
        $new_item = new LanguageItem($language_item['Text'], $language_item['Scope'], $language_item['Position'], $language_item['Type']);
        if (! $new_item->checkExistence() && ! $new_item->save()) {
            return fail();
        } 
        $response_item = new ResponseItem($response->get_ID(), $new_item->get_ID());
        if (! $response_item->save()) {
            return fail();
        }
    }
?>
{
    "success" : true
}
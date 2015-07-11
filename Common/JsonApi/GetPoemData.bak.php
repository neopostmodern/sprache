<?php
    require '../loader.php';
    $language_items = Array();
    
    $TYPES = Array(
        LanguageItem::SUBSTANTIV, 
        LanguageItem::ADJEKTIV, LanguageItem::SUBSTANTIV, 
        LanguageItem::SUBSTANTIV, LanguageItem::VERB, LanguageItem::SUBSTANTIV,
        LanguageItem::ADJEKTIV, LanguageItem::SUBSTANTIV, LanguageItem::VERB, LanguageItem::SUBSTANTIV
    );
    
    for($i = 0; $i < 10; $i++) {
        $language_items[] = LanguageItem::random($_GET['Scope'], null, $TYPES[$i]);
    }
    
    $split = 1;
    $split_inc = 1;
    foreach($language_items as $index => $word) { 
        echo $word->get_text() . " "; 
        if (($index + 1) % $split == 0) {
            echo "\n";
            $split_inc++;
            $split += $split_inc;
        }
    }
    
    echo "\n\n";
     
    $split = 1;
    $split_inc = 1;
    foreach($language_items as $index => $word) { 
        echo WordType::load($word->get_type())->get_name() . " "; 
        if (($index + 1) % $split == 0) {
            echo "\n";
            $split_inc++;
            $split += $split_inc;
        }
    }
?>
<?php
    require '../loader.php';
?>
{
    "Data" : [
<?php    
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
    $first = true; 
    foreach($language_items as $index => $word) { 
        if ($first) { $first = false; } else { echo ","; }
        ?>
        { "<?php echo $word->get_ID(); ?>" : "<?php echo $word->get_text(); ?>" }
    <?php }
?>
    ],
    "Rating" : null,
    "Scope" : <?php echo $_GET['TargetScope']; ?>
}
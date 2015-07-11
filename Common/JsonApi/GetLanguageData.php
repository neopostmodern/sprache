<?php
    require '../loader.php';
?>

{
    "Data" : [
        <?php 
            $language_items = Array();
            if (isset($_GET['CompleteResponse'])) {
                $response = Response::random($_GET['Scope']);
                $response_items = ResponseItem::all($response->get_ID());
                foreach($response_items as $response_item) {
                    $language_items[] = LanguageItem::load($response_item->get_LanguageItemId());
                }
            } else {
                for ($i = isset($_GET['Start']) ? $_GET['Start'] : 0; $i < $_GET['End']; $i++) { 
                    $language_items[] = LanguageItem::random($_GET['Scope'], $i); 
                }
            }
            $first = true; 
            foreach($language_items as $word) {
                if ($first) { $first = false; } else { echo ","; }
                ?>
                        { "<?php echo $word->get_ID(); ?>" : "<?php echo $word->get_text(); ?>" }
        <?php } ?>
    ],
    "Rating" : null,
    "Scope" : <?php echo $_GET['Scope']; ?>
}

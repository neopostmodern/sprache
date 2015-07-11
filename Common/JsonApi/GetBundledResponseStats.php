<?php
    include '../loader.php';

    $responses = Response::loop($_GET['Scope']);
    
    $stats = Array();
    foreach($responses as $response) {
        $items = $response->get_items();
        $key = "";
        foreach($items as $item) {
            $key .= $item->get_LanguageItemId() . "-";   
        }
        if (! array_key_exists($key, $stats)) {
            $stats[$key] = Array(
                'rating' => 0,
                'count' => 0,
                'words' => Array()
            );
            foreach($items as $item) {
                $stats[$key]['words'][] = LanguageItem::load($item->get_LanguageItemId())->get_text(); 
            }
        }
        $stats[$key]['rating'] = ($stats[$key]['rating'] * $stats[$key]['count'] + $response->get_rating()) / ($stats[$key]['count'] + 1);
        $stats[$key]['count'] += 1;
    }
    
    $first = true;
?>
{
    "Ratings" : [
        <?php foreach($stats as $stat) { 
            if ($first) { $first = false; } else { echo ","; } ?>
            {
                "Rating" : <?php echo round($stat['rating'], 2); ?>,
                "Count" : <?php echo $stat['count']; ?>,
                "Words" : [
                    <?php 
                    $first_inner = true;
                    foreach($stat['words'] as $word) { 
                        if ($first_inner) { $first_inner = false; } else { echo ","; }?>
                        "<?php echo $word; ?>"
                    <?php } ?>
                ]
            }
        <?php } ?>
    ]
}

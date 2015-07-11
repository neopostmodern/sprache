<?php
    class Response {
        private $ID;
        function get_ID() {
            return $this->ID;
        }
        function set_ID($value) {
            $this->ID = $value;
        }
        
        private $rating;
        function get_rating() {
            return $this->rating;
        }
        function set_rating($value) {
            $this->rating = $value;
        }
        
        private $scope;
        function get_scope() {
            return $this->scope;
        }
        function set_scope($value) {
            $this->scope = $value;
        }
        
        private $items;
        function get_items() {
            return $this->items;
        }
        function set_items($value) {
            $this->items = $value;
        }
        function add_item($value) {
            $this->items[] = $value;
            return end($this->items);
        }
        function load_items() {
            $this->items = ResponseItem::all($this->ID);
        }
        
        function __construct($rating, $scope, $ID = null) {
            $this->ID = $ID;
            $this->rating = $rating;
            $this->scope = $scope;
            $this->items = Array();
        }
        
        function save() {
            $return = false;
            
            try {
                $this->ID = ReadWrite::save('Responses', Array($this->rating, $this->scope), Array("Rating", "Scope"));
                
                $return = true;
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
            
            return $return;
        }
        
        static function random($scope) {            
            $data = ReadWrite::random('Responses', Array("ID", "Rating", "Scope"), Array("ID", "Rating", "Scope"), "`Scope`=" . $scope);
            return new Response($data['Rating'], $data['Scope'], $data['ID']);
        }
        
        static function load($ID) {
            $data = ReadWrite::load('Responses', $ID, Array("ID", "Rating", "Scope"), Array("ID", "Rating", "Scope"));
            return new Response($data['Rating'], $data['Scope'], $data['ID']);            
        }
        
        static function loop($scope) {            
            $return = Array();
            foreach (ReadWrite::all("Responses", Array("Scope"), Array($scope)) as $ID) {
                $return[] = Response::load($ID);
                $return[count($return) - 1]->load_items();
            }
            return $return;
        }
    }
?>

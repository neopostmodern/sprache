<?php
    class ResponseItem {
        private $ID;
        function get_ID() {
            return $this->ID;
        }
        function set_ID($value) {
            $this->ID = $value;
        }
        
        private $ResponseId;
        function get_ResponseId() {
            return $this->ResponseId;
        }
        function set_ResponseId($value) {
            $this->ResponseId = $value;
        }
        
        private $LanguageItemId;
        function get_LanguageItemId() {
            return $this->LanguageItemId;
        }
        function set_LanguageItemId($value) {
            $this->LanguageItemId = $value;
        }
        
        function __construct($ResponseId, $LanguageItemId, $ID = null) {
            $this->ID = $ID;
            $this->ResponseId = $ResponseId;
            $this->LanguageItemId = $LanguageItemId;
        }
        
        function save() {
            return ReadWrite::save('ResponseItems', Array($this->ResponseId, $this->LanguageItemId), Array("ResponseId", "LanguageItemId"));  
        }
        
        static function load($ID) {
            $data = ReadWrite::load("ResponseItems", $ID, Array("ID", "ResponseId", "LanguageItemId"), Array("ID", "ResponseId", "LanguageItemId"));
            return new ResponseItem($data['ResponseId'], $data['LanguageItemId'], $data['ID']);
        }
        
        static function all($response_ID) {
            $return = Array();
            foreach (ReadWrite::all("ResponseItems", Array("ResponseId"), Array($response_ID)) as $ID) {
                $return[] = ResponseItem::load($ID);
            }
            return $return;
        }
    }
?>

<?php
    class LanguageItem {
        const SUBSTANTIV = 1;
        const ADJEKTIV = 2;
        const VERB = 3;

        private $ID;
        function get_ID() {
            return $this->ID;
        }
        function set_ID($value) {
            $this->ID = $value;
        }
        
        private $text;
        function get_text() {
            return $this->text;
        }
        function set_text($value) {
            $this->text = $value;
        }
        
        private $scope;
        function get_scope() {
            return $this->scope;
        }
        function set_scope($value) {
            $this->scope = $value;
        }
        
        private $position;
        function get_position() {
            return $this->position;
        }
        function set_position($value) {
            $this->position = $value;
        }
        
        private $type;
        function get_type() {
            return $this->type;
        }
        function set_type($value) {
            $this->type = $value;
        }
        
        function __construct($text, $scope, $position, $type, $ID = null) {
            $this->ID = $ID;
            $this->text = $text;      
            $this->scope = $scope; 
            $this->type = $type;
            $this->position = $position; 
        }
        
        function checkExistence() {
            $ids = ReadWrite::All('LanguageItems', Array('Name', 'Type'), Array($this->text, $this->type));
            if ($ids && count($ids) > 0) {
                $this->ID = $ids[0];
                return true;
            }
            return false;
        }
        
        function save() {            
            $this->ID = ReadWrite::save('LanguageItems', Array($this->text, $this->position, $this->scope, $this->type), Array('Name', 'Position', 'Scope', 'Type'));
            return $this->ID;
        }
        
        static function load($ID) {
            $data = ReadWrite::load('LanguageItems', $ID, Array('ID', 'Name', 'Position', 'Scope', 'Type'), Array('ID', 'Text', 'Position', 'Scope', 'Type'));
            return new LanguageItem($data['Text'], $data['Scope'], $data['Position'], $data['Type'], $data['ID']);
        }
        
        static function random($scope, $position = null, $type = null) {
            $where = "`Scope`=" . $scope;
            if (isset($position)) {
                $where .= " AND `Position`=" . $position;
            }
            if ($type) {
                $where .= " AND `Type`=" . $type;
            }
            $data = ReadWrite::random('LanguageItems', Array('ID', 'Name', 'Position', 'Scope', 'Type'), Array('ID', 'Text', 'Position', 'Scope', 'Type'), $where);
            
            return new LanguageItem($data['Text'], $data['Scope'], $data['Position'], $data['Type'], $data['ID']);
        }
    }
?>

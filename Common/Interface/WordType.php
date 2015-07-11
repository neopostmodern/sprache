<?php
    class WordType {
        private $ID;
        function get_ID() {
            return $this->ID;
        }
        function set_ID($value) {
            $this->ID = $value;
        }
        
        private $name;
        function get_name() {
            return $this->name;
        }
        function set_name($value) {
            $this->name = $value;
        }
        
        private $example;
        function get_example() {
            return $this->example;
        }
        function set_example($value) {
            $this->example = $value;
        }
        
        function __construct($name, $example, $ID = null) {
            $this->ID = $ID;
            $this->name = $name;
            $this->example = $example;
        }
        
        static function load($ID) {
            $data = ReadWrite::load("Types", $ID, Array("ID", "Name", "Example"), Array("ID", "Name", "Example"));
            return new WordType($data['Name'], $data['Example'], $data['ID']);
        }
        
        static function loop() {
            $return = Array();
            foreach (ReadWrite::loop("Types") as $ID) {
                $return[] = WordType::load($ID);
            }
            return $return;
        }
    }
?>

<?php
/**
 * Created by PhpStorm.
 * User: aakash
 * Date: 3/12/19
 * Time: 11:34 PM
 */
main::start("data.csv");
class main {
    static public function start($filename) {
        $records = csv::getRecords($filename);
        $table = html::generateTable($records);
        system::printPage($table);
    }
}
class html {
    static public function generateTable($records) {
        $html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"><script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>';
        $html .= '<html>';
        $html .= '<table class="table table-hover">';
        $html .='<table class = "table table-bordered ">';
        $count = 0;
        foreach ($records as $record) {
            $array = $record->returnArray();
            $fields = array_keys($array);
            $values = array_values($array);

            if($count == 0) {
                $var = self::generateHtml($fields, $html, $head = '1');
                $html = $var;
                $var = self::generateHtml($values, $html, $head = '0');
                $html = $var;

            } else
            {
                $var = self::generateHtml($values, $html, $head = '0');
                $html = $var;

            }
            $count++;
        }
        $html .= '</table></html>';
        return $html;
    }
    public static function generateHtml($data, $html, $head) {
        if($head == '1') {
            $html .= '<thead>';
            $html .= '<tr>';
            foreach ($data as $key => $value) {
                $html .= '<th scope ="col">' . ($value) . '</th>';
            }
            $html .= '</tr>';
            $html .= '</thead>';
        } else {
            $html .= '<tbody>';
            $html .= '<tr>';
            foreach ($data as $key => $value) {
                $html .= '<td>' . ($value) . '</td>';
            }
            $html .= '</tr>';
            $html .= '<tbody>';
        }
        return $html;
    }
}
class csv {
    static public function getRecords($filename) {
        $file = fopen($filename,"r");
        $fieldName = array();
        $count = 0;
        while(! feof($file))
        {
            $record = fgetcsv($file);
            if($count == 0) {
                $fieldName = $record;
            } else {
                $records[] = recordFactory::create($fieldName, $record);
            }
            $count++;
        }
        fclose($file);
        return $records;
    }
}
class record {
    public function __construct(Array $fieldName = null, $values = null) {
        $record = array_combine($fieldName, $values);
        foreach ($record as $property => $value) {
            $this->createProperty($property, $value);
        }
    }
    public function returnArray() {
        $array = (array) $this;
        return $array;
    }
    public function createProperty($name = 'first', $value = 'AS') {
        $this->{$name} = $value;
    }
}
class recordFactory {
    public static function create(Array $fieldName = null, Array $values = null){
        $record = new record($fieldName, $values);
        return $record;
    }
}
class system {
    public static function printPage($page) {
        echo $page;
    }
}


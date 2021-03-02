<?php
class Connect extends PDO
{
  public function __construct()
  {
    parent::__construct(
      "mysql:host=localhost;dbname=cms",
      "root",
      ""
    );
  }
}

class Bread
{
var $table;
	
//INSTENTIATE
	public function __construct($table){
	    $this->table = $table;
	}

//STRING HELPERS
//MAKE STRINGS FROM ARRAY
public function arr_to_str($array, $values = TRUE) {

if($values) {
    foreach ($array as $value) {
        if (is_numeric($value)) {
            $string .= $value.",";
        } else { 
            $string .= "'$value',";
        }
    }
    $string = trim($string, ",");
} else {
    foreach ($array as $key => $value) {
        $string .= "`$key`,";
    }
    $string = trim($string, ",");
}
    return $string;
}

//MAKE KEY = VALUE PAIRS
public function arr_to_pairs($array) {
    foreach ($array as $key => $value) {
       $string .= "`$key` = '$value',";
    }
    $string = trim($string, ",");
    return $string;
}

   //BREAD METHODS
   //BROWSE
   public function browse($args = '')
   {
      $db = new Connect;
      $result = [];

      $data = $db->prepare(sprintf('SELECT * FROM %s %s', $this->table, $args));
      $data->execute();
      $result = $data->fetchAll();

      return json_encode($result);
      $db->null;
   }

   //READ
   public function read($id)
   {  
      $db = new Connect;
      $result = [];

      $data = $db->prepare(sprintf('SELECT * FROM %s WHERE id = %s', $this->table, $id));
      $data->execute();
      $result = $data->fetch();

    return json_encode($result);
    $db->null;
   }

   //EDIT
   public function edit($data=[], $id)
   {
       $db = new Connect;
       $result = [];

       $vals = $this->arr_to_pairs($data, false);
       
       $data = $db->prepare(sprintf("UPDATE %s SET %s WHERE `id` = %s;", $this->table, $vals, $id));

return ($data->execute()) ? TRUE : FALSE;
	       $db->null;
   }

   //ADD
   public function add($data=[])
   {
       $db = new Connect;
       $result = [];

       $cols = $this->arr_to_str($data, false);
       $vals = $this->arr_to_str($data);
       
       $data = $db->prepare(sprintf("INSERT INTO %s(%s) VALUES(%s);", $this->table, $cols, $vals));

return ($data->execute()) ? TRUE : FALSE;
	       $db->null;
   }

   //DELETE
   public function delete($id)
   {
       $db = new Connect;
       if ($this->read('id', $id)) {
           $data = $db->prepare(sprintf("DELETE FROM %s WHERE id = %s", $this->table, $id));
       return ($data->execute()) ? TRUE : FALSE;
       $db->null;
       }
   }
}

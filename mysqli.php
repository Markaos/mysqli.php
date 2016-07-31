<?php

class SQL{
  public $connection;
  
  public function __construct(){
    $this->connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
  }
  
  public function getTable($table, $mask="") {
    $return = array();
    $sql = "SELECT * FROM $table ";
    $limit = 0;
    $rate = "DESC";
    
    if(is_array($mask)){
      if (@count($mask) == 1 && isset($mask['count'])) $limit = $mask['count'];
      else {
        $sql .= "WHERE ";
        foreach($mask as $key => $content){
          if($key=="count"){
            $limit=$content;
            continue;
          }
          if($key=="rate"){
            $rate = $content;
            continue;
          }
          $sql .= "$key='$content', ";
        }
        $sql = substr($sql,0,strlen($sql)-2);
      }
    }
    $sql .= " ORDER BY ID $rate";
    if($limit!=0) $sql .= " LIMIT ".$limit;
    $res = mysqli_query($this->connection,$sql);
    while($retertretert = @mysqli_fetch_array($res)){
      $return[] = $retertretert;
    }
    return $return;
  }
  
  public function insertInto($table, $values) {
    $sql = "INSERT INTO $table ";
    
    if(is_array($values) && !isset($values[0])){
      $sql .= "(";
      
      foreach($values as $key=>$value){
        $sql .= "$key,";
      }
      
      $sql = substr($sql,0,strlen($sql)-1);
      $sql .= ") VALUES (";
      
      foreach($values as $value){
        $sql .= "'$value',";
      }
      
      $sql = substr($sql,0,strlen($sql)-1);
      $sql .= ")";
      mysqli_query($this->connection,$sql);
    }
  }
  
  public function update($table, $mask, $values) {
    $limit = 0;
    $sql = "UPDATE $table SET ";
    
    foreach($values as $key=>$value){
      $sql .= "$key='$value',";
    }
    
    $sql = substr($sql,0,strlen($sql)-1);
    $sql .= " WHERE ";
    
    foreach($mask as $key=>$value){
      if($key=="limit"){
        $limit = $value;
        continue;
      }
      $sql .= "$key='$value',";
    }
    
    $sql = substr($sql,0,strlen($sql)-1);
    if($limit!=0) $sql .= " LIMIT $limit";
    
    mysqli_query($this->connection,$sql);
  }
  
  public function delete($table, $mask="") {
    $limit = 0;
    $sql = "DELETE FROM $table";
    
    if(is_array($mask)){
      $sql .= " WHERE ";
      
      foreach($mask as $key=>$content){
        if($key=="count"){
          $limit = $content;
          continue;
        }
        $sql .= "$key='$content' AND ";
      }
      
      $sql = substr($sql,0,strlen($sql)-5);
      if($limit!=0) $sql .= " LIMIT $limit";
    }
    
    mysqli_query($this->connection,$sql);
  }
  
  public function getError() {
    return mysqli_error($this->connection);
  }
  
  public function close() {
    mysqli_close($this->connection);
  }
}

?>

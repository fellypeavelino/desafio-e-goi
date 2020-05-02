<?php

namespace Util;
/**
 *
 */
class UtilData
{

  function __construct(){}

  public function getData():array
  {
    $list = file_get_contents(__DIR__."/data/categories.json");
    return json_decode($list);
  }

  public function setData(array $list)
  {
    $data = json_encode($list);
    file_put_contents(__DIR__."/data/categories.json", $data);
  }

  public function patchData(object $category, array $data): object
  {
    try {
      $category = (array) $category;
      foreach ($data as $key => $value) {
        $category[$key] = $value;
      }
      return $category = (object) $category;      
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }
}

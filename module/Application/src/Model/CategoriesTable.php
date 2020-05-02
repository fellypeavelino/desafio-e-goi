<?php

namespace Application\Model;

class CategoriesTable
{

  public __construct(){
    $this->categories = [];
  }

	private $categories;

  public setCategories(array ...$categories){
    $this->categories = $categories;
  }

  public getCategories():array{
    return $this->categories;
  }

}

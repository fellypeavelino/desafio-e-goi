<?php

namespace Application\Model;

class CategoryTable
{

	public function __construct(
		$id, int $category_id, string $name, string $created, string $modified
	){
		$this->id = $id;
		$this->category_id = $category_id;
		$this->name = $name;
		$this->created = $created;
		$this->modified = $modified;
	}

	public $id;
	public $category_id;
	public $name;
	public $created;
	public $modified;

}

<?php

namespace Diging\ChurchtoolsSDK\Models;

class Calendar extends Model{

	protected $functionCall = 'getMasterData';

	protected $module = 'churchcal';

	protected $mapping = [
		'bezeichnung' => 'name'
	];

	protected $fillable = ['id','category','name','sortKey','color','textColor'];

	public static function get()
	{
		$get = parent::get();

		$collection = (new static)->newCollection();
		foreach($get->category as $key => $category){
			$collection->put($key,new static((array) $category));
		}
		return $collection;
	}

}

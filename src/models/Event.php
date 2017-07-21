<?php

namespace Diging\ChurchtoolsAPI\Models;

class Event extends Model{
	
	protected $functionCall = 'getCalPerCategory';

	protected $module = 'churchcal';

	protected $mapping = [
		'bezeichnung' 	=> 'name',
		'ort'			=> 'place',
		'notizen'		=> 'description'
		];

	protected $guarded = [];

	protected $dates = ['startdate','enddate'];

	protected $dateFormat = 'Y-m-d H:i:s';


	static function getByCategories($categories = []){

		$instance = new static;
		$arrCalendars = $instance->newRequest('getCalPerCategory','churchcal',array('category_ids[]'=>$categories));
		
		$collection = $instance->newCollection();
        foreach($arrCalendars as $cal=>$event){     	
        	foreach($event as $key=>$attributes){
        		$collection->put($key,new Event((array) $attributes));
        	}	
        }
        
        return $collection;
	}
}
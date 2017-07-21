<?php

namespace Diging\ChurchtoolsSDK\Models;

use Diging\ChurchtoolsSDK\Request;

class Person extends Model{
	
	protected $functionCall = 'getAllPersonData';

	protected $module = 'churchdb';

	protected $mapping = array
	(
		'id' 			=> 'id',
		'p_id'			=> 'id',
		'vorname' 		=> 'first_name',
		'name' 			=> 'last_name',
		'spitzname'		=> 'nickname',
		'sex'			=> 'gender',
		'geschlecht_no' => 'gender',
		'em'			=> 'email',
		'email' 		=> 'email',
		'geburtsdatum' 	=> 'birthday',
		'geb'			=> 'birthday',
		'status_id' 	=> 'status',
		'station_id' 	=> 'station',
		'familienstand_no' => 'family_status'
	);

	protected $fillable = ['id','first_name','last_name','gender','birthday','status','station','family_status','imageurl','email'];


	public function getStatusAttribute($value)
	{
		return $this->getRelatedInformation('status',$value);
	}

	public function getGenderAttribute($value)
	{
		return $this->getRelatedInformation('gender',$value);
	}

	public function getStationAttribute($value)
	{
		return $this->getRelatedInformation('station',$value);
		
	}

	public function getFamilyStatusAttribute($value)
	{
		return $this->getRelatedInformation('family_status',$value);
	}

	public function getRelatedInformation(string $attribute, $value)
	{
		if($value)
		{
			$masterdata = ChurchDBMasterdata::get();
			return $masterdata->$attribute[$value];	
		}
	}


	public function getImageurlAttribute($value){
		return 'https://etg.church.tools/?q=churchdb/filedownload&filename='.$value;
	}


	public static function findByName($query)
	{
		$instance = new static;
		$result = $instance->newRequest('getPersonByName','churchdb',array('searchpattern' => $query));
		
		$collection = $instance->newCollection();
		foreach($result['data'] as $person)
		{
			$collection->add(new Person((array) $person));
		}

		return $collection;
	}
}
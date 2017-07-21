<?php

namespace Diging\ChurchtoolsAPI\Models;

class ChurchDBMasterdata extends Model{

	protected $functionCall = 'getMasterData';

	protected $module = 'churchdb';

	protected $mapping = 
	[
		'sex' => 'gender',
		'familyStatus' => 'family_status'
	];

	protected $fillable = ['status','station','dep','gender','groupTypes','family_status'];

}
<?php

/*
 * This file is part of Contao.
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

use Diging\ChurchtoolsAPI\Request;
use Diging\ChurchtoolsAPI\Models\Person;
use Diging\ChurchtoolsAPI\Models\Event;
use Diging\ChurchtoolsAPI\Models\Calendar;
use Diging\ChurchtoolsAPI\Models\ChurchDBMasterdata;


/** @var Composer\Autoload\ClassLoader */
require __DIR__.'/vendor/autoload.php';

session_start();

//$person = Person::find(1);
//var_dump($person->gender);
//var_dump(Person::all());
//var_dump(Person::find(1));
//var_dump($person);


//var_dump(Event::getByCategories([16,17])); //-> by catgories gibt collection zurück
//var_dump(Event::all());
//var_dump(Calendar::get());
//Event::find(5) -> find gibt immer Model zurück

//var_dump(Person::findByName('Samuel'));

//$req = new Request('getAllPersonData','churchdb');
//var_dump($req->execute());

//$req = new Request();
//$req->download($lukas->imageurl);

//$masterdata = ChurchDBMasterdata::get();

//var_dump($masterdata->status);
//var_dump($masterdata);

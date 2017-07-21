<?php

namespace Diging\ChurchtoolsSDK\Models;

use Diging\ChurchtoolsSDK\Request;
use Illuminate\Support\Str;


abstract class Model extends \Illuminate\Database\Eloquent\Model{

	protected $functionCall = null;

    protected $mapping = [];
   
   /**
    * Find a specific record by id
    *  
    * @param $id
    * @return 
    * 
    */
    public static function find($id,$details=false)
    {
        return static::all()->find($id);
    }

//get wenn nur ein model zurückerwartet wird
    public static function get()
    {
        $instance = new static;

        $request = new Request($instance->functionCall, $instance->module);        

        $instance->fill($request->execute());

        return $instance;
    }

//all wenn eine collection zurückerwartet wird
    public static function all($column='')
    {
        $instance = new static;

        $result = $instance->newRequest($instance->functionCall,$instance->module);

        $collection = $instance->newCollection();
        foreach($result as $key => $object){
            $collection->put($key, new static((array) $object));
        }
        return $collection;
    }


    public function newRequest(string $function, string $module, $parameters = [])
    {
        $request = new Request($function,$module);
        
        $request->addParameters($parameters);
        
        return $request->execute();
    }

    /**
     * Get the fillable attributes of a given array.
     *
     * @param  array  $attributes
     * @return array
     */
    protected function fillableFromArray(array $attributes)
    {
        $attributes = $this->getMappedAttributes($attributes);

        return parent::fillableFromArray($attributes);
    }

    protected function getMappedAttributes(array $attributes)
    {
        foreach($attributes as $key => $value)
        {
            if(isset($this->mapping[$key])){
                $attributes[$this->mapping[$key]] = $attributes[$key];
                unset($attributes[$key]);    
            }
        }
        return $attributes;
    }


}
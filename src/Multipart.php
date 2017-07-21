<?php 

namespace Diging\ChurchtoolsSDK;

class Multipart
{
	
	protected $parts = array();

	public function __construct()
	{
		$this->setInitialValues();
	}

	protected function setInitialValues()
	{
		$this->add('directtool','yes');
	}

	public function add($name, $contents)
	{
		$this->parts[] = array(
			'name' => $name,
			'contents' => $contents
			);
	}

	public function asArray()
	{
		return $this->parts;
	}
}
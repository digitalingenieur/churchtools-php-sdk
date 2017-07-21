<?php

namespace Diging\ChurchtoolsAPI;

use Symfony\Component\Dotenv\Dotenv;

class Request{
	
	protected $multipart = null;

	protected $client = null;

	protected $response = null;

	protected $data = null;

	protected $func = '';

	protected $endpoint = '';

	protected $cookieJar = null;


	public function __construct(string $func = '', string $module=''){
		
		$this->func = $func;

		$this->module = $module;

		$this->boot();

		$this->prepare();
	}

	protected function boot()
	{
		$this->loadConfigFromEnvironment();

		$this->cookieJar = new \GuzzleHttp\Cookie\SessionCookieJar('cookiejar', true);
		$this->client = new \GuzzleHttp\Client(['cookie' => true]);
		
		$this->multipart = new Multipart();
	}

	protected function prepare()
	{	
		$this->multipart->add('func', $this->func);

		$this->setEndpoint();
	}

	public function download($url){
		$this->client->request('GET',$url,[
			'sink'=>'picture.jpg',
			'cookies' => $this->cookieJar
			]);
	}

	public function execute()
	{
		$this->response = $this->client->request('POST', $this->getChurchtoolsUrl(), [
				'multipart' => $this->multipart->asArray(),
				'cookies' => $this->cookieJar
			]);
	
		$arrJson = json_decode($this->response->getBody());
		
		if($arrJson->status == 'error')
		{
			
			if($arrJson->message=="Session expired!"){
				//Try to log in
				$this->multipart->add('func', 'login');
				$this->setEndpoint('/?q=login');
				$this->setChurchtoolsCredentials();
				$this->execute();

				$this->prepare();
				$this->execute();
			}
			else{
				//var_dump($arrJson);
				throw new \Exception($arrJson->message, 1);

			}
		}
		else{
			$this->data = (array) $arrJson->data;	

		}
		//var_dump($this->data);
		return $this->data;
	}

	public function addParameters(array $parameters){
		foreach($parameters as $key => $parameter){
			if(gettype($parameter) == "array"){
				foreach($parameter as $param){
					$this->addParameter($key,$param);
				}
			}
			else{
				$this->addParameter($key,$parameter);	
			}
			
		}
	}

	public function addParameter(string $key, string $parameter){
		$this->multipart->add($key,$parameter);
	}

	protected function loadConfigFromEnvironment()
	{
		$dotenvFile = __DIR__.'/../../.env';
		if(file_exists($dotenvFile)){
			(new Dotenv())->load($dotenvFile);	
		}

		if(getenv('CHURCHTOOLS_URL') != false && getenv('CHURCHTOOLS_USERNAME') != false && getenv('CHURCHTOOLS_PASSWORD') != false){
			$this->config['url'] = getenv('CHURCHTOOLS_URL');
			$this->config['username'] = getenv('CHURCHTOOLS_USERNAME');
			$this->config['password'] = getenv('CHURCHTOOLS_PASSWORD');	
		}
		else{
			throw new \Exception("Churchtools credentials missing in environment variable", 1);
		}
		
	}

	public function setChurchtoolsCredentials()
	{
		$this->multipart->add('email', $this->config['username']);
		$this->multipart->add('password',$this->config['password']);	
	}

	public function getChurchtoolsUrl()
	{
		return $this->config['url'].$this->getEndpoint();
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function setCookieJar($cookieJar)
	{
		$this->cookieJar = $cookieJar;
	}

	public function getCookieJar()
	{
		return $this->cookieJar;
	}

	public function setFunction($func){
		$this->func = $func;
	}

	public function getFunction(){
		if(empty($this->func)){
			return 'login';
		}
		else{
			return $this->func;
		}
	}

	public function setEndpoint(string $endpoint='')
	{
		if($endpoint!=''){
			$this->endpoint = $endpoint;
		}
		else{
			$this->endpoint = '/index.php?q='.$this->module.'/ajax';
		}
		
	}

	public function getEndpoint()
	{
		return $this->endpoint;
	}
}
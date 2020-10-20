<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

class Handler {

	private $client;
	private $uri = ["posts", "comments", "albums", "photos", "todos", "users"];

	public function Handler(){
		$this->client = new Client([
			'base_uri' => 'https://jsonplaceholder.typicode.com',     
			'timeout'  => 2.0
		]);
	}

	public function getClient(){
		return $this->client;
	}
	
	public function getData($uri = "posts", $id = "", $conditions = array ()){
		$stringConditions = "";
		if(!empty($conditions))
			$stringConditions = "?".http_build_query($conditions);
		if(in_array($uri, $this->uri)){
			try {
				return array("status" => 1 , "content" => json_decode($this->client->get("$uri/$id$stringConditions")->getBody()->getContents()));
			} catch (Exception $e) {
			    return array("status" => 0 , "content" => $e->getMessage());
			}
		}else
			return array("status" => 0 , "content" => "the provided URI option doesn't exists");	
	}

	public function deleteData($uri = "", $id = ""){
		if(empty($uri) or empty($id))
			return array("status" => 0 , "content" => "URI and ID are necessary to continue");

		if(in_array($uri, $this->uri)){
			try {
				if($this->client->delete("$uri/$id")->getStatusCode() == 200)
					return array("status" => 1 , "content" => "Success delete");
			} catch (Exception $e) {
			    return array("status" => 0 , "content" => $e->getMessage());
			}
		}else
			return array("status" => 0 , "content" => "the provided URI option doesn't exists");
	}

	public function insertData($uri = "", $data = array()){
		if(empty($data))
			echo "you must have some data";

		if(in_array($uri, $this->uri)){
			try {
				if($this->client->post("$uri", ["json" => $data])->getStatusCode() == 201)
					return array("status" => 1 , "content" => "Success Create");
			} catch (Exception $e) {
			    return array("status" => 0 , "content" => $e->getMessage());
			}
		}else
			return array("status" => 0 , "content" => "the provided URI option doesn't exists");
	}

	public function updateData($uri = "", $id = "", $data = array()){
		if(empty($uri) or empty($id) or empty($data))
			return array("status" => 0 , "content" => "URI, ID and Data are necessary to continue");

		if(in_array($uri, $this->uri)){
			try {
				if($this->client->put("$uri/$id", ["json" => $data])->getStatusCode() == 200)
					return array("status" => 1 , "content" => "Success Update");
			} catch (Exception $e) {
			    return array("status" => 0 , "content" => $e->getMessage());
			}
		}else
			return array("status" => 0 , "content" => "the provided URI option doesn't exists");
	}
}

#implementation
$handler  = new Handler;

#Delete Resource
echo("\n ---Delete ---\n");
$response = $handler->deleteData("users", 1);
if($response['status'])
	var_dump($response['content']);
else
	var_dump($response['content']);

#Get Resource
echo("\n --- Read ---\n");
$response = $handler->getData("users", "", array("id" => 1));
if($response['status']){
	if(is_array($response['content'])){
		foreach($response['content'] as $value){
			var_dump($value);	
		}
	}else
		var_dump($response['content']);
}else
	var_dump($response['content']);

#Insert Resource
echo("\n ---Insert ---\n");

$dataPost     = array("userId" => 1, "title" => "I'm a title", "body" => "And I'm the body");
$dataComments = array("postId"=> 1, "name"=> "Comment's name", "email"=> "example@example.com", "body"=> "body's comment");
$dataAlbums   = array("userId" => 1, "title" => "I'm a Album's title");
$dataPhotos   = array("albumId"=> 1, "title"=> "Photo's Title", "url"=> "https://via.placeholder.com/600/92c952", "thumbnailUrl"=> "https://via.placeholder.com/150/92c952");
$dataTodos    = array("userId"=> 1, "title"=> "Todo's title", "completed"=> false);
$dataUsers    = array("name"=> "Diego Fonseca", "username"=> "Diego", "email"=> "diego@example.com");

$response = $handler->insertData("posts", $dataPost);
if($response)
	var_dump($response['content']);
$response = $handler->insertData("comments", $dataComments);
if($response)
	var_dump($response['content']);
$response = $handler->insertData("albums", $dataAlbums);
if($response)
	var_dump($response['content']);
$response = $handler->insertData("photos", $dataPhotos);
if($response)
	var_dump($response['content']);
$response = $handler->insertData("todos", $dataTodos);
if($response)
	var_dump($response['content']);
$response = $handler->insertData("users", $dataUsers);
if($response)
	var_dump($response['content']);

#Update Resource
echo("\n --- Update ---\n");

$dataPost     = array("userId" => 2, "title" => "I'm a title", "body" => "And I'm the body");
$dataComments = array("postId"=> 2, "name"=> "Comment's name", "email"=> "example@example.com", "body"=> "body's comment");
$dataAlbums   = array("userId" => 2, "title" => "I'm a Album's title");
$dataPhotos   = array("albumId"=> 2, "title"=> "Photo's Title", "url"=> "https://via.placeholder.com/600/92c952", "thumbnailUrl"=> "https://via.placeholder.com/250/92c952");
$dataTodos    = array("userId"=> 2, "title"=> "Todo's title", "completed"=> false);
$dataUsers    = array("name"=> "Diego Fernando", "username"=> "Diego", "email"=> "diego@example.com");

$response = $handler->updateData("posts", 1, $dataPost);
if($response)
	var_dump($response['content']);
$response = $handler->updateData("comments", 1, $dataComments);
if($response)
	var_dump($response['content']);
$response = $handler->updateData("albums", 1, $dataAlbums);
if($response)
	var_dump($response['content']);
$response = $handler->updateData("photos", 1, $dataPhotos);
if($response)
	var_dump($response['content']);
$response = $handler->updateData("todos", 1, $dataTodos);
if($response)
	var_dump($response['content']);
$response = $handler->updateData("users", 1, $dataUsers);
if($response)
	var_dump($response['content']);
?>

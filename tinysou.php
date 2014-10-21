<?php

class TinySouException extends Exception {
  public function __construct($message, $code, Exception $previous = null) {
    parent::__construct($message, $code);   // For PHP 5.2.x
  }

  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}

class TinySouBadRequestException extends TinySouException {
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, 400, $previous);
  }
}

class TinySouAuthorizationException extends TinySouException {
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, 401, $previous);
  }
}

class TinySouForbiddenException extends TinySouException {
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, 403, $previous);
  }
}

class TinySouNotFoundException extends TinySouException {
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, 404, $previous);
  }
}

class TinySouNotAcceptableException extends TinySouException {
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, 406, $previous);
  }
}

class TinySouServiceUnavailable extends TinySouException {
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, 503, $previous);
  }
}

class TinySou {
  const VERSION   = '0.0.1';
  const BASE_URI  = 'http://api.tinysou.com/v1/'

  private $_token;
  private $_timeout = 30;

  protected $base_uri;

  /**
   * Init TinySou Client
   * @param $token Auth Token
   *
   * @return object
   */
  public function __construct($token, $timeout = 30) {
    $this->_token = $token;
    $this->_timeout = $timeout;
    $this->base_uri = self::BASE_URI;

    if(!function_exists('curl_init')){
      throw new Exception('TinySou requires the CURL PHP extension.');
    }

    if(!function_exists('json_decode')){
      throw new Exception('TinySou requires the JSON PHP extension.');
    }
  }

  /**
   * Get current lib version
   * @return string
   */
  public function version() {
    return self::VERSION;
  }

  /**
   * List engines
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#2-1-罗列-Engines
   */
  public function engines() {
    return $this->get($this->engines_path());
  }

  /**
   * Create an engine
   * @param array $attrs Properties of new engine
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#2-2-创建一个-Engine
   */
  public function create_engine($attrs) {
    return $this->post($this->engines_path(), array(), $attrs);
  }

  /**
   * Get an engine
   * @param string $engine_name Engine's name
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#2-3-获取一个-Engine
   */
  public function engine($engine_name) {
    return $this->get($this->engine_path($engine_name));
  }

  /**
   * Update an engine
   * @param string $engine_name Engine's name
   * @param array $attrs New properties of the engine
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#2-4-更新一个-Engine
   */
  public function update_engine($engine_name, $attrs) {
    return $this->put($this->engine_path($engine_name), array(), $attrs);
  }

  /**
   * Delete an engine
   * @param string $engine_name Engine's name
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#2-5-删除一个-Engine
   */
  public function delete_engine($engine_name){
    return $this->delete($this->engine_path($engine_name));
  }

  /**
   * List collections
   * @param string $engine_name Engine's name
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#3-6-罗列-Collections
   */
  public function collections($engine_name){
    return $this->get($this->collections_path($engine_name));
  }

  /**
   * Create a collection
   * @param string $engine_name Engine's name
   * @param string $attrs Properties of new collection
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#3-7-创建一个-Collection
   */
  public function create_collection($engine_name, $attrs){
    return $this->post($this->collections_path($engine_name), array(), $attrs);
  }

  /**
   * Get a collection
   * @param string $engine_name Engine's name
   * @param string $collection_name Collection's name
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#3-8-获取一个-Collection
   */
  public function collection($engine_name, $collection_name) {
    return $this->get($this->collection_path($engine_name, $collection_name));
  }

  /**
   * Delete a collection
   * @param string $engine_name Engine's name
   * @param string $collection_name Collection's name
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#3-9-删除一个-Collection
   */
  public function delete_collection($engine_name, $collection_name){
    return $this->delete($this->collection_path($engine_name, $collection_name));
  }

  /**
   * List documents
   * @param string $engine_name Engine's name
   * @param string $collection_name Collection's name
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#4-10-罗列-Documents
   */
  public function documents($engine_name, $collection_name){
    return $this->get($this->documents_path($engine_name, $collection_name));
  }

  /**
   * Create a document
   * @param string $engine_name Engine's name
   * @param string $collection_name Collection's name
   * @param string $attrs Properties of new document
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#4-12-创建一个-Document(指定-id-方式)
   */
  public function create_document($engine_name, $collection_name, $attrs){
    return $this->post($this->documents_path($engine_name, $collection_name), array(), $attrs);
  }

  /**
   * Get a document
   * @param string $engine_name Engine's name
   * @param string $collection_name Collection's name
   * @param string $document_id Document's id
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#4-13-获取一个-Document
   */
  public function document($engine_name, $collection_name, $document_id) {
    return $this->get($this->document_path($engine_name, $collection_name, $document_id));
  }

  /**
   * Update a document
   * @param string $engine_name Engine's name
   * @param string $collection_name Collection's name
   * @param string $document_id Document's id
   * @param array $attrs New properties of the document
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#4-14-创建或更新一个-Document
   */
  public function update_document($engine_name, $collection_name, $document_id, $attrs) {
    return $this->put($this->document_path($engine_name, $collection_name, $document_id), array(), $attrs);
  }

  /**
   * Delete a document
   * @param string $engine_name Engine's name
   * @param string $collection_name Collection's name
   * @param string $document_id Document's id
   *
   * @return array
   * @see http://doc.tinysou.com/v1/indexing.html#4-15-删除一个-Document
   */
  public function delete_document($engine_name, $collection_name, $document_id){
    return $this->delete($this->document_path($engine_name, $collection_name, $document_id));
  }

  /**
   * Search
   * @param string $engine_namen Engine's name
   * @param string $c Collection name(s)
   * @param string $opts Search options
   *
   * @return array
   * @see http://doc.tinysou.com/v1/searching.html#3-参数
   */
  public function search($engine_name, $c, $query, $opts){
    $query_string = array('q' => $query, 'c' => $c);
    $full_query = array_merge($query_string, $options);
    return $this->post($this->search_path($engine_name), array(), $full_query);
  }

  /**
   * Autocomplete
   * @param string $engine_namen Engine's name
   * @param string $c Collection name(s)
   * @param string $opts Autocomplete options
   *
   * @return array
   * @see http://doc.tinysou.com/v1/autocomplete.html
   */
  public function autocomplete($engine_name, $c, $query, $opts){
    $query_string = array('q' => $query, 'c' => $c);
    $full_query = array_merge($query_string, $options);
    return $this->post($this->autocomplete_path($engine_name), array(), $full_query);
  }


  private function search_path($engine_name) {
    return 'engines/'.$engine_name.'/search';
  }

  private function autocomplete_path($engine_name) {
    return 'engines/'.$engine_name.'/autocomplete';
  }

  private function engines_path() {
    return 'engines';
  }

  private function engine_path($engine_name) {
    return 'engines/'.$engine_name;
  }

  private function collections_path($engine_name) {
    return $this->engine_path($engine_name).'/collections';
  }

  private function collection_path($engine_name, $collection_name) {
    return $this->engine_path($engine_name).'/collections/'.$collection_name;
  }

  private function documents_path($engine_name, $collection_name) {
    return $this->collection_path($engine_name, $collection_name).'/documents';
  }

  private function document_path($engine_name, $collection_name, $document_id) {
    return $this->collection_path($engine_name, $collection_name).'/documents/'.$document_id;
  }

  private function get($path, $params = array(), $data = array()) {
    return $this->request('GET', $path, $params, $data);
  }

  private function post($path, $params = array(), $data = array()) {
    return $this->request('POST', $path, $params, $data);
  }

  private function delete($path, $params = array(), $data = array()) {
    return $this->request('DELETE', $path, $params, $data);
  }

  private function put($path, $params = array(), $data = array()) {
    return $this->request('PUT', $path, $params, $data);
  }

  /**
   * HTTP REQUEST 封装
   * @param string $method HTTP REQUEST方法，包括PUT、POST、GET、OPTIONS、DELETE
   * @param string $path 除Bucketname之外的请求路径，包括get参数
   * @param array $headers 请求需要的特殊HTTP HEADERS
   * @param array $body 需要POST发送的数据
   *
   * @return mixed
   */
  protected function request($method, $path, $params = array(), $data = array()) {
    $full_path = "{$this->base_uri}{$path}";

    $headers = array('Content-type: application/json');
    array_push($headers, "Authorization: token {$this->_token}");

    //Build the query string
    $query = http_build_query($params);
    if ($query) {
      $full_path .= '?' . $query;
    }

    $request = curl_init($full_path);

    $body = ($data) ? json_encode($data) : '';

    //Return the output instead of printing it
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_FAILONERROR, true);
    curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($request, CURLOPT_TIMEOUT, $this->_timeout);

    if ($method === 'POST') {
      curl_setopt($request, CURLOPT_POST, true);
      curl_setopt($request, CURLOPT_POSTFIELDS, $body);
    } elseif ($method === 'DELETE') {
      curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'DELETE');
    } elseif ($method === 'PUT') {
      curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($request, CURLOPT_POSTFIELDS, $body);
    }

    $response = curl_exec($request);
    $http_code = curl_getinfo($request, CURLINFO_HTTP_CODE);

    if ($http_code == 0) throw new TinySouException('Connection Failed', $http_code);

    curl_close($request);

    //Any 2XX HTTP codes mean that the request worked
    if (intval(floor($http_code / 100)) === 2) {
      $final = json_decode($response);
      switch (json_last_error()) {
        case JSON_ERROR_DEPTH:
          $error = 'Maximum stack depth exceeded';
          break;
        case JSON_ERROR_CTRL_CHAR:
          $error = 'Unexpected control character found';
          break;
        case JSON_ERROR_SYNTAX:
          $error = 'Syntax error, malformed JSON';
          break;
        case JSON_ERROR_STATE_MISMATCH:
          $error = 'Underflow or the modes mismatch';
          break;
        case JSON_ERROR_UTF8:
          $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
          break;
        case JSON_ERROR_NONE:
        default:
          $error = false;
          break;
      }

      if ($error === false) {
        //Request and response are OK
        if ($final) {
          return $final;
        } else {
          return array();
        }
      } else {
        throw new TinySouException('The JSON response could not be parsed: '.$error. '\n'.$response, $http_code);
      }
    } else {
      switch($http_code) {
        case 401:
        throw new TinySouAuthorizationException($message);
        break;
        case 403:
        throw new TinySouForbiddenException($message);
        break;
        case 404:
        throw new TinySouNotFoundException($message);
        break;
        case 406:
        throw new TinySouNotAcceptableException($message);
        break;
        case 503:
        throw new TinySouServiceUnavailable($message);
        break;
        default:
        throw new TinySouException($message, $http_code);
      }
    }
  }
}

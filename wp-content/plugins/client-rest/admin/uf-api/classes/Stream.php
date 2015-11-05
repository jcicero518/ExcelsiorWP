<?php
class Stream {

  public $name;
  public $stream_id;
  public $stream_data;

  public function __construct($name = '', $stream_id = '', $stream_data = '' ) {
    $this->name = $name;
    $this->stream_id = $stream_id;
    $this->stream_data = $stream_data;
  }

}

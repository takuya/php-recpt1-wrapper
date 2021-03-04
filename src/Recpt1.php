<?php


namespace RecorderUtil;


use RecorderUtil\Recpt1\Recpt1Command;

class Recpt1 {
  
  public $cmd = 'recpt1';
  public $options = [];
  public $args = [];
  protected $channel;
  protected $duration;
  protected $destfile;
  protected $proc;
  
  public function __construct ( $cmd = null ) {
    if ( !is_null( $cmd ) ) {
      $this->cmd = $cmd;
    }
    $this->reset();
  }
  public function reset(){
    $this->options=[];
    $this->args=[];
    $this->channel=null;
    $this->duration=null;
    $this->destfile=null;
  }
  public function getDuration(){
    return $this->duration;
  }
  public function duration( $duration ) {
    if ( ! ($duration == '-' || is_numeric($duration) ) ){
      throw new \InvalidArgumentException('duration shoud be number or "-" ( infinite ).');
    }
    $this->duration = $duration;
    return $this;
  }
  public function destfile($dest){
    if ( $dest !== '-' ){
      $dir = dirname($dest);
      if ( !is_writable($dir) ){
        throw new \RuntimeException("{$dir}");
      }
    }
    $this->destfile = $dest;
    return $this;
  }
  public function getDestfile(){
    return $this->destfile;
  }
  
  public function channel ( $ch ) {
    if (  ! preg_match('/\d/', $ch) ){
      throw new \InvalidArgumentException('channel is not vaild.');
    }
    $this->channel = $ch;
    return $this;
  }
  public function getChannel(){
    return $this->channel;
  }
  
  public function b25 ( $enable = true ) {
    if ( $enable ) {
      $this->options['--b25'] = '';
    } else {
      unset( $this->options['--b25'] );
    }
    return $this;
  }
  
  public function strip ( $enable = true ) {
    if ( $enable ) {
      $this->options['--strip'] = '';
    } else {
      unset( $this->options['--strip'] );
    }
    return $this;
  }
  
  public function sid ( ...$sids ) {
    $this->options['--sid'] = join( ',', $sids );
    return $this;
  }
  protected function genArgs(){
    Recpt1Command::$cmd = $this->cmd;
    //
    $args = [];
    $args = array_merge(array_map(function($k,$v){
    
      return trim($k.' '.(is_array($v)?join($v):$v));
    
    }, array_keys($this->options),array_values($this->options)) ,$args);
    //
    $args[] = $this->getChannel();
    $args[] = $this->getDuration();
    $args[] = $this->getDestfile();

    return $this->args = $args;
  }
  protected function checkArgs(){
    if ( empty($this->getChannel())){
      throw new \RuntimeException('チャンネルが未設定');
    }
    if ( empty($this->getDuration())){
      throw new \RuntimeException('録画時間が未設定');
    }
    if ( empty($this->getDestfile())){
      throw new \RuntimeException('出力先が未設定');
    }
  }
  
  public function start () {
    $this->checkArgs();;
    $args = $this->genArgs();
    $this->proc = Recpt1Command::exec(...$args);
    return $this;
  }
  public function getProcess(){
    return $this->proc;
  }
  public function wait(){
    $this->proc->wait();;
  }
  public function run(){
    $this->start();
    $this->wait();
  }
}
<?php


namespace SystemUtil\Recpt1;


use SystemUtil\Process;

class Recpt1Command {

  public static $cmd = 'ssh s0 recpt1';
  
  public static function exec(...$args): Process {
    $args = array_merge( [], preg_split('/\s/',static::$cmd), $args );
    $args = array_filter($args,'trim');
    //
    $proc = new Process( $args );
    $proc->start();
    return $proc;
  }
  public static function help(){
    $proc = static::exec('-h');
    $proc->wait();
    $out = $proc->getErrorOutput();
    return $out;
  }
  public static function gs_channels(){
    $ch =[];
    $str = static::help();
    $ret = preg_grep('/Terrestrial/mi',preg_split('|\n|',$str));
    $ret = array_pop($ret);
    $ch1 = preg_filter('/^(\d+)-.+/','$1',$ret);
    $ch2 = preg_filter('/.+-(\d+):.+/','$1',$ret);
    $ret = range($ch1,$ch2);
    return $ret;
  }
  public static function bs_channels(){
    $ch =[];
    $str = static::help();
    $ret = preg_grep('/\d+ch:/m',preg_split('|\n|',$str));
    $ret = array_values($ret);
    $ret = array_map(function($e){return  $e;}, $ret);
    foreach ( $ret as $e){
      $key = preg_filter('/(\d+)ch:.+/','$1',$e);
      $name = trim( preg_filter('/\d+ch:(.+)/','$1',$e));
      $ch[$key] =$name;
    }
    return $ch;
  }
  public static function cs_channels(){
    $ch =[];
    $str = static::help();
    $ret = preg_grep('/CS\d+/mi',preg_split('|\n|',$str));
    $ret = array_pop($ret);
    $ch1 = preg_filter('/CS(\d+)-.+/','$1',$ret);
    $ch2 = preg_filter('/.+-CS(\d+).+/','$1',$ret);
    $ret = range($ch1,$ch2);
    $ret = array_map(function($e){ return 'CS'.$e;},$ret);
    return $ret;
  }
  
  
}
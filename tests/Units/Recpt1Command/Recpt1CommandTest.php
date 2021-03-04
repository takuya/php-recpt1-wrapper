<?php


namespace Tests\Units\Recpt1Command;


use Tests\TestCase;
use RecorderUtil\Recpt1\Recpt1Command;

class Recpt1CommandTest extends  TestCase {
  
  public function test_recpt1_help(){
    $ret = Recpt1Command::help();
    $this->assertNotEmpty($ret);
    $ret  = Recpt1Command::bs_channels();
    $this->assertArrayHasKey('101', $ret);
  
    $ret  = Recpt1Command::cs_channels();
    $this->assertIsArray($ret);
  
    $ret  = Recpt1Command::gs_channels();
    $this->assertIsArray($ret);

  }
}
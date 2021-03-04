<?php


namespace Tests\Units\Recpt1;


use Tests\TestCase;
use SystemUtil\Recpt1;

class Recpt1Test extends TestCase {
  
  public function test_recpt1_options () {
    $recpt1 = new Recpt1( 'ssh s0 recpt1' );
    $recpt1->b25();
    $this->assertArrayHasKey( '--b25', $recpt1->options );
    $recpt1->strip();
    $this->assertArrayHasKey( '--strip', $recpt1->options );
    $recpt1->duration( 60 );
    $this->assertEquals( 60, $recpt1->getDuration() );
    $recpt1->duration( '-' );
    $this->assertEquals( '-', $recpt1->getDuration() );
    //
    $recpt1->destfile( '-' );
    $this->assertEquals( '-', $recpt1->getDestfile() );
    $recpt1->destfile( sys_get_temp_dir().'/test.ts' );
    $this->assertEquals( sys_get_temp_dir().'/test.ts', $recpt1->getDestfile() );
    //
    $recpt1->channel( '22' );
    $this->assertEquals( 22, $recpt1->getChannel() );
    
    $recpt1->reset();
    $this->assertEquals( null, $recpt1->getChannel() );
    $this->assertEquals( null, $recpt1->getDuration() );
    $this->assertEquals( null, $recpt1->getDestfile() );
    
    
    //
    try{
      $recpt1->reset();
      $recpt1
        ->b25()
        ->strip()
        ->start();
    }catch (\Exception $e){
      $this->assertEquals( \RuntimeException::class, get_class($e) );
      $this->assertStringContainsString('チャンネル', $e->getMessage());
    }
    try{
      $recpt1->reset();
      $recpt1
        ->b25()
        ->strip()
        ->channel(21)
        ->start();
    }catch (\Exception $e){
      $this->assertEquals( \RuntimeException::class, get_class($e) );
      $this->assertStringContainsString('時間', $e->getMessage());
    }
    try{
      $recpt1->reset();
      $recpt1
        ->b25()
        ->strip()
        ->channel(21)
        ->duration(1234)
        ->start();
    }catch (\Exception $e){
      $this->assertEquals( \RuntimeException::class, get_class($e) );
      $this->assertStringContainsString('出力', $e->getMessage());
    }
  }
  
  public function test_recpt1_recording(){
  
    //
    $duration = 5;
    $ch=101;
    $recpt1 = new Recpt1( 'ssh s0 recpt1' );
    $recpt1
      ->b25()
      ->strip()
      ->channel($ch)
      ->duration($duration)
      ->destfile('-');
    $proc = $recpt1->getProcess();
    $start = microtime(true);
    $recpt1->run();
    $end = microtime(true);
    $this->assertGreaterThan($duration,$end-$start);
    $this->assertGreaterThan(8,strlen($recpt1->getProcess()->getOutput())/1024/1000);
    
  }
  
  
}
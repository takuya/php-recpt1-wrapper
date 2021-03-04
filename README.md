## Recpt1 Command Wrapper for php

Recpt1のコマンドオプションをいつも忘れるので、PHPからメソッドチェーンで呼び出せるようにした。

## Sample 01
```php
<?php

    $recpt1 = new Recpt1( 'ssh 192.168.10.10 recpt1' );
    $proc = $recpt1
      ->b25()
      ->strip()
      ->channel(22)
      ->duration(3600)
      ->destfile('out.ts')
      ->run();

```

## Sample 02
出力をストリームで取得する
```php
<?php

    $recpt1 = new Recpt1( 'ssh 192.168.10.10 recpt1' );
    $recpt1
      ->b25()
      ->strip()
      ->channel(22)
      ->duration(3600)
      ->destfile('out.ts')
      ->run();

    $proc = $recpt1->getProcess();
    $out_stream = $proc->getOutputStream();
```

## Sample 03
出力を変数で取得する
```php
<?php

    $recpt1 = new Recpt1( 'ssh 192.168.10.10 recpt1' );
    $recpt1
      ->b25()
      ->strip()
      ->channel(22)
      ->duration(3600)
      ->destfile('out.ts')
      ->run();

    $proc = $recpt1->getProcess();
    $out = $proc->getOutput();
```

## Sample 04 

プロセスを並列で起動する。

```php
<?php

    $recpt1_1 = new Recpt1( 'ssh 192.168.10.10 recpt1' );
    $recpt1_1
      ->b25()
      ->strip()
      ->channel(22)
      ->duration(3600)
      ->destfile('out_1.ts')
      ->start();
    //
    $recpt1_2 = new Recpt1( 'ssh 192.168.10.10 recpt1' );
    $recpt1_2
      ->b25()
      ->strip()
      ->channel(22)
      ->duration(3600)
      ->destfile('out_2.ts')
      ->start();
      
      
    $recpt1_1->wait();
    $recpt1_2->wait();

```









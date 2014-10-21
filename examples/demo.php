<?php
require_once('../tinysou.php');

define('TOKEN', 'YOUR_TOKEN');

$client = new TinySou(TOKEN);

try {
    echo "=========Create Engine\r\n";
    $blog = $client->create_engine(array(
      'name' => 'test-blog', 'display_name' => 'Blog for Test'
      ));
    var_dump($blog);
    echo "=========DONE\r\n\r\n";
    echo "=========List Engines\r\n";
    $list = $client->engines();
    var_dump($list);
    echo "=========DONE\r\n\r\n";
    echo "=========DELETE\r\n";
    $res = $client->delete_engine('test-blog');
    var_dump($res);
    echo "=========DONE\r\n\r\n";
}
catch(Exception $e) {
    echo $e->getCode();
    echo $e->getMessage();
}

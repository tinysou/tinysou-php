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

    echo "=========Create Collection\r\n";
    $post = $client->create_collection('test-blog', array(
                'name' => 'posts',
                'field_types' => array(
                    'title' => 'string',
                    'body' => 'text'
                )
    ));
    var_dump($post);
    echo "=========DONE\r\n\r\n";

    echo "=========List Collection\r\n";
    $list = $client->collections('test-blog', 'posts');
    var_dump($post);
    echo "=========DONE\r\n\r\n";

    echo "=========Create Document\r\n";
    $doc = $client->create_document('test-blog', 'posts', array(
                'title' => 'First Post',
                'body' => 'This is my first post'
    ));
    var_dump($doc);
    echo "=========DONE\r\n\r\n";

    echo "=========Create 2nd Document\r\n";
    $doc2 = $client->create_document('test-blog', 'posts', array(
                'title' => 'Second Post',
                'body' => 'This is my second post'
    ));
    var_dump($doc2);
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

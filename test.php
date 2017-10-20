<?php

$cod = 'Tzo4OiJzdGRDbGFzcyI6NTp7czoxMDoiZW5hYmxlZG9jayI7czozOiJ5ZXMiO3M6ODoidHJpbW1vZGUiO3M6MToiMSI7czoxMDoidHJpbWxlbmd0aCI7aTo1MDtzOjE0OiJleHBhbnNpb25saW1pdCI7czoyOiIzMCI7czoxNDoibGlua2NhdGVnb3JpZXMiO3M6Mjoibm8iO30=';
$cod2 = 'Tzo4OiJzdGRDbGFzcyI6NTp7czoxMDoiZW5hYmxlZG9jayI7czozOiJ5ZXMiO3M6MTQ6ImxpbmtjYXRlZ29yaWVzIjtzOjI6Im5vIjtzOjg6InRyaW1tb2RlIjtzOjE6IjEiO3M6MTA6InRyaW1sZW5ndGgiO2k6NTA7czoxNDoiZXhwYW5zaW9ubGltaXQiO3M6MToiMCI7fQ==';

$config = unserialize(base64_decode($cod));
$config2 = unserialize(base64_decode($cod2));

echo '<pre> test 1: ';
var_dump($config);
echo '</pre>';

echo '<pre> test 2: ';
var_dump($config2);
echo '</pre>';


?>
## php

md5

```php
md5(file_get_contents('http://example.com/some-file.html');
md5_file('http://example.com/some-file.html');
```

ed2k

```php
echo hash_file('md4', 'example.txt');
```

incremental hashing

```php
$fp = fopen($file, "r");
$ctx = hash_init('sha1');
while (!feof($fp)) {
    $buffer = fgets($fp, 65536);
    hash_update($ctx, $buffer);
}
$hash = hash_final($ctx);
fclose($fp);
```

## shell

```shell
curl -sL http://example.com/some-file.html | md5sum
wget http://example.com/some-file.html -O- | md5sum

https://askubuntu.com/questions/685775/bash-get-md5-of-online-file
```

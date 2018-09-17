## PHP

```php
md5(file_get_contents('http://example.com/some-file.html');
md5_file('http://example.com/some-file.html');
```

- [will-md5file-contents-as-string-equal-md5-file-path-to-file](https://stackoverflow.com/questions/10738866/will-md5file-contents-as-string-equal-md5-file-path-to-file)

增量计算 hash 值

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

- [function.hash-update.php#68373](http://php.net/manual/en/function.hash-update.php#68373)

## Shell

```shell
curl -sL http://example.com/some-file.html | md5sum
wget http://example.com/some-file.html -O- | md5sum
```

- [Bash get MD5 of online file](https://askubuntu.com/questions/685775/bash-get-md5-of-online-file)

## 碰撞攻击

- [Collision attack](https://en.wikipedia.org/wiki/Collision_attack)
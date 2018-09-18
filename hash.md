## PHP

md5

```php
# md5
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

## Shell

```shell
curl -sL http://example.com/some-file.html | md5sum
wget http://example.com/some-file.html -O- | md5sum
```

## Links

- [will-md5file-contents-as-string-equal-md5-file-path-to-file](https://stackoverflow.com/questions/10738866/will-md5file-contents-as-string-equal-md5-file-path-to-file)
- [Ed2k-hash](https://wiki.anidb.info/w/Ed2k-hash)、
- [PHP生成Ed2k(电驴)连接](https://cevin.me/archives/php-generate-ed2k-link.html)
- [function.hash-update.php#68373](http://php.net/manual/en/function.hash-update.php#68373)
- [Bash get MD5 of online file](https://askubuntu.com/questions/685775/bash-get-md5-of-online-file)
- [Collision attack](https://en.wikipedia.org/wiki/Collision_attack)

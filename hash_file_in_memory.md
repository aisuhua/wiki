## PHP

```php
md5(file_get_contents('http://example.com/some-file.html');
md5_file('http://example.com/some-file.html');
```

- [will-md5file-contents-as-string-equal-md5-file-path-to-file](https://stackoverflow.com/questions/10738866/will-md5file-contents-as-string-equal-md5-file-path-to-file)

## Shell

```shell
curl -sL http://example.com/some-file.html | md5sum
wget http://example.com/some-file.html -O- | md5sum

https://askubuntu.com/questions/685775/bash-get-md5-of-online-file
```

- [Bash get MD5 of online file](https://askubuntu.com/questions/685775/bash-get-md5-of-online-file)

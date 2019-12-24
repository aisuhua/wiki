shell 实现

```
md5sum file.txt
sha1sum file.txt
head -c 131072 file.txt | sha1sum
openssl dgst -md4 -hex file.txt
```

```
# 快速生成大文件
sell> dd if=/dev/zero of=test bs=1M count=1000
记录了1000+0 的读入
记录了1000+0 的写出
1048576000 bytes (1.0 GB, 1000 MiB) copied, 0.401372 s, 2.6 GB/s

# 小写转换成大写
shell> sha1sum test | tr a-z A-Z
A5F5E9413C558753025495DAF6A0A7B47E55402B  TEST

# 大写转换成小写
shell> echo 'A5F5E9413C558753025495DAF6A0A7B47E55402B' | tr A-Z a-z
a5f5e9413c558753025495daf6a0a7b47e55402b
```

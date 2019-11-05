## 安装

```sh
apt-get install thrift-compiler
thrift --version
```

## 使用

官网文档有详细说明，https://github.com/apache/thrift/tree/master/tutorial#tutorial

步骤总得来讲有以下几步：

1. 编写 xxx.thrift 文件
2. 构建出所需要的 server and client stubs, 命令：`thrift --gen php:server xxx.thrift`
3. 利用 Apache Thrift library 和 server and client stubs 文件编写 demo。

## 参考

- http://www.thrift.pl/
- https://thrift-tutorial.readthedocs.io/en/latest/index.html
- https://segmentfault.com/a/1190000013329497

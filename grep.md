# grep

逐行查找

## 简单使用

从文件中查找

```
grep 1 suhua.txt
```

遍历查找

```
grep -r keyword dir
```

查看搜索结果的前面和后面

```
grep -A 2 -B 2 keyword suhua.txt
```

查看匹配的第一部分

```
grep -r -A 2 -B 2 -m 1 keyword dir1
grep -r -A 2 -B 2 keyword dir1 | head -100
```

## 高级使用

查找包含任意一个关键字的行

```sh
shell> grep -E 'word1|word2|word3' file.txt
```

查找包含所有关键字的行

```sh
shell> grep word1 file.txt | grep word2 |grep word3
```

- [grep 同时满足多个关键字和满足任意关键字](https://www.cnblogs.com/smallrookie/p/6102691.html)

统计 test2 中有，test1 中没有的行

```sh
shell> grep -vFf test1.csv test2.csv
```

统计两个文本文件的相同行

```sh
shell> grep -Ff test1.csv test2.csv
```

tab 修改为 4 个空格

```sh
# 全局
shell> vim /etc/vim/vimrc.local
set tabstop=4
# 当前用户
shell> vim ~/.vimrc
set tabstop=4
```

语法高亮

```sh
shell> vim ~/.vimrc
syntax on
```

替换文本

```vim
:%s/7001/7002/g
```

- [在 Vim 中优雅地查找和替换](https://harttle.land/2016/08/08/vim-search-in-file.html)

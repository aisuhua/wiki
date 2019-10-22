tab 修改为 4 个空格

```sh
shell> vim /etc/vim/vimrc.local
set tabstop=4
```

- [Tab key == 4 spaces and auto-indent after curly braces in Vim](https://stackoverflow.com/questions/234564/tab-key-4-spaces-and-auto-indent-after-curly-braces-in-vim)

只作用于当前用户

```sh
shell> vim ~/.vimrc
set tabstop=4
```

语法高亮

```sh
shell> vim ~/.vimrc
syntax on
```

- [Turn On or Off Color Syntax Highlighting In vi or vim Editor](https://www.cyberciti.biz/faq/turn-on-or-off-color-syntax-highlighting-in-vi-or-vim/)

替换文本

```vim
:%s/7001/7002/g
```

- [在 Vim 中优雅地查找和替换](https://harttle.land/2016/08/08/vim-search-in-file.html)

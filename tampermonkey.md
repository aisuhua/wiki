
使用示例

```js
// ==UserScript==
// @name         React
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  try to take over the world!
// @author       You
// @match        https://zh-hans.reactjs.org/*
// @grant        none
// ==/UserScript==

(function() {
    'use strict';

    var div1 = document.getElementsByClassName('css-4zmxiv')[0];
    div1.style.filter = "grayscale(0%)";

    var div2 = document.getElementsByClassName('css-1ilhy7w')[0];
    div2.style.filter = "grayscale(0%)";
})();
```

- [Tampermonkey](http://www.tampermonkey.net/)

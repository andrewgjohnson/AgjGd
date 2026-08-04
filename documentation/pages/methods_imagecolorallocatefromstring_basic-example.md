---
layout:    default
title:     Basic • AgjGd::imagecolorallocatefromstring Example • AgjGd
permalink: /methods/imagecolorallocatefromstring/basic-example/
---

# [AgjGd](/methods/)::[imagecolorallocatefromstring](/methods/imagecolorallocatefromstring/) Example: Basic

The same red is allocated twice: once by passing separate red, green and blue values to `imagecolorallocate` and once by passing the string `'#FF0000'`. Both rectangles are drawn side by side and are identical, which is the point — the string is just a friendlier way to say the same thing.

## Source Code: [examples/imagecolorallocatefromstring/01-basic.php](https://github.com/andrewgjohnson/AgjGd/blob/master/examples/imagecolorallocatefromstring/01-basic.php)

<pre><code>{% include examples/imagecolorallocatefromstring/01-basic.php %}</code></pre>

## Expected Output: [examples/imagecolorallocatefromstring/01-basic.png](https://github.com/andrewgjohnson/AgjGd/blob/master/examples/imagecolorallocatefromstring/01-basic.png)

![Example: Basic](/examples/imagecolorallocatefromstring/01-basic.png "Example: Basic")

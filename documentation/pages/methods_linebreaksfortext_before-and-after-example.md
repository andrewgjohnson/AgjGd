---
layout:    default
title:     Before and After • AgjGd::linebreaksfortext Example • AgjGd
permalink: /methods/linebreaksfortext/before-and-after-example/
---

# [AgjGd](/methods/)::[linebreaksfortext](/methods/linebreaksfortext/) Example: Before and After

The opening of *A Tale of Two Cities* is rendered twice for comparison. On the left it goes straight to `imagettftext` and runs off the edge of the image on a single line; on the right the same text is passed through this method first, which inserts line breaks so that it wraps to the width of the image.

## Source Code: [examples/linebreaksfortext/01-before-and-after.php](https://github.com/andrewgjohnson/AgjGd/blob/master/examples/linebreaksfortext/01-before-and-after.php)

<pre><code>{% include examples/linebreaksfortext/01-before-and-after.php %}</code></pre>

## Expected Output: [examples/linebreaksfortext/01-before-and-after.png](https://github.com/andrewgjohnson/AgjGd/blob/master/examples/linebreaksfortext/01-before-and-after.png)

![Example: Before and After](/examples/linebreaksfortext/01-before-and-after.png "Example: Before and After")

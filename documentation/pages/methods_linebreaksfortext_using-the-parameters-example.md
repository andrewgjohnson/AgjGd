---
layout:    default
title:     Using the Parameters • AgjGd::linebreaksfortext Example • AgjGd
permalink: /methods/linebreaksfortext/using-the-parameters-example/
---

# [AgjGd](/methods/)::[linebreaksfortext](/methods/linebreaksfortext/) Example: Using the Parameters

The three optional flags are demonstrated a pair at a time, each with the same text rendered once with the flag off and once with it on. `$attemptToBreakOnHyphens` breaks a hyphenated word across lines, `$forceBreakOnSingleWords` splits a word too long to fit on a line of its own and `$preventWidows` avoids leaving a single word stranded on the final line.

## Source Code: [examples/linebreaksfortext/02-using-the-parameters.php](https://github.com/andrewgjohnson/AgjGd/blob/master/examples/linebreaksfortext/02-using-the-parameters.php)

<pre><code>{% include examples/linebreaksfortext/02-using-the-parameters.php %}</code></pre>

## Expected Output: [examples/linebreaksfortext/02-using-the-parameters.png](https://github.com/andrewgjohnson/AgjGd/blob/master/examples/linebreaksfortext/02-using-the-parameters.png)

![Example: Using the Parameters](/examples/linebreaksfortext/02-using-the-parameters.png "Example: Using the Parameters")

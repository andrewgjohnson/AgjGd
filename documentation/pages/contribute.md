---
layout:    default
title:     Contribute • AgjGd
permalink: /contribute/
nav_order: 6
nav_text:  Contribute
---

{% capture content %}{% include .github/CONTRIBUTING.md %}{% endcapture %}
{{ content | markdownify }}

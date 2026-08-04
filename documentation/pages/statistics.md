---
layout:    default
title:     Statistics • AgjGd
permalink: /statistics/
nav_order: 5
nav_text:  Statistics
---

# Statistics

The statistics for contributors, downloads, stargazers and open issues for **AgjGd** can be found below.

## Downloads

{% capture downloads_content %}{% include DOWNLOADS.md %}{% endcapture %}
{{ downloads_content | markdownify }}

## Stargazers

{% capture stargazers_content %}{% include STARGAZERS.md %}{% endcapture %}
{{ stargazers_content | markdownify }}

## Open Issues

{% capture issues_content %}{% include ISSUES.md %}{% endcapture %}
{{ issues_content | markdownify }}

## Contributors

{% capture contributors_content %}{% include CONTRIBUTORS.md %}{% endcapture %}
{{ contributors_content | markdownify | replace: '<h1', '<h3' | replace: '</h1', '</h3' }}

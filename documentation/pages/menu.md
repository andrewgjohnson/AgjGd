---
layout:    default
title:     Menu • AgjGd
permalink: /menu/
---

# Menu

<ul>
    {% assign nav_items = site.pages | where_exp: "item", "item.nav_order != nil" %}
    {% if site.nav %}
        {% assign nav_items = nav_items | concat: site.nav %}
    {% endif %}
    {% assign nav_items = nav_items | sort: 'nav_order' %}
    {% for nav_item in nav_items %}
        {% assign nav_text = nav_item.nav_text | default: nav_item.title %}
        <li>
            <a href="{{ nav_item.url | relative_url | escape }}" title="{{ nav_text | escape }}">{{ nav_text | escape }}</a>
        </li>
    {% endfor %}
</ul>

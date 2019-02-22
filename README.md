Twig CSS Inliner Extension
==========================

This package provides a CSS inliner tag (`inlinecss`) for Twig and a Symfony
bundle.

Use the `inlinecss` tag from a Twig template:

```twig
{% inlinecss %}
    <html>
        <head>
            <style>
                p { color: red; }
            </style>
        </head>
        <body>
            <p>Hello CSS!</p>
        </body>
    </html>
{% endinlinecss %}
```

You can also add some stylesheets by passing them as arguments to the tag:

```twig
{% inlinecss "some_styles.css" "another.css" %}
    <html>
        <body>
            <p>Hello CSS!</p>
        </body>
    </html>
{% endinlinecss %}
```

Styles loaded via the tag ocverride the styles defined in the `<style>` tag of
the HTML document.

Note that the CSS inliner works on an entire HTML document, not a fragment.

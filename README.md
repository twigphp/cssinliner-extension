Twig CSS Inliner Extension
==========================

This package provides a CSS inliner filter (`inline_css`) for Twig and a Symfony
bundle.

If you are not using Symfony, register the extension on Twig's `Environment`
manually:

```php
use Twig\CssInliner\CssInlinerExtension;
use Twig\Environment;

$twig = new Environment(...);
$twig->addExtension(new CssInlinerExtension());
```

Use the `inline_css` filter from a Twig template:

```twig
{% filter inline_css %}
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
{% endfilter %}
```

You can also add some stylesheets by passing them as arguments to the filter:

```twig
{% filter inline_css(source("some_styles.css"), source("another.css")) %}
    <html>
        <body>
            <p>Hello CSS!</p>
        </body>
    </html>
{% endfilter %}
```

Styles loaded via the filter override the styles defined in the `<style>` tag of
the HTML document.

You can also use the filter on an included file:

```twig
{{ include('some_template.html.twig')|inline_css }}

{{ include('some_template.html.twig')|inline_css(source("some_styles.css")) }}
```

Note that the CSS inliner works on an entire HTML document, not a fragment.

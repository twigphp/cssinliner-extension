Twig CSS Inliner Extension
==========================

This package provides a CSS inliner tag (`inlinecss`) for Twig and a Symfony
bundle.

If you are not using Symfony, you need to register the extension on Twig's
`Environment` manually:

```php
use Twig\CssInliner\CssInlinerExtension;
use Twig\CssInliner\CssInlinerRuntime;
use Twig\Environment;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

$twig = new Environment(...);
$twig->addExtension(new CssInlinerExtension());

// and register the Runtime class as well (the following is just a simple way to do it)
$twig->addRuntimeLoader(new class implements RuntimeLoaderInterface
{
    public function load($class)
    {
        if (CssInlinerRuntime::class === $class) {
            return new $class();
        }
    }
});
```

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

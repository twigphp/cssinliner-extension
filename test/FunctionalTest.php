<?php

namespace Twig\CssInliner\Tests;

use PHPUnit\Framework\TestCase;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Twig\CssInliner\CssInlinerExtension;
use Twig\CssInliner\CssInlinerRuntime;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

class FunctionalTest extends TestCase
{
    /**
     * @dataProvider getTests
     */
    public function test($template, $expected)
    {
        $twig = new Environment(new ArrayLoader([
            'index' => $template,
            'css' => 'p { color: red }',
            'more_css' => 'p { color: blue }',
        ]));
        $twig->addExtension(new CssInlinerExtension());
        $twig->addRuntimeLoader(new class() implements RuntimeLoaderInterface {
            public function load($class)
            {
                if (CssInlinerRuntime::class === $class) {
                    return new $class(new CssToInlineStyles());
                }
            }
        });

        $this->assertContains($expected, $twig->render('index'));
    }

    public function getTests()
    {
        return [
            [<<<EOF
{% inlinecss %}
    <html>
        <style>
            p { color: red }
        </style>
        <p>Great!</p>
    </html>
{% endinlinecss %}
EOF
            , '<p style="color: red;">Great!</p>'],
        [<<<EOF
{% inlinecss 'css' %}
    <html>
        <p>Great!</p>
    </html>
{% endinlinecss %}
EOF
            , '<p style="color: red;">Great!</p>'],
        [<<<EOF
{% inlinecss 'css' 'more_css' %}
    <html>
        <p>Great!</p>
    </html>
{% endinlinecss %}
EOF
            , '<p style="color: blue;">Great!</p>'],
        ];
    }
}

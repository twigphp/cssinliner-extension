<?php

namespace Twig\CssInliner\Tests;

use PHPUnit\Framework\TestCase;
use Twig\CssInliner\CssInlinerExtension;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class FunctionalTest extends TestCase
{
    /**
     * @dataProvider getTests
     */
    public function test($template, $expected)
    {
        $twig = new Environment(new ArrayLoader([
            'index' => $template,
            'html' => '<html><p>Great!</p></html>',
            'css' => 'p { color: red }',
            'more_css' => 'p { color: blue }',
        ]));
        $twig->addExtension(new CssInlinerExtension());
        $this->assertContains($expected, $twig->render('index'));
    }

    public function getTests()
    {
        return [
            [<<<EOF
{% filter inline_css %}
    <html>
        <style>
            p { color: red }
        </style>
        <p>Great!</p>
    </html>
{% endfilter %}
EOF
            , '<p style="color: red;">Great!</p>'],
            [<<<EOF
{% filter inline_css(source('css')) %}
    <html>
        <p>Great!</p>
    </html>
{% endfilter %}
EOF
            , '<p style="color: red;">Great!</p>'],
            [<<<EOF
{% filter inline_css(source('css'), source('more_css')) %}
    <html>
        <p>Great!</p>
    </html>
{% endfilter %}
EOF
            , '<p style="color: blue;">Great!</p>'],
            [<<<EOF
{% filter inline_css(source('css') ~ source('more_css')) %}
    <html>
        <p>Great!</p>
    </html>
{% endfilter %}
EOF
            , '<p style="color: blue;">Great!</p>'],
            ["{{ include('html')|inline_css(source('css') ~ source('more_css')) }}", '<p style="color: blue;">Great!</p>'],
        ];
    }
}

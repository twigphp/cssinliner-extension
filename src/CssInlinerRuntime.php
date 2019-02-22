<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\CssInliner;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class CssInlinerRuntime
{
    private $inliner;

    public function __construct(CssToInlineStyles $inliner = null)
    {
        if (!class_exists(CssToInlineStyles::class)) {
            throw new \RuntimeException('Unable to use the CSS extension as the tijsverkoyen/css-to-inline-styles package is not installed.');
        }

        $this->inliner = $inliner ?? new CssToInlineStyles();
    }

    public function inline(string $body, string $css = null): string
    {
        return $this->inliner->convert($body, $css);
    }
}

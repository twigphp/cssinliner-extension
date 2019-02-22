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

use Twig\Compiler;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Node;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
class InlineCssNode extends Node
{
    public function __construct(Node $body, ArrayExpression $stylesheets = null, int $lineno, string $tag = null)
    {
        $nodes = ['body' => $body];
        if (null !== $stylesheets) {
            $nodes['stylesheets'] = $stylesheets;
        }

        parent::__construct($nodes, [], $lineno, $tag);
    }

    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write('echo $this->env->getRuntime(\'Twig\CssInliner\CssInlinerRuntime\')->inline(ob_get_clean()')
        ;
        if ($this->hasNode('stylesheets')) {
            $first = true;
            foreach ($this->getNode('stylesheets')->getKeyValuePairs() as $pair) {
                if (!$first) {
                    $compiler->raw('.');
                } else {
                    $compiler->raw(', ');
                }
                $first = false;

                $compiler
                    ->raw('$this->env->getLoader()->getSourceContext(')
                    ->subcompile($pair['value'])
                    ->raw(')->getCode()')
                ;
            }
        }
        $compiler->raw(");\n");
    }
}

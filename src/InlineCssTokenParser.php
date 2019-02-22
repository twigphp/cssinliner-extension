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

use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Token Parser for the 'inlinecss' tag.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class InlineCssTokenParser extends AbstractTokenParser
{
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $stylesheets = [];
        $i = 0;
        while (!$stream->test(Token::BLOCK_END_TYPE)) {
            $stylesheets[] = new ConstantExpression($i++, $lineno);
            $stylesheets[] = $this->parser->getExpressionParser()->parseExpression();
        }
        if ($stylesheets) {
            $stylesheets = new ArrayExpression($stylesheets, $lineno);
        } else {
            $stylesheets = null;
        }

        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideInlinecssEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new InlineCssNode($body, $stylesheets, $lineno, $this->getTag());
    }

    public function decideInlinecssEnd(Token $token)
    {
        return $token->test(['endinlinecss']);
    }

    public function getTag()
    {
        return 'inlinecss';
    }
}

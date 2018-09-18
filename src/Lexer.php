<?php declare(strict_types=1);
/*
 * This file is part of the elasticsearch-synonyms package.
 *
 * (c) Martin Ohmann <martin@mohmann.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mohmann\ElasticsearchSynonyms;

use Yosymfony\ParserUtils\BasicLexer;

class Lexer extends BasicLexer
{
    /**
     * @const string
     */
    const T_WORD = 'T_WORD';

    /**
     * @const string
     */
    const T_COMMA = 'T_COMMA';

    /**
     * @const string
     */
    const T_ARROW = 'T_ARROW';

    /**
     * @const string
     */
    const T_COMMENT = 'T_COMMENT';

    /**
     * @const string
     */
    const T_SPACE = 'T_SPACE';

    /**
     * @const string
     */
    const T_NEWLINE = 'T_NEWLINE';

    public function __construct()
    {
        parent::__construct([
            '/^([\p{L}\'-]+)/u' => self::T_WORD,
            '/^(,)/x' => self::T_COMMA,
            '/^(=>)/x' => self::T_ARROW,
            '/^(\#)/x' => self::T_COMMENT,
            '/^\s+/' => self::T_SPACE,
        ]);

        $this->generateNewlineTokens()
            ->setNewlineTokenName(self::T_NEWLINE);
    }
}

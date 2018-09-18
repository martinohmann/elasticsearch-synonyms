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

use mohmann\ElasticsearchSynonyms\Collection\MappingCollection;
use mohmann\ElasticsearchSynonyms\Lexer;
use mohmann\ElasticsearchSynonyms\Mapping\Mapping;
use Yosymfony\ParserUtils\LexerInterface;
use Yosymfony\ParserUtils\SyntaxErrorException;
use Yosymfony\ParserUtils\Token;
use Yosymfony\ParserUtils\TokenStream;
use Yosymfony\ParserUtils\TokenStreamInterface;

class Parser implements ParserInterface
{
    /**
     * @const int
     */
    const STEP_SYNONYMS = 0;

    /**
     * @const int
     */
    const STEP_REPLACEMENTS = 1;

    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * @var Mapping
     */
    private $mapping;

    /**
     * @var int
     */
    private $step;

    /**
     * @var Token
     */
    private $previousToken;

    /**
     * @param LexerInterface $lexer
     */
    public function __construct(LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * {@inheritDoc}
     */
    public function parse(string $input): MappingCollection
    {
        $stream = $this->lexer->tokenize($input);
        $result = $this->parseTokenStream($stream);

        if ($stream->hasPendingTokens()) {
            throw new SyntaxErrorException('There are unprocessed tokens left.');
        }

        return $result;
    }

    /**
     * @return void
     */
    private function reset()
    {
        $this->setStep(self::STEP_SYNONYMS);
        $this->previousToken = null;
        $this->mapping = new Mapping();
    }

    /**
     * @param int $state
     * @return void
     */
    private function setStep(int $state)
    {
        $this->step = $state;
    }

    /**
     * @param TokenStream $stream
     * @return MappingCollection
     */
    private function parseTokenStream(TokenStreamInterface $stream): MappingCollection
    {
        $this->reset();

        $collection = new MappingCollection();

        while ($token = $stream->moveNext()) {
            switch ($token->getName()) {
                case Lexer::T_ARROW:
                    $this->ensureSurroundedByWords($token, $stream);

                    if (self::STEP_REPLACEMENTS === $this->step) {
                        throw new SyntaxErrorException(
                            \sprintf(
                                '"%s" is only allowed once per line',
                                Lexer::T_ARROW
                            )
                        );
                    }

                    $this->setStep(self::STEP_REPLACEMENTS);

                    break;
                case Lexer::T_COMMA:
                    $this->ensureSurroundedByWords($token, $stream);

                    break;
                case Lexer::T_COMMENT:
                    $stream->skipWhileAny([
                        Lexer::T_ARROW,
                        Lexer::T_COMMA,
                        Lexer::T_COMMENT,
                        Lexer::T_WORD,
                    ]);

                    break;
                case Lexer::T_NEWLINE:
                    $this->collectMapping($collection);
                    $this->reset();

                    break;
                case Lexer::T_WORD:
                    $synonym = $this->parseSynonym($token, $stream);

                    if (self::STEP_REPLACEMENTS === $this->step) {
                        $this->mapping->addReplacement($synonym);
                    } else {
                        $this->mapping->addSynonym($synonym);
                    }

                    break;
                default:
                    throw new SyntaxErrorException('Unexpected token class.');
            }

            $this->previousToken = $token;
        }

        $this->collectMapping($collection);

        return $collection;
    }

    /**
     * @param MappingCollection $collection
     * @return void
     */
    private function collectMapping(MappingCollection $collection)
    {
        if ($this->mapping->hasSynonyms()) {
            $collection->add($this->mapping);
        }
    }

    /**
     * @param Token $token
     * @param TokenStreamInterface $stream
     * @return void
     */
    private function ensureSurroundedByWords(Token $token, TokenStreamInterface $stream)
    {
        if (null === $this->previousToken || Lexer::T_WORD !== $this->previousToken->getName()) {
            throw new SyntaxErrorException(
                \sprintf(
                    '"%s" has to be preceeded by "%s"',
                    $token->getName(),
                    Lexer::T_WORD
                )
            );
        }

        if (!$stream->isNext(Lexer::T_WORD)) {
            throw new SyntaxErrorException(
                \sprintf(
                    '"%s" has to be followed by "%s"',
                    $token->getName(),
                    Lexer::T_WORD
                )
            );
        }
    }

    /**
     * @param Token $token
     * @param TokenStreamInterface $stream
     * @return Synonym
     */
    private function parseSynonym(Token $token, TokenStreamInterface $stream): Synonym
    {
        $synonym = new Synonym($token->getValue());

        while ($stream->isNext(Lexer::T_WORD)) {
            $synonym->addWord($stream->matchNext(Lexer::T_WORD));
        }

        return $synonym;
    }
}

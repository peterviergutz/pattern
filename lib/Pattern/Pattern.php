<?php namespace Pattern;

class Pattern
{
    const PARAM_NAME_REGEX = '/\{((?:(?!\d+,?\d+?)\w)+?)\}/';

    private $pattern = '';

    private $variables = [];

    /**
     * Pattern constructor.
     * @param $pattern
     */
    public function __construct($pattern)
    {
        $this->variables = $this->parseVariablesFromPattern($pattern);
        $this->pattern = $pattern;
    }

    /**
     * @param string $pattern
     * @return array
     */
    private function parseVariablesFromPattern(string $pattern) : array
    {
        preg_match_all(static::PARAM_NAME_REGEX, $pattern, $matches);

        $variables = array_map(function ($m) {
            return trim($m, '?');
        }, $matches[1]);

        if (empty($variables)) {
            throw new \InvalidArgumentException(sprintf('No placeholders found in pattern "%s"', $pattern));
        }

        return $variables;
    }

    /**
     * @param $value
     * @return array
     */
    public function parse($value) : array
    {
        $pattern = str_replace('/', '\/', $this->pattern);
        $pattern = str_replace('.', '\.', $pattern);
        $text = '/^'.preg_replace(static::PARAM_NAME_REGEX, '(?<$1>.*)', $pattern).' ?$/miu';

        $regexMatched = (bool) preg_match($text, $value, $matches);

        $variables = [];

        if ($regexMatched) {
            foreach ($this->variables as $variable) {
                if (!empty($matches[$variable])) {
                    $variables[$variable] = $matches[$variable];
                }
            }
        }

        return $variables;
    }
}

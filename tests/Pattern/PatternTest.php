<?php
declare(strict_types=1);

use Pattern\Pattern;
use PHPUnit\Framework\TestCase;

final class PatternTest extends TestCase
{
    public function testCanParseVariablesFromFilename(): void
    {
        $pattern = new Pattern('{path}/{name}_{startDate}-{endDate}.{extension}');
        $variables = $pattern->parse('/path/to/file/update_XY_example.com_20180917-20180923.csv');

        $this->assertArrayHasKey('path', $variables);
        $this->assertArrayHasKey('name', $variables);
        $this->assertArrayHasKey('startDate', $variables);
        $this->assertArrayHasKey('endDate', $variables);
        $this->assertArrayHasKey('extension', $variables);

        $this->assertEquals('/path/to/file', $variables['path']);
        $this->assertEquals('update_XY_example.com', $variables['name']);
        $this->assertEquals('20180917', $variables['startDate']);
        $this->assertEquals('20180923', $variables['endDate']);
        $this->assertEquals('csv', $variables['extension']);
    }

    public function testCanParseVariablesFromSql(): void
    {
        $pattern = new Pattern('SELECT {columns} FROM {table} LIMIT {limit}');
        $variables = $pattern->parse('select foo from bar limit 42');

        $this->assertArrayHasKey('columns', $variables);
        $this->assertArrayHasKey('table', $variables);
        $this->assertArrayHasKey('limit', $variables);

        $this->assertEquals('foo', $variables['columns']);
        $this->assertEquals('bar', $variables['table']);
        $this->assertEquals(42, $variables['limit']);
    }

    public function testHandlesNoPlaceholdersInPattern(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Pattern('No placeholders available in this pattern');
    }
}

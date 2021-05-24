<?php

declare(strict_types=1);

namespace App\Tests\Application\Command;

use App\Command\PrintCityForecastCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

final class PrintCityForecastCommandTest extends KernelTestCase
{
    private const REGEX_PATTERN = "/(^Processed city ([a-zA-Zö\.-]+ ?)+\| ([a-zA-Z]+ ?)+- ([a-zA-Z]+ ?)+$)/";

    private PrintCityForecastCommand $command;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;
        $command = $container->get('console.command.print_city_forecast');
        $this->assertNotNull($command);
        $this->assertInstanceOf(PrintCityForecastCommand::class, $command);
        if (is_object($command) && PrintCityForecastCommand::class == get_class($command)) {
            $this->command = $command;
        }
    }

    public function testCommandHasPrinted100RowsWithExpectedFormattation(): void
    {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $this->command->execute($input, $output);

        $content = $output->fetch();
        $rows = explode(\PHP_EOL, trim($content));
        $this->assertCount(100, $rows);
        foreach ($rows as $row) {
            $this->assertMatchesRegularExpression(self::REGEX_PATTERN, $row);
        }
    }
}

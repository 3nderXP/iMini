<?php

namespace Core\Console\Commands;

use Core\Console\Interfaces\CommandInterface;

class Help implements CommandInterface {

    public readonly string $description;

    private string $purple = "\033[35m";
    private string $green  = "\033[32m";
    private string $bold   = "\033[1m";
    private string $reset  = "\033[0m";

    public function __construct() {

        $this->description = "Show all available commands with descriptions.";

    }

    public function handle(array $params = []): void {

        $commands = $params['commands'] ?? [];

        if (empty($commands)) {

            echo $this->bold . "No commands found." . $this->reset . "\n";
            
        }
        
        echo $this->separator();

        echo $this->bold . "Usage:\n" . $this->reset;
        echo "  php cli.php [command]\n\n";

        echo $this->bold . "Available Commands:\n" . $this->reset;

        $longestCommand = max(array_map('strlen', array_keys($commands)));

        foreach ($commands as $command => $class) {

            $commandObj = new $class();
            $padding = str_repeat(' ', $longestCommand - strlen($command) + 2);

            echo "  " . $this->purple . $command . $this->reset . $padding .
                $this->green . $commandObj->description . $this->reset . "\n";

        }

        echo $this->separator();

    }

    private function separator(int $length = 50): string {

        return $this->bold .
                "\n" . str_repeat("-", $length) . "\n\n" .
               $this->reset;

    }

}

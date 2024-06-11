<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DecryptPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:decrypt-pdf {inputFile} {outputFile} {userPassword}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decrypt a PDF file with a password.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inputFile = $this->argument('inputFile');
        $outputFile = $this->argument('outputFile');
        $userPassword = $this->argument('userPassword');

        $command = "/opt/homebrew/bin/pdftk {$inputFile} input_pw {$userPassword} output {$outputFile}";
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error($process->getErrorOutput());
            $this->error("The command failed to execute: {$process->getErrorOutput()}");
            return 1;
        }

        $this->info("PDF decrypted successfully.");
        return 0;
    }
}
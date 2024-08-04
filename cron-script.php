<?php

// Define the directory where your Symfony application resides
// $symfonyAppDir = '/var/www/html/rat-hawk-backend';

// Command to execute
$command = "php bin/console app:handle-hotel-dump";

// Change directory to the Symfony app directory
// chdir($symfonyAppDir);

// Execute the command
$output = shell_exec($command);

// Log or output the result if needed
echo ('Cron working');

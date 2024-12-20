<?php
// Autoload Composer dependencies

use \Illuminate\Support\Carbon as Date;
use Illuminate\Support\Facades\Facade;

require_once __DIR__ . '/../vendor/autoload.php';

// Set up your application configuration
// Initialize slim application
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Crea un'istanza del gestore del database (Capsule)
$capsule = new \Illuminate\Database\Capsule\Manager();

// Aggiungi la configurazione del database al Capsule
$connections = require_once __DIR__.'/../config/database.php';
$capsule->addConnection($connections['mysql']);

// Esegui il boot del Capsule
$capsule->bootEloquent();
$capsule->setAsGlobal();
// Set up log tail
require_once __DIR__ . '/../config/logger.php';


// Set up the Facade application
Facade::setFacadeApplication([
    'log' => $logger,
    'date' => new Date()
]);

// Check application environment
if(env('APP_KEY') === null) {
    throw new Exception('APP_KEY not set');
}
<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())
    ->usePutenv()
    ->bootEnv(dirname(__DIR__) . '/.env')
;

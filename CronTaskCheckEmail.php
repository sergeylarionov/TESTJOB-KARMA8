<?php

declare(strict_types = 1);

require 'EmailServiceFunctions.php';

// запуск 1 раз в минуту (один инстанс)
runTaskCheckEmails(10);

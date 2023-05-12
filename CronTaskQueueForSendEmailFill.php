<?php

declare(strict_types = 1);

require 'EmailServiceFunctions.php';

$msgTemplate = "{username}, your subscription is expiring soon";

// запуск 1 раз в день
runTaskQueueForSendEmailFill(1, 1000, $msgTemplate);
runTaskQueueForSendEmailFill(3, 1000, $msgTemplate);
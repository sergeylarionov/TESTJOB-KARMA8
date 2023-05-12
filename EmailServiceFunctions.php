<?php

declare(strict_types = 1);

/**
 * Блок кода для запуска крон заданий
 */
function runTaskCheckEmails(int $batchSize): void
{
    $notCheckedUsers = getNotCheckedUsers($batchSize);

    foreach ($notCheckedUsers as $notCheckedUser) {
        if (checkEmail($notCheckedUser['email'])) {
            if (!updateChecked($notCheckedUser['id'], true)) {
                logError('error update user');
            }
        }
    }
}

function runTaskQueueForSendEmailFill(int $daysToEndLicense, int $batchSize, string $msgTemplate): void
{
    $run = true;
    while ($run) {
        $usersToSendNotify = getCheckedUsersWhoseLicenseIsExpiring($daysToEndLicense, $batchSize);

        $run = count($usersToSendNotify) === $batchSize;

        foreach ($usersToSendNotify as $user) {
            $msg = str_replace('{username}', $user['username'], $msgTemplate);
            if (!addTaskToQueue($msg) ) {
                logError('error add task to queue');
            }
        }
    }
}

function runTaskQueueForSendEmailPrepare(string $emailFrom, int $batchSize): void
{
    for ($i = 0; $i < $batchSize; $i++) {
        $taskForSend = getTaskFromQueue();
        if (empty($taskForSend)) {
            break;
        }

        if (!sendEmail($emailFrom, $taskForSend['emailTo'], $taskForSend['message'])) {
            logError('error send email');
            queueTaskTryCountIncrease();
        }

        if (queueTaskMarkAsSent($taskForSend['id'])) {
            logError('error mark task as sent');
        }
    }
}

/**
 * Эмуляция ф-ий внешних сервисов и логирования
 */
function sendEmail(string $emailFrom, string $emailTo, string $message): bool
{
    sleep(random_int(1, 60));

    return (bool)random_int(0, 10);
}

function checkEmail(string $email): bool
{
    // стоимость вызова 1 руб!
    sleep(random_int(1, 10));

    return (bool)random_int(0, 10);
}

function logError(string $message): void
{}


/**
 * ф-ии для работы с БД
 */
function getNotCheckedUsers(int $limit): array
{
    // возвращаем массив данных по юзерам (не подтвержденные confirmed=0 и checked=0)
    return [];
}

function updateChecked(int $id, bool $checked): bool
{
    // апдейтим в БД юзера (поле checked)
    return true;
}

function getCheckedUsersWhoseLicenseIsExpiring(int $daysToEndLicense, int $batchSize): array
{
    // возвращаем массив данных по юзерам (у которых заканчивается подписка)
    return [];
}

function addTaskToQueue(string $msg): bool
{
    // добавляем запись в очередь на отправку
    return true;
}

function getTaskFromQueue(): array
{
    // берем первое задание в очереди на отправку
    return [];
}

function queueTaskTryCountIncrease(): void
{
    // увеличим счетчик неудачных отправок для задания на отправку
}

function queueTaskMarkAsSent(int $id): bool
{
    // пометить задание в очереди как отправленное
    return true;
}
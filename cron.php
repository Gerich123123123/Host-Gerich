<?php

class ssh2Library {
    public function connect($hostname, $username, $password) {
        if($link = ssh2_connect($hostname, 22)) {
            if(ssh2_auth_password($link, $username, $password)) {
                return $link;
            }
        }
        die("#Ошибка Не удалось соединиться с сервером!");
    }

    public function execute($link, $cmd)
    {
        $stream = ssh2_exec($link, $cmd);
        stream_set_blocking($stream, true);
        $output = '';
        while ($get = fgets($stream)) {
            $output .= $get;
        }
        fclose($stream);
        return $output;
    }

    public function disconnect($link) {
        ssh2_exec($link, "exit");
    }
}

// Функция для очистки файлов лаунчера
function cleanup() {
    $ssh2Lib = new ssh2Library();
    $link = $ssh2Lib->connect($ip_lan, "root", $pass_lan);
    $installsDir = '/var/www/candy-games.ru/launchers';

    $ssh2Lib->execute($link, "rm -rf $installsDir/installs");
    $ssh2Lib->execute($link, "cd $installsDir && mkdir installs");
    echo "Лаунчеры успешно очищены!" . PHP_EOL;
    $ssh2Lib->disconnect($link);
}

// Проверяем, был ли передан параметр с именем функции
if(isset($argv[1])) {
    $action = $argv[1];

    // Выполняем соответствующую функцию, если она существует
    switch ($action) {
        case 'cleanup':
            cleanup();
            break;
        default:
            // В случае передачи недопустимого значения
            echo "Недопустимая функция.";
            break;
    }
} else {
    // Если параметр не был передан
    echo "Не указана функция для выполнения.";
}

?>

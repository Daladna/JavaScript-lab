<?php
// Настройки приложения
return [
    'db_path'         => __DIR__ . '/../database/afisha.sqlite',
    'mail_log'        => __DIR__ . '/../database/mail.log',
    'reset_ttl'       => 3600,   // время жизни ссылки восстановления пароля (сек)
    'show_reset_link' => true,   // демо-режим: показывать ссылку сброса на странице (на локальном сервере нет почты)
];

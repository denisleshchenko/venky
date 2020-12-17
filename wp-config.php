<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'venky' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'root' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', '' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'lFv0`9WFpdbq4SX rD-h!1IKfYAVyuv|,9t@e4qgR $}pTgLQGw`-4_5W`{T+l22' );
define( 'SECURE_AUTH_KEY',  'WiFy)LL~A6R3ef$j_ve]aU6]iTbG)eQ4af=Xl&E=>$*yd<ImHn*F+h@-$B0ar:@L' );
define( 'LOGGED_IN_KEY',    'A7+%e[h}1ZOu^S~}N9gVKi#HBb}^JyOi[)/}({{Us3jz7bPlR_s/(2C|y^cd`L-S' );
define( 'NONCE_KEY',        'l~;0$1r>0ZGz0MFtFQ(u/X8HV>E JQ{(#q,&c #D!9k-0t685&6/xAAJM@MS_orf' );
define( 'AUTH_SALT',        'ax|Y8(F7pbVw5u 5Sbn&a9h$uL} ;Kru>OBD@lI[Y6&5vNbMIT?J[06OeE8%r{FU' );
define( 'SECURE_AUTH_SALT', 'Jm5L%r/V+IiPG`g<yo)F1Dx:g~}D40`,cAz&i7{{{Bfbg$14-Xk:O^*@1q!FJtY}' );
define( 'LOGGED_IN_SALT',   'uw>VXyg]k&4dCEA.50*r80V[Z>>2MO{r/Rmv *Hy$9}/Qalr854B57bD:YgfI(p1' );
define( 'NONCE_SALT',       'v*q8;*o/l6@}M<^p7D:SSQ56W1o>!dd:32)V_5YfY]P!wT^>5 . I(IXd~^tVV%/' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wk_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );

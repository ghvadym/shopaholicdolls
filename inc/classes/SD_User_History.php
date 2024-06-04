<?php


class SD_User_History
{
    public static string $table_products = '';
    public static string $user_ip = '';

    private static $wpdb;


    static function instance()
    {
        return new static;
    }


    public function __construct()
    {
        global $wpdb;
        self::$wpdb = $wpdb;
        self::$table_products = self::$wpdb->prefix . 'user_views';
        self::$user_ip = ip2long(self::get_user_ip()); // make int from ip
    }

    public static function get_user_ip(): string
    {
        $user_ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $user_ip;
    }

    /**
     * @description installs the user views table in database
     */
    public static function create_table()
    {
        $charset_collate = self::$wpdb->get_charset_collate();
        $table = self::$table_products;

        $create_table = "
            CREATE TABLE IF NOT EXISTS {$table} (
            `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
            `ip` INT(20) UNSIGNED NULL,
            `products` TEXT NULL,
            `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) {$charset_collate} ";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($create_table);

        if (is_table_exists($table)) {
            update_option('table_views_installed', 'yes');
        }
    }


    /**
     * @param int $post_id
     * @return bool
     * @description add a new value
     */
    public static function set_value(int $post_id = 0): bool
    {
        if (!$post_id) {
            return false;
        }

        $row = self::get_row();

        /* add new row with IP address if not exists */
        if (empty($row)) {
            self::add_row($post_id);
            return true;
        }

        $products = $row[0]['products'] ?? [];

        if (!empty($products)) {
            $products = unserialize($products);
        }

        array_unshift($products, $post_id);
        $products = array_unique($products);

        if (count($products) > 10) {
            array_pop($products);
        }

        return !!self::$wpdb->update(
            self::$table_products,
            ['products' => serialize($products)],
            ['ip'       => self::$user_ip]
        );
    }


    /**
     * @param int $post_id
     * @return int|null
     * @description add a new user
     */
    public static function add_row(int $post_id = 0): ?int
    {
        if (!$post_id) {
            return null;
        }

        return self::$wpdb->insert(
            self::$table_products,
            [
                'ip'       => self::$user_ip,
                'products' => serialize([$post_id])
            ]
        );
    }


    /**
     * @return array|null
     * @description gets the user id by ip
     */
    public static function get_row(): ?array
    {
        $table = self::$table_products;
        $ip = self::$user_ip;

        return self::$wpdb->get_results(
            self::$wpdb->prepare(
                "SELECT * FROM {$table} WHERE ip = %d LIMIT 1",
                $ip
            ),
            ARRAY_A
        );
    }


    /**
     * @return array|null
     */
    public static function get_viewed_posts(): ?array
    {
        $table = self::$table_products;

        $rows = self::$wpdb->get_results(
            self::$wpdb->prepare(
                "SELECT products FROM {$table} WHERE ip = %d LIMIT 1",
                self::$user_ip
            ),
            ARRAY_A
        );

        if (empty($rows)) {
            return [];
        }

        return unserialize($rows[0]['products']);
    }
}

SD_User_History::instance();
<?php
require_once 'DB.php';
class Logger
{

    public static function log($ip_address, $user_agent, $page_url){

        if ($row = DB::row("SELECT * FROM `visitors` WHERE `ip_address` = :ip_address AND `user_agent` = :user_agent AND `page_url` = :page_url",
            [
                'ip_address' => $ip_address,
                'user_agent' => $user_agent,
                'page_url' => $page_url
            ])){
            DB::update('visitors',
                [
                    'views_count' => $row['views_count'] + 1,
                    'view_date' => date('Y-m-d H:i:s'),
                ],
                ['id' => $row['id']]);

        } else {
            DB::insert('visitors',
                [
                    'ip_address' => $ip_address,
                    'view_date' => date('Y-m-d H:i:s'),
                    'user_agent' => $user_agent,
                    'page_url' => $page_url,
                    'views_count' => 1
                ]
            );
        }
    }
}
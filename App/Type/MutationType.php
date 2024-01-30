<?php

namespace App\Type;

use App\DB;
use App\Types;
use App\AESIO as AESIO;
use GraphQL\Type\Definition\ObjectType;

class MutationType extends ObjectType
{
    private function checkAuth($root, $model, $index = 0)
    {
        $curent_role = array_filter($root['roles'], function ($var) use ($model, $index) {
            if (!empty($var["model"]) && !empty($var["enable_code"])) {
                return $var["model"] == $model && substr(str_pad(decbin($var["enable_code"]), 3, '0', STR_PAD_LEFT), $index, 1) == 1;
            } else {
                return;
            }
        });
        return COUNT($curent_role);
    }

    public function quoteIdentifier($str)
    {
        return str_replace("'", "\\'", $str);
    }

    private function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function IPtoLocation($ip)
    {
        $apiURL = 'https://freegeoip.app/json/' . $ip;

        // Make HTTP GET request using cURL 
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $apiResponse = curl_exec($ch);
        if ($apiResponse === FALSE) {
            $msg = curl_error($ch);
            curl_close($ch);
            return false;
        }
        curl_close($ch);

        // Retrieve IP data from API response 
        $ipData = json_decode($apiResponse, true);

        // Return geolocation data 
        return !empty($ipData) ? $ipData : false;
    }

    public function __construct()
    {
        $config = [
            'fields' => function () {
                return [
                    'create_quotation' => [
                        'type' => Types::quotation(),
                        'description' => 'Adding new quotation',
                        'args' => [
                            'id_product' => Types::nonNull(Types::int()),
                            'quotation_quantity' => Types::nonNull(Types::int()),
                            'quotation_customer_name' => Types::nonNull(Types::string()),
                            'quotation_customer_address' => Types::nonNull(Types::string()),
                            'quotation_customer_phone' => Types::nonNull(Types::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            $newQuotation = DB::insert("INSERT INTO quotation (id_product, quotation_quantity, quotation_customer_name, quotation_customer_address, quotation_customer_phone) VALUES ({$args['id_product']},{$args['quotation_quantity']},'{$args['quotation_customer_name']}','{$args['quotation_customer_address']}','{$args['quotation_customer_phone']}')");
                            return DB::selectOne("SELECT * FROM quotation WHERE id_quotation = $newQuotation");
                        }
                    ],



                    'create_menu_top' => [
                        'type' => Types::menu_top(),
                        'description' => 'Adding a menu top',
                        'args' => [
                            'id_model' => Types::nonNull(Types::int()),
                            'model_name' => Types::nonNull(Types::string()),
                            'order_index' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "menu", 1) > 0) {
                                    $menuTopId = DB::insert("INSERT INTO menu_top (id_user, id_model, model_name, order_index) VALUES ({$root['id_user']},{$args['id_model']},'{$args['model_name']}','{$args['order_index']}')");
                                    return DB::selectOne("SELECT * FROM menu_top WHERE id_menu_top = $menuTopId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_menu_top' => [
                        'type' => Types::menu_top(),
                        'description' => 'Change post',
                        'args' => [
                            'id_menu_top' => Types::nonNull(Types::int()),
                            'id_model' => Types::int(),
                            'model_name' => Types::string(),
                            'order_index' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "post", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_menu_top") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE menu_top SET " . implode(', ', $setCondition) . " WHERE id_menu_top = {$args['id_menu_top']}");
                                    $user = DB::selectOne("SELECT * FROM menu_top WHERE id_menu_top = {$args['id_menu_top']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No menu_top with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'create_menu_main' => [
                        'type' => Types::menu_main(),
                        'description' => 'Adding a menu main',
                        'args' => [
                            'id_model' => Types::nonNull(Types::int()),
                            'model_name' => Types::nonNull(Types::string()),
                            'order_index' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "menu", 1) > 0) {
                                    $menuTopId = DB::insert("INSERT INTO menu_main (id_user, id_model, model_name, order_index) VALUES ({$root['id_user']},{$args['id_model']},'{$args['model_name']}','{$args['order_index']}')");
                                    return DB::selectOne("SELECT * FROM menu_main WHERE id_menu_main = $menuTopId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_menu_main' => [
                        'type' => Types::menu_main(),
                        'description' => 'Change post',
                        'args' => [
                            'id_menu_main' => Types::nonNull(Types::int()),
                            'id_model' => Types::int(),
                            'model_name' => Types::string(),
                            'order_index' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "post", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_menu_main") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE menu_main SET " . implode(', ', $setCondition) . " WHERE id_menu_main = {$args['id_menu_main']}");
                                    $user = DB::selectOne("SELECT * FROM menu_main WHERE id_menu_main = {$args['id_menu_main']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No menu_main with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'create_menu_bar' => [
                        'type' => Types::menu_bar(),
                        'description' => 'Adding a menu bar',
                        'args' => [
                            'id_model' => Types::nonNull(Types::int()),
                            'model_name' => Types::nonNull(Types::string()),
                            'order_index' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "menu", 1) > 0) {
                                    $menuTopId = DB::insert("INSERT INTO menu_bar (id_user, id_model, model_name, order_index) VALUES ({$root['id_user']},{$args['id_model']},'{$args['model_name']}','{$args['order_index']}')");
                                    return DB::selectOne("SELECT * FROM menu_bar WHERE id_menu_bar = $menuTopId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_menu_bar' => [
                        'type' => Types::menu_bar(),
                        'description' => 'Change post',
                        'args' => [
                            'id_menu_bar' => Types::nonNull(Types::int()),
                            'id_model' => Types::int(),
                            'model_name' => Types::string(),
                            'order_index' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "post", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_menu_bar") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE menu_bar SET " . implode(', ', $setCondition) . " WHERE id_menu_bar = {$args['id_menu_bar']}");
                                    $user = DB::selectOne("SELECT * FROM menu_bar WHERE id_menu_bar = {$args['id_menu_bar']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No menu_bar with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'create_calendar' => [
                        'type' => Types::calendar(),
                        'description' => 'Adding a calendar',
                        'args' => [
                            'start_datetime' => Types::string(),
                            'end_datetime' => Types::string(),
                            'title_th' => Types::string(),
                            'title_en' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'link' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {

                            if (!empty($root)) {
                                if ($this->checkAuth($root, "calendar", 1) > 0) {
                                    $calendarId = DB::insert("INSERT INTO calendar(id_user, start_datetime, end_datetime, title_th, title_en, description_th, description_en, link) 
                            VALUES ({$root['id_user']},'{$args['start_datetime']}','{$args['end_datetime']}','{$args['title_th']}','{$args['title_en']}','{$args['description_th']}','{$args['description_en']}','{$args['link']}')");
                                    return DB::selectOne("SELECT * FROM calendar WHERE id_calendar = $calendarId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_calendar' => [
                        'type' => Types::calendar(),
                        'description' => 'Change calendar',
                        'args' => [
                            'id_calendar' => Types::nonNull(Types::int()),
                            'start_datetime' => Types::string(),
                            'end_datetime' => Types::string(),
                            'title_th' => Types::string(),
                            'title_en' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'link' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "calendar", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_calendar") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE calendar SET " . implode(', ', $setCondition) . " WHERE id_calendar = {$args['id_calendar']}");
                                    $user = DB::selectOne("SELECT * FROM calendar WHERE id_calendar = {$args['id_calendar']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No calendar with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_calendar' => [
                        'type' => Types::calendar(),
                        'description' => 'Remove calendar',
                        'args' => [
                            'id_calendar' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "calendar", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM calendar WHERE id_calendar = {$args['id_calendar']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM calendar WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM calendar WHERE id_calendar = {$args['id_calendar']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],
                    'create_instructor' => [
                        'type' => Types::instructor(),
                        'description' => 'Adding a instructor',
                        'args' => [
                            'code' => Types::string(),
                            'name_th' => Types::nonNull(Types::string()),
                            'name_en' => Types::nonNull(Types::string()),
                            'image_url' => Types::string(),
                            'major' => Types::string(),
                            'university' => Types::string(),
                            'position_name_th' => Types::string(),
                            'position_name_en' => Types::string(),
                            'key_id' => Types::string(),
                            'parent_key_id' => Types::string(),
                            'size' => Types::string(),
                            'education_th' => Types::string(),
                            'education_en' => Types::string(),
                            'portfolio_th' => Types::string(),
                            'portfolio_en' => Types::string(),
                            'room_th' => Types::string(),
                            'room_en' => Types::string(),
                            'email' => Types::string(),
                            'tel_th' => Types::string(),
                            'tel_en' => Types::string(),
                            'phone' => Types::string(),
                            'website' => Types::string(),
                            'order_index' => Types::int(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "instructor", 1) > 0) {
                                    $setColumn = [];
                                    $setValue = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value)) {
                                            //$setCondition[] = "{$column} = '{$value}'";
                                            $setColumn[] = $column;
                                            $setValue[] = "'{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    //echo "INSERT INTO instructor (id_user, " . implode(', ', $setColumn) . ") VALUES ({$root['id_user']}, ". implode(', ', $setValue) .")";
                                    $instructorId = DB::insert("INSERT INTO instructor (id_user, " . implode(', ', $setColumn) . ") 
                                                                VALUES ({$root['id_user']}, " . implode(', ', $setValue) . ")");
                                    return DB::selectOne("SELECT * FROM instructor WHERE id_instructor = $instructorId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_instructor' => [
                        'type' => Types::instructor(),
                        'description' => 'Change instructor',
                        'args' => [
                            'id_instructor' => Types::nonNull(Types::int()),
                            'code' => Types::string(),
                            'name_th' => Types::nonNull(Types::string()),
                            'name_en' => Types::nonNull(Types::string()),
                            'image_url' => Types::string(),
                            'major' => Types::string(),
                            'university' => Types::string(),
                            'position_name_th' => Types::string(),
                            'position_name_en' => Types::string(),
                            'key_id' => Types::string(),
                            'parent_key_id' => Types::string(),
                            'size' => Types::string(),
                            'education_th' => Types::string(),
                            'education_en' => Types::string(),
                            'portfolio_th' => Types::string(),
                            'portfolio_en' => Types::string(),
                            'room_th' => Types::string(),
                            'room_en' => Types::string(),
                            'email' => Types::string(),
                            'tel_th' => Types::string(),
                            'tel_en' => Types::string(),
                            'phone' => Types::string(),
                            'website' => Types::string(),
                            'order_index' => Types::int(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "instructor", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_instructor") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    //echo "UPDATE instructor SET " . implode(', ', $setCondition) . " WHERE id_instructor = {$args['id_instructor']}";
                                    DB::update("UPDATE instructor SET " . implode(', ', $setCondition) . " WHERE id_instructor = {$args['id_instructor']}");
                                    $instructor = DB::selectOne("SELECT * FROM instructor WHERE id_instructor = {$args['id_instructor']}");
                                    if (is_null($instructor)) {
                                        throw new \Exception('No instructor with this id');
                                    }
                                    return $instructor;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_instructor' => [
                        'type' => Types::instructor(),
                        'description' => 'Remove instructor',
                        'args' => [
                            'id_instructor' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "instructor", 2) > 0) {
                                    $before_instructor = DB::selectOne("SELECT * FROM instructor WHERE id_instructor = {$args['id_instructor']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $instructor = DB::delete("DELETE FROM instructor WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $instructor = DB::delete("DELETE FROM instructor WHERE id_instructor = {$args['id_instructor']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $instructor);
                                    return $instructor ? $before_instructor : null;
                                }
                            }
                            return;
                        }
                    ],

                    'create_post' => [
                        'type' => Types::post(),
                        'description' => 'Adding a post',
                        'args' => [
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "post", 1) > 0) {
                                    $pressReleaseId = DB::insert("INSERT INTO post (id_user, title_th, title_en, content_th, content_en, image_cover, description_th, description_en, active) 
                            VALUES ({$root['id_user']},'{$args['title_th']}','{$args['title_en']}','{$args['content_th']}','{$args['content_en']}','{$args['image_cover']}','{$args['description_th']}','{$args['description_en']}','{$args['active']}')");
                                    return DB::selectOne("SELECT * FROM post WHERE id_post = $pressReleaseId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_post' => [
                        'type' => Types::post(),
                        'description' => 'Change post',
                        'args' => [
                            'id_post' => Types::nonNull(Types::int()),
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "post", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_post") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE post SET " . implode(', ', $setCondition) . " WHERE id_post = {$args['id_post']}");
                                    $user = DB::selectOne("SELECT * FROM post WHERE id_post = {$args['id_post']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No post with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_post' => [
                        'type' => Types::post(),
                        'description' => 'Remove post',
                        'args' => [
                            'id_post' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "post", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM post WHERE id_post = {$args['id_post']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM post WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM post WHERE id_post = {$args['id_post']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],

                    'create_page' => [
                        'type' => Types::page(),
                        'description' => 'Adding a page',
                        'args' => [
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "page", 1) > 0) {
                                    $pressReleaseId = DB::insert("INSERT INTO page (id_user, title_th, title_en, content_th, content_en, image_cover, description_th, description_en, active) 
                            VALUES ({$root['id_user']},'{$args['title_th']}','{$args['title_en']}','{$args['content_th']}','{$args['content_en']}','{$args['image_cover']}','{$args['description_th']}','{$args['description_en']}','{$args['active']}')");

                                    return DB::selectOne("SELECT * FROM page WHERE id_page = $pressReleaseId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_page' => [
                        'type' => Types::page(),
                        'description' => 'Change page',
                        'args' => [
                            'id_page' => Types::nonNull(Types::int()),
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "page", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_page") {
                                            $setCondition[] = "{$column} = '{$this->quoteIdentifier($value)}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE page SET " . implode(', ', $setCondition) . " WHERE id_page = {$args['id_page']}");
                                    $page = DB::selectOne("SELECT * FROM page WHERE id_page = {$args['id_page']}");
                                    if (is_null($page)) {
                                        throw new \Exception('No page with this id');
                                    }
                                    return $page;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_page' => [
                        'type' => Types::page(),
                        'description' => 'Remove page',
                        'args' => [
                            'id_page' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "page", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM page WHERE id_page = {$args['id_page']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM page WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM page WHERE id_page = {$args['id_page']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],

                    'create_press_release' => [
                        'type' => Types::press_release(),
                        'description' => 'Adding a press_release',
                        'args' => [
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "press_release", 1) > 0) {
                                    $pressReleaseId = DB::insert("INSERT INTO press_release (id_user, title_th, title_en, content_th, content_en, image_cover, description_th, description_en, active) 
                            VALUES ({$root['id_user']},'{$args['title_th']}','{$args['title_en']}','{$args['content_th']}','{$args['content_en']}','{$args['image_cover']}','{$args['description_th']}','{$args['description_en']}','{$args['active']}')");

                                    return DB::selectOne("SELECT * FROM press_release WHERE id_press_release = $pressReleaseId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_press_release' => [
                        'type' => Types::press_release(),
                        'description' => 'Change press_release',
                        'args' => [
                            'id_press_release' => Types::nonNull(Types::int()),
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "press_release", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_press_release") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE press_release SET " . implode(', ', $setCondition) . " WHERE id_press_release = {$args['id_press_release']}");
                                    $user = DB::selectOne("SELECT * FROM press_release WHERE id_press_release = {$args['id_press_release']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No press_release with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_press_release' => [
                        'type' => Types::press_release(),
                        'description' => 'Remove press_release',
                        'args' => [
                            'id_press_release' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "press_release", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM press_release WHERE id_press_release = {$args['id_press_release']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM press_release WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM press_release WHERE id_press_release = {$args['id_press_release']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],

                    'create_job_new' => [
                        'type' => Types::job_new(),
                        'description' => 'Adding a job_new',
                        'args' => [
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "job_new", 1) > 0) {
                                    $pressReleaseId = DB::insert("INSERT INTO job_new (id_user, title_th, title_en, content_th, content_en, image_cover, description_th, description_en, active) 
                            VALUES ({$root['id_user']},'{$args['title_th']}','{$args['title_en']}','{$args['content_th']}','{$args['content_en']}','{$args['image_cover']}','{$args['description_th']}','{$args['description_en']}','{$args['active']}')");

                                    return DB::selectOne("SELECT * FROM job_new WHERE id_job_new = $pressReleaseId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_job_new' => [
                        'type' => Types::job_new(),
                        'description' => 'Change job_new',
                        'args' => [
                            'id_job_new' => Types::nonNull(Types::int()),
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "job_new", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_job_new") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE job_new SET " . implode(', ', $setCondition) . " WHERE id_job_new = {$args['id_job_new']}");
                                    $user = DB::selectOne("SELECT * FROM job_new WHERE id_job_new = {$args['id_job_new']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No job_new with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_job_new' => [
                        'type' => Types::job_new(),
                        'description' => 'Remove job_new',
                        'args' => [
                            'id_job_new' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "job_new", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM job_new WHERE id_job_new = {$args['id_job_new']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM job_new WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM job_new WHERE id_job_new = {$args['id_job_new']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],

                    'create_study_tour' => [
                        'type' => Types::study_tour(),
                        'description' => 'Adding a study_tour',
                        'args' => [
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "study_tour", 1) > 0) {
                                    $pressReleaseId = DB::insert("INSERT INTO study_tour (id_user, title_th, title_en, content_th, content_en, image_cover, description_th, description_en, active) 
                            VALUES ({$root['id_user']},'{$args['title_th']}','{$args['title_en']}','{$args['content_th']}','{$args['content_en']}','{$args['image_cover']}','{$args['description_th']}','{$args['description_en']}','{$args['active']}')");

                                    return DB::selectOne("SELECT * FROM study_tour WHERE id_study_tour = $pressReleaseId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_study_tour' => [
                        'type' => Types::study_tour(),
                        'description' => 'Change study_tour',
                        'args' => [
                            'id_study_tour' => Types::nonNull(Types::int()),
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "study_tour", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_study_tour") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE study_tour SET " . implode(', ', $setCondition) . " WHERE id_study_tour = {$args['id_study_tour']}");
                                    $user = DB::selectOne("SELECT * FROM study_tour WHERE id_study_tour = {$args['id_study_tour']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No study_tour with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_study_tour' => [
                        'type' => Types::study_tour(),
                        'description' => 'Remove study_tour',
                        'args' => [
                            'id_study_tour' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "study_tour", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM study_tour WHERE id_study_tour = {$args['id_study_tour']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM study_tour WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM study_tour WHERE id_study_tour = {$args['id_study_tour']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],

                    'create_student_activity' => [
                        'type' => Types::student_activity(),
                        'description' => 'Adding a student_activity',
                        'args' => [
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "student_activity", 1) > 0) {
                                    $pressReleaseId = DB::insert("INSERT INTO student_activity (id_user, title_th, title_en, content_th, content_en, image_cover, description_th, description_en, active) 
                            VALUES ({$root['id_user']},'{$args['title_th']}','{$args['title_en']}','{$args['content_th']}','{$args['content_en']}','{$args['image_cover']}','{$args['description_th']}','{$args['description_en']}','{$args['active']}')");

                                    return DB::selectOne("SELECT * FROM student_activity WHERE id_student_activity = $pressReleaseId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_student_activity' => [
                        'type' => Types::student_activity(),
                        'description' => 'Change student_activity',
                        'args' => [
                            'id_student_activity' => Types::nonNull(Types::int()),
                            'title_th' => Types::nonNull(Types::string()),
                            'title_en' => Types::nonNull(Types::string()),
                            'content_th' => Types::string(),
                            'content_en' => Types::string(),
                            'image_cover' => Types::string(),
                            'description_th' => Types::string(),
                            'description_en' => Types::string(),
                            'active' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "student_activity", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $column != "id_student_activity") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE student_activity SET " . implode(', ', $setCondition) . " WHERE id_student_activity = {$args['id_student_activity']}");
                                    $user = DB::selectOne("SELECT * FROM student_activity WHERE id_student_activity = {$args['id_student_activity']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No student_activity with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_student_activity' => [
                        'type' => Types::student_activity(),
                        'description' => 'Remove student_activity',
                        'args' => [
                            'id_student_activity' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "student_activity", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM student_activity WHERE id_student_activity = {$args['id_student_activity']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM student_activity WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM student_activity WHERE id_student_activity = {$args['id_student_activity']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],

                    'create_role' => [
                        'type' => Types::role(),
                        'description' => 'Adding a role',
                        'args' => [
                            'name_th' => Types::nonNull(Types::string()),
                            'name_en' => Types::nonNull(Types::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "role", 1) > 0) {
                                    $roleId = DB::insert("INSERT INTO role (name_th, name_en) VALUES ('{$args['name_th']}','{$args['name_en']}')");

                                    return DB::selectOne("SELECT * FROM role WHERE id_role = $roleId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_role' => [
                        'type' => Types::role(),
                        'description' => 'Change role',
                        'args' => [
                            'id_role' => Types::nonNull(Types::int()),
                            'name_th' => Types::nonNull(Types::string()),
                            'name_en' => Types::nonNull(Types::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "role", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (!empty($value) && $column != "id_role") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE role SET " . implode(', ', $setCondition) . " WHERE id_role = {$args['id_role']}");
                                    $user = DB::selectOne("SELECT * FROM role WHERE id_role = {$args['id_role']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No role with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_role' => [
                        'type' => Types::role(),
                        'description' => 'Remove role',
                        'args' => [
                            'id_role' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "role", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM role WHERE id_role = {$args['id_role']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM role WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM role WHERE id_role = {$args['id_role']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],
                    'update_permission' => [
                        'type' => Types::listOf(Types::permission()),
                        'description' => 'Adding a role',
                        'args' => [
                            'id_role' => Types::nonNull(Types::int()),
                            'data' => Types::nonNull(Types::json()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "permission", 1) > 0) {
                                    //var_dump($args["data"]);
                                    $data = $args["data"];
                                    //return;
                                    //$roleId = DB::insert("INSERT INTO permission (name_th, name_en) VALUES ('{$args['name_th']}','{$args['name_en']}')");

                                    foreach ($data as $key => $value) {
                                        $p = DB::selectOne("SELECT * FROM permission WHERE id_role = {$args['id_role']} AND model = '{$value['model']}'"); // AND enable_code = {$value['enable_code']}
                                        if (!empty($p)) {
                                            DB::update("UPDATE permission SET enable_code = '{$value['enable_code']}' WHERE id_role = {$args['id_role']} AND model = '{$value['model']}'");
                                        } else {
                                            DB::insert("INSERT INTO permission (id_role, model, enable_code) VALUES ('{$args['id_role']}','{$value['model']}','{$value['enable_code']}')");
                                        }
                                    }

                                    return DB::select("SELECT * FROM permission WHERE id_role = {$args['id_role']}");
                                }
                            }
                            return;
                        }
                    ],
                    /*
                    id_user
              id_role
              username
              phone
              firstname
              lastname
              email
              confirmed
              active
              block
                    */
                    'update_avatar' => [
                        'type' => Types::user(),
                        'description' => 'Update avatar',
                        'args' => [
                            'avatar' => Types::nonNull(Types::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                DB::update("UPDATE user SET avatar = '{$args['avatar']}' WHERE id_user = {$root['id_user']}");
                            }
                            $user = DB::selectOne("SELECT * FROM user WHERE id_user = {$root['id_user']}");
                            if (is_null($user)) {
                                throw new \Exception('No user with this id');
                            }
                            return $user;
                        }
                    ],
                    'update_profile' => [
                        'type' => Types::user(),
                        'description' => 'Update avatar',
                        'args' => [
                            'firstname' => Types::string(),
                            'lastname' => Types::string(),
                            'email' => Types::nonNull(Types::email()),
                            'phone' => Types::nonNull(Types::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                DB::update("UPDATE user SET avatar = '{$args['avatar']}' WHERE id_user = {$root['id_user']}");
                            }
                            $user = DB::selectOne("SELECT * FROM user WHERE id_user = {$root['id_user']}");
                            if (is_null($user)) {
                                throw new \Exception('No user with this id');
                            }
                            return $user;
                        }
                    ],
                    'update_student_avatar' => [
                        'type' => Types::student(),
                        'description' => 'Update avatar',
                        'args' => [
                            'avatar' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root) && $root['id_student']) {
                                DB::update("UPDATE student SET avatar = '{$args['avatar']}' WHERE id_student = {$root['id_student']}");
                            }
                            $student = DB::selectOne("SELECT * FROM student WHERE id_student = {$root['id_student']}");
                            if (is_null($student)) {
                                throw new \Exception('No student with this id');
                            }
                            return $student;
                        }
                    ],
                    'update_student_profile' => [
                        'type' => Types::student(),
                        'description' => 'Update avatar',
                        'args' => [
                            'id_card' => Types::string(),
                            'nickname' => Types::string(),
                            'phone' => Types::string(),
                            'birthday' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root) && $root['id_student']) {
                                $setCondition = [];
                                foreach ($args as $column => $value) {
                                    if (isset($value) && $value != "" && $column != "id_student") {
                                        if ($column == "password") {
                                            $aesio = new AESIO();
                                            $setCondition[] = "{$column} = '{$aesio->Encrypted($value)}'";
                                        } else {
                                            $setCondition[] = "{$column} = '{$value}'";
                                        }
                                        //$bindValues[] = $value;
                                    }
                                }

                                DB::update("UPDATE student SET " . implode(', ', $setCondition) . " WHERE id_student = {$root['id_student']}");
                            }
                            $student = DB::selectOne("SELECT * FROM student WHERE id_student = {$root['id_student']}");
                            if (is_null($student)) {
                                throw new \Exception('No student with this id');
                            }
                            return $student;
                        }
                    ],
                    'create_user' => [
                        'type' => Types::user(),
                        'description' => 'Adding a user',
                        'args' => [
                            'id_role' => Types::nonNull(Types::int()),
                            'username' => Types::nonNull(Types::string()),
                            'email' => Types::nonNull(Types::email()),
                            'phone' => Types::nonNull(Types::string()),
                            'password' => Types::nonNull(Types::string()),
                            'firstname' => Types::string(),
                            'lastname' => Types::string(),
                            'confirmed' => Types::int(),
                            'active' => Types::int(),
                            'block' => Types::int(),
                            'avatar' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "user", 1) > 0) {
                                    $before_user = DB::select("SELECT * FROM user WHERE username = '{$args['username']}' OR email = '{$args['email']}' OR phone = '{$args['phone']}'");
                                    $userId = null;

                                    if (empty($before_user)) {
                                        $aesio = new AESIO();
                                        $client_ip = $this->get_client_ip();
                                        //$location = $this->IPtoLocation($client_ip);
                                        $userId = DB::insert("INSERT INTO user (id_user_ref, id_role, username, email, phone, password, firstname, lastname, confirmed, active, block, ip_address, latitude, longitude, avatar) VALUES ({$root['id_user']},{$args['id_role']},'{$args['username']}','{$args['email']}','{$args['phone']}','{$aesio->Encrypted($args['password'])}','{$args['firstname']}','{$args['lastname']}',{$args['confirmed']},{$args['active']},{$args['block']}, '{$client_ip}', null, null, '{$args['avatar']}')");
                                    }
                                    return DB::selectOne("SELECT `id_user`, `id_user_ref`, `id_role`, `username`, `email`, `phone`, `firstname`, `lastname`, `nickname`, `confirmed`, `active`, `block`, `ip_address`, `latitude`, `longitude`, `avatar`, `created_at`, `update_at` FROM user WHERE id_user = $userId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_user' => [
                        'type' => Types::user(),
                        'description' => 'Change User',
                        'args' => [
                            'id_user' => Types::nonNull(Types::int()),
                            'id_role' => Types::nonNull(Types::int()),
                            'username' => Types::nonNull(Types::string()),
                            'email' => Types::nonNull(Types::email()),
                            'phone' => Types::nonNull(Types::string()),
                            'password' => Types::nonNull(Types::string()),
                            'firstname' => Types::string(),
                            'lastname' => Types::string(),
                            'confirmed' => Types::int(),
                            'active' => Types::int(),
                            'block' => Types::int(),
                            'avatar' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "user", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $value != "" && $column != "id_user") {
                                            if ($column == "password") {
                                                $aesio = new AESIO();
                                                $setCondition[] = "{$column} = '{$aesio->Encrypted($value)}'";
                                            } else {
                                                $setCondition[] = "{$column} = '{$value}'";
                                            }
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE user SET " . implode(', ', $setCondition) . " WHERE id_user = {$args['id_user']}");
                                    $user = DB::selectOne("SELECT `id_user`, `id_user_ref`, `id_role`, `username`, `email`, `phone`, `firstname`, `lastname`, `nickname`, `confirmed`, `active`, `block`, `ip_address`, `latitude`, `longitude`, `avatar`, `created_at`, `update_at` FROM user WHERE id_user = {$args['id_user']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No user with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_user' => [
                        'type' => Types::user(),
                        'description' => 'Remove User',
                        'args' => [
                            'id' => Types::nonNull(Types::int()),
                            'firstname' => Types::string(),
                            'lastname' => Types::string(),
                            'email' => Types::email(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "user", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT `id_user`, `id_user_ref`, `id_role`, `username`, `email`, `phone`, `firstname`, `lastname`, `nickname`, `confirmed`, `active`, `block`, `ip_address`, `latitude`, `longitude`, `avatar`, `created_at`, `update_at` FROM user WHERE id = {$args['id']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM user WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM user WHERE id = {$args['id']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],
                    /*'create_category' => [
                        'type' => Types::category(),
                        'description' => 'Adding a category',
                        'args' => [
                            'name' => Types::nonNull(Types::string()),
                            'description' => Types::string(),
                            'handle' => Types::string(),
                            'tag' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            $categoryId = DB::insert("INSERT INTO category (name, description, handle, tag) VALUES ('{$args['name']}','{$args['description']}','{$args['handle']}','{$args['tag']}')");
                            return DB::selectOne("SELECT * FROM category WHERE id_category = $categoryId");
                        }
                    ],
                    'update_category' => [
                        'type' => Types::category(),
                        'description' => 'Change Category',
                        'args' => [
                            'id_category' => Types::nonNull(Types::int()),
                            'name' => Types::nonNull(Types::string()),
                            'description' => Types::string(),
                            'handle' => Types::string(),
                            'tag' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            $setCondition = [];
                            foreach ($args as $column => $value) {
                                if (isset($value) && $column != "id_category") {
                                    $setCondition[] = "{$column} = '{$value}'";
                                    //$bindValues[] = $value;
                                }
                            }

                            DB::update("UPDATE category SET " . implode(', ', $setCondition) . " WHERE id_category = {$args['id_category']}");
                            $category = DB::selectOne("SELECT * FROM category WHERE id_category = {$args['id_category']}");
                            if (is_null($category)) {
                                throw new \Exception('No category with this id_category');
                            }
                            return $category;
                        }
                    ],
                    'delete_category' => [
                        'type' => Types::category(),
                        'description' => 'Remove Category',
                        'args' => [
                            'id_category' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            $before_user = DB::selectOne("SELECT * FROM category WHERE id_category = {$args['id_category']}");
                            $user = DB::delete("DELETE FROM category WHERE id_category = {$args['id_category']}");

                            return $user ? $before_user : null;
                        }
                    ],*/


                    'create_category_activity' => [
                        'type' => Types::category_activity(),
                        'description' => 'Adding a category_activity',
                        'args' => [
                            'name_th' => Types::nonNull(Types::string()),
                            'name_en' => Types::nonNull(Types::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "category_activity", 1) > 0) {
                                    $categoryActivityId = DB::insert("INSERT INTO category_activity (name_th, name_en) VALUES ('{$args['name_th']}','{$args['name_en']}')");

                                    return DB::selectOne("SELECT * FROM category_activity WHERE id_category_activity = $categoryActivityId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_category_activity' => [
                        'type' => Types::category_activity(),
                        'description' => 'Change category_activity',
                        'args' => [
                            'id_category_activity' => Types::nonNull(Types::int()),
                            'name_th' => Types::nonNull(Types::string()),
                            'name_en' => Types::nonNull(Types::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "category_activity", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (!empty($value) && $column != "id_category_activity") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE category_activity SET " . implode(', ', $setCondition) . " WHERE id_category_activity = {$args['id_category_activity']}");
                                    $user = DB::selectOne("SELECT * FROM category_activity WHERE id_category_activity = {$args['id_category_activity']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No category_activity with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_category_activity' => [
                        'type' => Types::category_activity(),
                        'description' => 'Remove category_activity',
                        'args' => [
                            'id_category_activity' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "category_activity", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM category_activity WHERE id_category_activity = {$args['id_category_activity']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM category_activity WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM category_activity WHERE id_category_activity = {$args['id_category_activity']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],
                    'create_subject_activity' => [
                        'type' => Types::subject_activity(),
                        'description' => 'Adding a subject_activity',
                        'args' => [
                            'id_category_activity' => Types::nonNull(Types::int()),
                            'date_start' => Types::string(),
                            'date_end' => Types::string(),
                            'name_th' => Types::nonNull(Types::string()),
                            'name_en' => Types::nonNull(Types::string()),
                            'credit' => Types::int(),
                            'hours' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "subject_activity", 1) > 0) {
                                    $categoryActivityId = DB::insert("INSERT INTO subject_activity (id_category_activity, date_start, date_end, name_th, name_en, credit, hours) VALUES ('{$args['id_category_activity']}','{$args['date_start']}','{$args['date_end']}','{$args['name_th']}','{$args['name_en']}',{$args['credit']},{$args['hours']})");

                                    return DB::selectOne("SELECT * FROM subject_activity WHERE id_subject_activity = $categoryActivityId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_subject_activity' => [
                        'type' => Types::subject_activity(),
                        'description' => 'Change subject_activity',
                        'args' => [
                            'id_subject_activity' => Types::nonNull(Types::int()),
                            'id_category_activity' => Types::nonNull(Types::int()),
                            'date_start' => Types::string(),
                            'date_end' => Types::string(),
                            'name_th' => Types::nonNull(Types::string()),
                            'name_en' => Types::nonNull(Types::string()),
                            'credit' => Types::int(),
                            'hours' => Types::int(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "subject_activity", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (!empty($value) && $column != "id_subject_activity") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE subject_activity SET " . implode(', ', $setCondition) . " WHERE id_subject_activity = {$args['id_subject_activity']}");
                                    $user = DB::selectOne("SELECT * FROM subject_activity WHERE id_subject_activity = {$args['id_subject_activity']}");
                                    if (is_null($user)) {
                                        throw new \Exception('No subject_activity with this id');
                                    }
                                    return $user;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_subject_activity' => [
                        'type' => Types::subject_activity(),
                        'description' => 'Remove subject_activity',
                        'args' => [
                            'id_subject_activity' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "subject_activity", 2) > 0) {
                                    $before_user = DB::selectOne("SELECT * FROM subject_activity WHERE id_subject_activity = {$args['id_subject_activity']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $user = DB::delete("DELETE FROM subject_activity WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $user = DB::delete("DELETE FROM subject_activity WHERE id_subject_activity = {$args['id_subject_activity']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $user ? $before_user : null;
                                }
                            }
                            return;
                        }
                    ],
                    'create_student' => [
                        'type' => Types::student(),
                        'description' => 'Adding a Student',
                        'args' => [
                            'id_student_card' => Types::nonNull(Types::string()),
                            'id_card' => Types::nonNull(Types::string()),
                            'student_year' => Types::nonNull(Types::int()),
                            'email' => Types::nonNull(Types::email()),
                            'phone' => Types::nonNull(Types::string()),
                            'password' => Types::nonNull(Types::string()),
                            'title_th' => Types::string(),
                            'firstname_th' => Types::string(),
                            'lastname_th' => Types::string(),
                            'title_en' => Types::string(),
                            'firstname_en' => Types::string(),
                            'lastname_en' => Types::string(),
                            'nickname' => Types::string(),
                            'major_code' => Types::string(),
                            'major' => Types::string(),
                            'student_status' => Types::string(),
                            'confirmed' => Types::int(),
                            'active' => Types::int(),
                            'block' => Types::int(),
                            'avatar' => Types::string(),
                            'birthday' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "student", 1) > 0) {
                                    $before_student = DB::select("SELECT * FROM student WHERE id_student_card = {$args['id_student_card']} OR id_card = {$args['id_card']} OR email = '{$args['email']}' OR phone = '{$args['phone']}'");
                                    $studentId = null;

                                    if (empty($before_student)) {
                                        $aesio = new AESIO();
                                        $client_ip = $this->get_client_ip();
                                        //$location = $this->IPtoLocation($client_ip);
                                        $studentId = DB::insert("INSERT INTO student (id_student_card, id_user, id_card, student_year, email, phone, password, title_th, firstname_th, lastname_th, title_en, firstname_en, lastname_en, nickname, major_code, major, student_status, confirmed, active, block, ip_address, birthday, avatar) VALUES ('{$args['id_student_card']}',{$root['id_user']},'{$args['id_card']}',{$args['student_year']},'{$args['email']}','{$args['phone']}','{$aesio->Encrypted($args['password'])}','{$args['title_th']}','{$args['firstname_th']}','{$args['lastname_th']}','{$args['title_en']}','{$args['firstname_en']}','{$args['lastname_en']}','{$args['nickname']}','{$args['major_code']}','{$args['major']}','{$args['student_status']}',{$args['confirmed']},{$args['active']},{$args['block']}, '{$client_ip}', '{$args['birthday']}', '{$args['avatar']}')");
                                    }
                                    return DB::selectOne("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE id_student = $studentId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_student' => [
                        'type' => Types::student(),
                        'description' => 'Change Student',
                        'args' => [
                            'id_student' => Types::nonNull(Types::int()),
                            'id_student_card' => Types::nonNull(Types::string()),
                            'id_card' => Types::nonNull(Types::string()),
                            'student_year' => Types::nonNull(Types::int()),
                            'email' => Types::nonNull(Types::email()),
                            'phone' => Types::nonNull(Types::string()),
                            'password' => Types::nonNull(Types::string()),
                            'title_th' => Types::string(),
                            'firstname_th' => Types::string(),
                            'lastname_th' => Types::string(),
                            'title_en' => Types::string(),
                            'firstname_en' => Types::string(),
                            'lastname_en' => Types::string(),
                            'nickname' => Types::string(),
                            'major_code' => Types::string(),
                            'major' => Types::string(),
                            'student_status' => Types::string(),
                            'confirmed' => Types::int(),
                            'active' => Types::int(),
                            'block' => Types::int(),
                            'avatar' => Types::string(),
                            'birthday' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "student", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $value != "" && $column != "id_student") {
                                            if ($column == "password") {
                                                $aesio = new AESIO();
                                                $setCondition[] = "{$column} = '{$aesio->Encrypted($value)}'";
                                            } else {
                                                $setCondition[] = "{$column} = '{$value}'";
                                            }
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE student SET " . implode(', ', $setCondition) . " WHERE id_student = {$args['id_student']}");
                                    $student = DB::selectOne("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE id_student = {$args['id_student']}");
                                    if (is_null($student)) {
                                        throw new \Exception('No student with this id');
                                    }
                                    return $student;
                                }
                            }
                            return;
                        }
                    ],
                    'update_student_password' => [
                        'type' => Types::student(),
                        'description' => 'Change Student',
                        'args' => [
                            'password' => Types::nonNull(Types::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                $setCondition = [];
                                foreach ($args as $column => $value) {
                                    if (isset($value) && $value != "" && $column != "id_student") {
                                        if ($column == "password") {
                                            $aesio = new AESIO();
                                            $setCondition[] = "{$column} = '{$aesio->Encrypted($value)}'";
                                        } else {
                                            $setCondition[] = "{$column} = '{$value}'";
                                        }
                                        //$bindValues[] = $value;
                                    }
                                }

                                DB::update("UPDATE student SET " . implode(', ', $setCondition) . " WHERE id_student = {$root['id_student']}");
                                $student = DB::selectOne("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE id_student = {$root['id_student']}");
                                if (is_null($student)) {
                                    throw new \Exception('No student with this id');
                                }
                                return $student;
                            }
                            return;
                        }
                    ],
                    'update_student_salf' => [
                        'type' => Types::student(),
                        'description' => 'Change Student',
                        'args' => [
                            //'id_student' => Types::nonNull(Types::int()),
                            'id_student_card' => Types::nonNull(Types::string()),
                            //'id_card' => Types::nonNull(Types::string()),
                            //'student_year' => Types::nonNull(Types::int()),
                            'email' => Types::nonNull(Types::email()),
                            'phone' => Types::nonNull(Types::string()),
                            'password' => Types::nonNull(Types::string()),
                            'title_th' => Types::string(),
                            'firstname_th' => Types::string(),
                            'lastname_th' => Types::string(),
                            'title_en' => Types::string(),
                            'firstname_en' => Types::string(),
                            'lastname_en' => Types::string(),
                            'nickname' => Types::string(),
                            'major_code' => Types::string(),
                            'major' => Types::string(),
                            'student_status' => Types::string(),
                            'confirmed' => Types::int(),
                            'active' => Types::int(),
                            'block' => Types::int(),
                            'avatar' => Types::string(),
                            'birthday' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if (!empty($root['id_student'])) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (isset($value) && $value != "" && $column != "id_student") {
                                            if ($column == "password") {
                                                $aesio = new AESIO();
                                                $setCondition[] = "{$column} = '{$aesio->Encrypted($value)}'";
                                            } else {
                                                $setCondition[] = "{$column} = '{$value}'";
                                            }
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE student SET " . implode(', ', $setCondition) . " WHERE id_student = {$args['id_student']}");
                                    $student = DB::selectOne("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE id_student = {$args['id_student']}");
                                    if (is_null($student)) {
                                        throw new \Exception('No student with this id');
                                    }
                                    return $student;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_student' => [
                        'type' => Types::student(),
                        'description' => 'Remove Student',
                        'args' => [
                            'id' => Types::nonNull(Types::int()),
                            'firstname' => Types::string(),
                            'lastname' => Types::string(),
                            'email' => Types::email(),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "student", 2) > 0) {
                                    $before_student = DB::selectOne("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE id = {$args['id']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $student = DB::delete("DELETE FROM student WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $student = DB::delete("DELETE FROM student WHERE id = {$args['id']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$student => " . $student);
                                    return $student ? $before_student : null;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_student_year' => [
                        'type' => Types::listOf(Types::student()),
                        'description' => 'Remove Student',
                        'args' => [
                            'student_year' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "student", 2) > 0) {
                                    $before_student = DB::select("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE student_year = {$args['student_year']}");
                                    $student = DB::delete("DELETE FROM student WHERE student_year = {$args['student_year']}");
                                    return $student ? $before_student : null;
                                }
                            }
                            return;
                        }
                    ],
                    'create_scanner_student' => [
                        'type' => Types::scanner_student(),
                        'description' => 'Adding a scanner_student',
                        'args' => [
                            'id_subject_activity' => Types::nonNull(Types::int()),
                            'id_student_card' => Types::nonNull(Types::string()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "scanner_student", 1) > 0) {
                                    $before_student = DB::selectOne("SELECT * FROM student WHERE id_student_card = '{$args['id_student_card']}'");
                                    //var_dump($before_student);
                                    if (!$before_student)
                                        return array("result" => "No Student");

                                    $before_scanner_student = DB::selectOne("SELECT * FROM scanner_student WHERE id_subject_activity = {$args['id_subject_activity']} AND id_student = {$before_student->id_student}");
                                    //var_dump($before_scanner_student);
                                    if ($before_scanner_student)
                                        return array("result" => "Already");

                                    $scannerStudentId = DB::insert("INSERT INTO scanner_student (id_user, id_subject_activity, id_student) VALUES ('{$root['id_user']}','{$args['id_subject_activity']}',{$before_student->id_student})");

                                    return DB::selectOne("SELECT *, 'Save' AS result FROM scanner_student WHERE id_scanner_student = $scannerStudentId");
                                }
                            }
                            return;
                        }
                    ],
                    'update_scanner_student' => [
                        'type' => Types::scanner_student(),
                        'description' => 'Change scanner_student',
                        'args' => [
                            'id_scanner_student' => Types::nonNull(Types::int()),
                            'id_subject_activity' => Types::nonNull(Types::int()),
                            'id_student' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "scanner_student", 1) > 0) {
                                    $setCondition = [];
                                    foreach ($args as $column => $value) {
                                        if (!empty($value) && $column != "id_scanner_student") {
                                            $setCondition[] = "{$column} = '{$value}'";
                                            //$bindValues[] = $value;
                                        }
                                    }

                                    DB::update("UPDATE scanner_student SET " . implode(', ', $setCondition) . " WHERE id_scanner_student = {$args['id_scanner_student']}");
                                    $scannerStudent = DB::selectOne("SELECT * FROM scanner_student WHERE id_scanner_student = {$args['id_scanner_student']}");
                                    if (is_null($scannerStudent)) {
                                        throw new \Exception('No scanner_student with this id');
                                    }
                                    return $scannerStudent;
                                }
                            }
                            return;
                        }
                    ],
                    'delete_scanner_student' => [
                        'type' => Types::scanner_student(),
                        'description' => 'Remove scanner_student',
                        'args' => [
                            'id_scanner_student' => Types::nonNull(Types::int()),
                        ],
                        'resolve' => function ($root, $args) {
                            if (!empty($root)) {
                                if ($this->checkAuth($root, "scanner_student", 2) > 0) {
                                    $before_scanner_student = DB::selectOne("SELECT * FROM scanner_student WHERE id_scanner_student = {$args['id_scanner_student']}");

                                    if (count($args) > 1) {
                                        $setCondition = [];
                                        foreach ($args as $column => $value) {
                                            if (!empty($value)) {
                                                $setCondition[] = "{$column} = '{$value}'";
                                                //$bindValues[] = $value;
                                            }
                                        }
                                        $scannerStudent = DB::delete("DELETE FROM scanner_student WHERE " . implode('AND ', $setCondition));
                                    } else {
                                        $scannerStudent = DB::delete("DELETE FROM scanner_student WHERE id_scanner_student = {$args['id_scanner_student']}"); // AND email = '{$args['email']}'
                                    }

                                    //print_r("$user => " . $user);
                                    return $scannerStudent ? $before_scanner_student : null;
                                }
                            }
                            return;
                        }
                    ],
                ];
            }
        ];
        parent::__construct($config);
    }
}

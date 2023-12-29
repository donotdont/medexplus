<?php

namespace App\Type;

use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;
use JetBrains\PhpStorm\Language;
use Type;

class QueryType extends ObjectType
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

	public function __construct()
	{
		$config = [
			'fields' => function () {
				return [
					'menu_top' => [
						'type' => Types::listOf(Types::menu_top()),
						'description' => 'Returns the menu top',
						'args' => [],
						'resolve' => function ($root, $args) {
							return DB::select("SELECT * FROM `menu_top` ORDER BY order_index");
						}
					],
					'menu_main' => [
						'type' => Types::listOf(Types::menu_main()),
						'description' => 'Returns the menu main',
						'args' => [],
						'resolve' => function ($root, $args) {
							return DB::select("SELECT * FROM `menu_main` ORDER BY order_index");
						}
					],
					'menu_bar' => [
						'type' => Types::listOf(Types::menu_bar()),
						'description' => 'Returns the menu bar',
						'args' => [],
						'resolve' => function ($root, $args) {
							return DB::select("SELECT * FROM `menu_bar` ORDER BY order_index");
						}
					],
					'visitors' => [
						'type' => Types::count(),
						'description' => 'Returns the visitor',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								return ["count" => DB::affectingStatement("SELECT COUNT(*), ip_address as count FROM `analytic` GROUP BY ip_address")];
							}
							return;
						}
					],
					'reads' => [
						'type' => Types::count(),
						'description' => 'Returns the reads',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								//return DB::selectOne("SELECT COUNT(*) as count FROM analytic WHERE model != 'admin2' AND id_model IS NOT NULL GROUP BY id_model, model");
								return DB::selectOne("SELECT COUNT(*) as count FROM analytic WHERE model_name != 'admin2' GROUP BY id_model, model_name");
							}
							return;
						}
					],
					'active_pages' => [
						'type' => Types::count(),
						'description' => 'Returns the Active Pages',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								return DB::selectOne("SELECT SUM(all_tables.count) as count FROM ( SELECT COUNT(*) as count FROM `post` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `page` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `press_release` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `job_new` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `student_activity` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `study_tour` WHERE active = 1) as all_tables");
								//return DB::selectOne("SELECT ((all_count.count - (all_count.count - active_count.count))*100 / all_count.count) as count FROM (SELECT SUM(all_tables.count) as count FROM (SELECT COUNT(*) as count FROM `post` UNION ALL SELECT COUNT(*) as count FROM `page` UNION ALL SELECT COUNT(*) as count FROM `press_release` UNION ALL SELECT COUNT(*) as count FROM `job_new` UNION ALL SELECT COUNT(*) as count FROM `student_activity` UNION ALL SELECT COUNT(*) as count FROM `study_tour`) all_tables) as all_count, (SELECT SUM(all_active_tables.count) as count FROM ( SELECT COUNT(*) as count FROM `post` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `page` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `press_release` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `job_new` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `student_activity` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `study_tour` WHERE active = 1) as all_active_tables) as active_count");
							}
							return;
						}
					],
					'all_pages' => [
						'type' => Types::count(),
						'description' => 'Returns the Active Pages',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								return DB::selectOne("SELECT SUM(all_tables.count) as count FROM ( SELECT COUNT(*) as count FROM `post` UNION ALL SELECT COUNT(*) as count FROM `page` UNION ALL SELECT COUNT(*) as count FROM `press_release` UNION ALL SELECT COUNT(*) as count FROM `job_new` UNION ALL SELECT COUNT(*) as count FROM `student_activity` UNION ALL SELECT COUNT(*) as count FROM `study_tour`) as all_tables");
								//return DB::selectOne("SELECT ((all_count.count - (all_count.count - active_count.count))*100 / all_count.count) as count FROM (SELECT SUM(all_tables.count) as count FROM (SELECT COUNT(*) as count FROM `post` UNION ALL SELECT COUNT(*) as count FROM `page` UNION ALL SELECT COUNT(*) as count FROM `press_release` UNION ALL SELECT COUNT(*) as count FROM `job_new` UNION ALL SELECT COUNT(*) as count FROM `student_activity` UNION ALL SELECT COUNT(*) as count FROM `study_tour`) all_tables) as all_count, (SELECT SUM(all_active_tables.count) as count FROM ( SELECT COUNT(*) as count FROM `post` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `page` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `press_release` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `job_new` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `student_activity` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `study_tour` WHERE active = 1) as all_active_tables) as active_count");
							}
							return;
						}
					],
					'active_users' => [
						'type' => Types::count(),
						'description' => 'Returns the Active Users',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								return DB::selectOne("SELECT COUNT(*) as count FROM user WHERE active = 1");
							}
							return;
						}
					],
					'visitors_daily' => [
						'type' => Types::listOf(Types::count()),
						'description' => 'Returns the Visitors Daily',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								//return DB::select("SELECT COUNT(*) as count, DATE_FORMAT(created_at, '%d %M %Y') as date FROM `analytic` WHERE model_name != 'admin2' GROUP BY DATE_FORMAT(created_at, '%d-%m-%y') DESC");
								return DB::select("SELECT COUNT(*) as count, DATE_FORMAT(created_at, '%d %M %Y') as date FROM `analytic` WHERE model_name != 'admin2' GROUP BY DATE(created_at) ASC");
							}
							return;
						}
					],
					'visitors_monthly' => [
						'type' => Types::listOf(Types::count()),
						'description' => 'Returns the Visitors Monthly',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								return DB::select("SELECT COUNT(*) as count, DATE_FORMAT(created_at, '%M %Y') as date FROM `analytic` WHERE model_name != 'admin2' GROUP BY DATE_FORMAT(created_at, '%m-%y')");
							}
							return;
						}
					],
					'role_users' => [
						'type' => Types::listOf(Types::count()),
						'description' => 'Returns the Role Users',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								return DB::select("SELECT COUNT(*) as count, r.name_en as role FROM `user` u LEFT JOIN `role` r ON r.id_role = u.id_role GROUP BY u.id_role");
							}
							return;
						}
					],
					'analytics' => [
						'type' => Types::listOf(Types::analytic()),
						'description' => 'Returns the Analytic',
						'args' => [
							'limit' => Types::int(),
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								$limit = "";
								if (isset($args) && !empty($args['limit']))
									$limit = "LIMIT {$args['limit']}";

								return DB::select("SELECT * FROM `analytic` ORDER BY created_at DESC " . $limit);
							}
							return;
						}
					],
					'role' => [
						'type' => Types::role(),
						'description' => 'Returns the role by id',
						'args' => [
							'id_role' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "role", 0) > 0) {
									return DB::selectOne("SELECT * FROM role WHERE id_role = {$args['id_role']}");
								}
							}
							return;
						}
					],
					'roles' => [
						'type' => Types::listOf(Types::role()),
						'description' => 'A list of roles',
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "role", 0) > 0) {
									return DB::select("SELECT * FROM role");
								}
							}
							return;
						}
					],
					'role_filter' => [
						'type' => Types::listOf(Types::role()),
						'description' => 'A list filter of roles',
						'args' => [
							'search' => Types::string()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "role", 0) > 0) {
									if (!empty($args['search'])) {
										return DB::select("SELECT * FROM role WHERE id_role = '{$args['search']}' OR name_th LIKE '%{$args['search']}%' OR name_en LIKE '%{$args['search']}%' ");
									} else {
										return DB::select("SELECT * FROM role");
									}
								}
							}
							return;
						}
					],
					'profile' => [
						'type' => Types::user(),
						'description' => 'Returns the user by id',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root) && !empty($root['id_user'])) {
								return DB::selectOne("SELECT `id_user`, `id_user_ref`, `id_role`, `username`, `email`, `phone`, `firstname`, `lastname`, `nickname`, `confirmed`, `active`, `block`, `ip_address`, `latitude`, `longitude`, `avatar`, `created_at`, `update_at` FROM user WHERE id_user = {$root['id_user']}");
							}
							return;
						}
					],
					'student_profile' => [
						'type' => Types::student(),
						'description' => 'Returns the student by id',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root) && !empty($root['id_student'])) {
								return DB::selectOne("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `id_instructor`, `adviser`, `created_at`, `update_at` FROM student WHERE id_student = {$root['id_student']}");
							}
							return;
						}
					],
					'user' => [
						'type' => Types::user(),
						'description' => 'Returns the user by id',
						'args' => [
							'id_user' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "user", 0) > 0) {
									return DB::selectOne("SELECT `id_user`, `id_user_ref`, `id_role`, `username`, `email`, `phone`, `firstname`, `lastname`, `nickname`, `confirmed`, `active`, `block`, `ip_address`, `latitude`, `longitude`, `avatar`, `created_at`, `update_at` FROM user WHERE id_user = {$args['id_user']}");
								}
							}
							return;
						}
					],
					'users' => [
						'type' => Types::listOf(Types::user()),
						'description' => 'A list of users',
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "user", 0) > 0) {
									return DB::select("SELECT `id_user`, `id_user_ref`, `id_role`, `username`, `email`, `phone`, `firstname`, `lastname`, `nickname`, `confirmed`, `active`, `block`, `ip_address`, `latitude`, `longitude`, `avatar`, `created_at`, `update_at` FROM user");
								}
							}
							return;
						}
					],
					'user_filter' => [
						'type' => Types::listOf(Types::user()),
						'description' => 'A list filter of users',
						'args' => [
							'search' => Types::string()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "user", 0) > 0) {
									if (!empty($args['search'])) {
										return DB::select("SELECT `id_user`, `id_user_ref`, `id_role`, `username`, `email`, `phone`, `firstname`, `lastname`, `nickname`, `confirmed`, `active`, `block`, `ip_address`, `latitude`, `longitude`, `avatar`, `created_at`, `update_at` FROM user WHERE id_user = '{$args['search']}' OR firstname LIKE '%{$args['search']}%' OR lastname LIKE '%{$args['search']}%' ");
									} else {
										return DB::select("SELECT `id_user`, `id_user_ref`, `id_role`, `username`, `email`, `phone`, `firstname`, `lastname`, `nickname`, `confirmed`, `active`, `block`, `ip_address`, `latitude`, `longitude`, `avatar`, `created_at`, `update_at` FROM user");
									}
								}
							}
							return;
						}
					],


					/*'address' => [
						'type' => Types::address(),
						'description' => 'Return the address by id',
						'args' => [
							'id' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							return DB::selectOne("SELECT * FROM addresses WHERE id = {$args['id']}");
						}
					],
					'category' => [
						'type' => Types::category(),
						'description' => 'Returns the category by id_category',
						'args' => [
							'id_category' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							return DB::selectOne("SELECT * FROM category WHERE id_category = {$args['id_category']}");
						}
					],
					'categories' => [
						'type' => Types::listOf(Types::category()),
						'description' => 'A list of categories',
						'resolve' => function ($root, $args) {
							return DB::select("SELECT * FROM category");
						}
					],
					'category_filter' => [
						'type' => Types::listOf(Types::category()),
						'description' => 'A list filter of categories',
						'args' => [
							'search' => Types::string()
						],
						'resolve' => function ($root, $args) {
							if (!empty($args['search'])) {
								return DB::select("SELECT * FROM category WHERE id_category = '{$args['search']}' OR name LIKE '%{$args['search']}%' OR description LIKE '%{$args['search']}%' ");
							} else {
								return DB::select("SELECT * FROM category");
							}
						}
					],
					'product' => [
						'type' => Types::product(),
						'description' => 'Returns the product by id_product',
						'args' => [
							'id_product' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							return DB::selectOne("SELECT * FROM product WHERE id_product = {$args['id_product']}");
						}
					],
					'products' => [
						'type' => Types::listOf(Types::product()),
						'description' => 'A list of products',
						'resolve' => function ($root, $args) {
							return DB::select("SELECT * FROM product");
						}
					],*/

					'searchs' => [
						'type' => Types::listOf(Types::search()),
						'description' => 'Returns the search_filters by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							/*if (!empty($root)) {
								if ($this->checkAuth($root, "press_release", 0) > 0) {
									$data = DB::select("SELECT *, 'Thai' as i18n  FROM press_release WHERE title_th LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;

									$data = DB::select("SELECT *, 'English' as i18n FROM press_release WHERE title_en LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;
								}
							}*/
							return DB::select("(SELECT id_post as id_search, post.*, 'post' as model FROM post WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1) UNION ALL (SELECT id_page as id_search, page.*, 'page' as model FROM page WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1) UNION ALL (SELECT id_press_release as id_search, press_release.*, 'press_release' as model FROM press_release WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1) UNION ALL (SELECT id_job_new as id_search, job_new.*, 'job_new' as model FROM job_new WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1) UNION ALL (SELECT id_study_tour as id_search, study_tour.*, 'study_tour' as model FROM study_tour WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1) UNION ALL (SELECT id_student_activity as id_search, student_activity.*, 'student_activity' as model FROM student_activity WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1)");
						}
					],

					'calendar' => [
						'type' => Types::calendar(),
						'description' => 'Returns the calendar by id_calendar',
						'args' => [
							'id_calendar' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "calendar", 1) > 0) {
									return DB::selectOne("SELECT * FROM calendar WHERE id_calendar = {$args['id_calendar']}");
								} else {
									return;
								}
							}
							return DB::selectOne("SELECT * FROM calendar WHERE id_calendar = {$args['id_calendar']}");
						}
					],

					'calendar_filter' => [
						'type' => Types::calendar(),
						'description' => 'Returns the calendar_filter by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							$data = DB::selectOne("SELECT *, 'Thai' as i18n  FROM calendar WHERE title_th LIKE '%{$args['search']}%'");
							if (!empty($data))
								return $data;

							$data = DB::selectOne("SELECT *, 'English' as i18n FROM calendar WHERE title_en LIKE '%{$args['search']}%'");
							if (!empty($data))
								return $data;

							return DB::selectOne("SELECT * FROM calendar WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%')");
						}
					],
					'calendar_filters' => [
						'type' => Types::listOf(Types::calendar()),
						'description' => 'Returns the calendar_filters by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "calendar", 0) > 0) {
									$data = DB::select("SELECT *, 'Thai' as i18n  FROM calendar WHERE title_th LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;

									$data = DB::select("SELECT *, 'English' as i18n FROM calendar WHERE title_en LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;
								}
							}
							return DB::select("SELECT * FROM calendar WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%')");
						}
					],
					'calendars' => [
						'type' => Types::listOf(Types::calendar()),
						'description' => 'A list of calendars',
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "calendar", 0) > 0) {
									return DB::select("SELECT * FROM calendar");
								} else {
									return;
								}
							}
							//return DB::select("SELECT * FROM calendar ORDER BY created_at DESC");
							return DB::select("SELECT * FROM calendar ORDER BY created_at ASC");
						}
					],

					'instructor' => [
						'type' => Types::instructor(),
						'description' => 'Returns the instructor by id_instructor',
						'args' => [
							'id_instructor' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "instructor", 1) > 0) {
									return DB::selectOne("SELECT * FROM instructor WHERE id_instructor = {$args['id_instructor']}");
								} else {
									return;
								}
							}
							return DB::selectOne("SELECT * FROM instructor WHERE id_instructor = {$args['id_instructor']}");
						}
					],
					'instructor_filter' => [
						'type' => Types::instructor(),
						'description' => 'Returns the instructor_filter by name',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							$data = DB::selectOne("SELECT *, 'Thai' as i18n  FROM instructor WHERE name_th LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							$data = DB::selectOne("SELECT *, 'English' as i18n FROM instructor WHERE name_en LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							return DB::selectOne("SELECT * FROM instructor WHERE (name_th LIKE '%{$args['search']}%' OR name_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'instructor_filters' => [
						'type' => Types::listOf(Types::instructor()),
						'description' => 'Returns the instructor_filters by name',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "instructor", 0) > 0) {
									$data = DB::select("SELECT *, 'Thai' as i18n  FROM instructor WHERE name_th LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;

									$data = DB::select("SELECT *, 'English' as i18n FROM instructor WHERE name_en LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;
								}
							}
							return DB::select("SELECT * FROM instructor WHERE (name_th LIKE '%{$args['search']}%' OR name_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'instructors' => [
						'type' => Types::listOf(Types::instructor()),
						'args' => [
							'major' => Types::string(),
							'sort' => Types::string(),
							'limit' => Types::int()
						],
						'description' => 'A list of instructors',
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "instructor", 0) > 0) {
									return DB::select("SELECT * FROM instructor ORDER BY id_instructor");
								} else {
									return;
								}
							}
							//return DB::select("SELECT * FROM calendar ORDER BY created_at DESC");
							if (!empty($args['major'])) {
								return DB::select("SELECT * FROM instructor WHERE major LIKE '%{$args['major']}%' ORDER BY id_instructor "
									. ((!empty($args['sort']) ? $args['sort'] : "ASC"))
									. ((!empty($args['limit']) ? " LIMIT " . $args['limit'] : "")));
							} else {
								return DB::select("SELECT * FROM instructor WHERE major != '' ORDER BY id_instructor "
									. ((!empty($args['sort']) ? $args['sort'] : "ASC"))
									. ((!empty($args['limit']) ? " LIMIT " . $args['limit'] : "")));
							}
						}
					],



					'post' => [
						'type' => Types::post(),
						'description' => 'Returns the post by id_post',
						'args' => [
							'id_post' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "user", 1) > 0) {
									return DB::selectOne("SELECT * FROM post WHERE id_post = {$args['id_post']}");
								} else {
									return;
								}
							}
							return DB::selectOne("SELECT * FROM post WHERE id_post = {$args['id_post']} AND active = 1");
						}
					],
					'post_filter' => [
						'type' => Types::post(),
						'description' => 'Returns the post_filter by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							$data = DB::selectOne("SELECT *, 'Thai' as i18n  FROM post WHERE title_th LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							$data = DB::selectOne("SELECT *, 'English' as i18n FROM post WHERE title_en LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							return DB::selectOne("SELECT * FROM post WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'post_filters' => [
						'type' => Types::listOf(Types::post()),
						'description' => 'Returns the post_filters by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "post", 0) > 0) {
									$data = DB::select("SELECT *, 'Thai' as i18n  FROM post WHERE title_th LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;

									$data = DB::select("SELECT *, 'English' as i18n FROM post WHERE title_en LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;
								}
							}
							return DB::select("SELECT * FROM post WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'posts' => [
						'type' => Types::listOf(Types::post()),
						'description' => 'A list of posts',
						'args' => [
							'sort' => Types::string(),
							'limit' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "post", 0) > 0) {
									return DB::select("SELECT * FROM post");
								} else {
									return;
								}
							}
							return DB::select("SELECT * FROM post WHERE active = 1 ORDER BY created_at "
								. ((!empty($args['sort']) ? $args['sort'] : "DESC"))
								. ((!empty($args['limit']) ? " LIMIT " . $args['limit'] : "")));
						}
					],

					'page' => [
						'type' => Types::page(),
						'description' => 'Returns the page by id_page',
						'args' => [
							'id_page' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "page", 0) > 0) {
									return DB::selectOne("SELECT * FROM page WHERE id_page = {$args['id_page']}");
								} else {
									return;
								}
							}
							return DB::selectOne("SELECT * FROM page WHERE id_page = {$args['id_page']} AND active = 1");
						}
					],
					'page_filter' => [
						'type' => Types::page(),
						'description' => 'Returns the page_filter by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							$data = DB::selectOne("SELECT *, 'Thai' as i18n  FROM page WHERE title_th LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							$data = DB::selectOne("SELECT *, 'English' as i18n FROM page WHERE title_en LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							return DB::selectOne("SELECT * FROM page WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'page_filters' => [
						'type' => Types::listOf(Types::page()),
						'description' => 'Returns the page_filters by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "page", 0) > 0) {
									$data = DB::select("SELECT *, 'Thai' as i18n  FROM page WHERE title_th LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;

									$data = DB::select("SELECT *, 'English' as i18n FROM page WHERE title_en LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;
								}
							}
							return DB::select("SELECT * FROM page WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'pages' => [
						'type' => Types::listOf(Types::page()),
						'description' => 'A list of pages',
						'args' => [
							'sort' => Types::string(),
							'limit' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "page", 0) > 0) {
									return DB::select("SELECT * FROM page");
								} else {
									return;
								}
							}
							return DB::select("SELECT * FROM page WHERE active = 1 ORDER BY created_at "
								. ((!empty($args['sort']) ? $args['sort'] : "DESC"))
								. ((!empty($args['limit']) ? " LIMIT " . $args['limit'] : "")));
						}
					],

					'press_release' => [
						'type' => Types::press_release(),
						'description' => 'Returns the press_release by id_press_release',
						'args' => [
							'id_press_release' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "press_release", 0) > 0) {
									return DB::selectOne("SELECT * FROM press_release WHERE id_press_release = {$args['id_press_release']}");
								} else {
									return;
								}
							}
							return DB::selectOne("SELECT * FROM press_release WHERE id_press_release = {$args['id_press_release']} AND active = 1");
						}
					],
					'press_release_filter' => [
						'type' => Types::press_release(),
						'description' => 'Returns the press_release_filter by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							$data = DB::selectOne("SELECT *, 'Thai' as i18n  FROM press_release WHERE title_th LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							$data = DB::selectOne("SELECT *, 'English' as i18n FROM press_release WHERE title_en LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							return DB::selectOne("SELECT * FROM press_release WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'press_release_filters' => [
						'type' => Types::listOf(Types::press_release()),
						'description' => 'Returns the press_release_filters by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "press_release", 0) > 0) {
									$data = DB::select("SELECT *, 'Thai' as i18n  FROM press_release WHERE title_th LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;

									$data = DB::select("SELECT *, 'English' as i18n FROM press_release WHERE title_en LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;
								}
							}
							return DB::select("SELECT * FROM press_release WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'press_releases' => [
						'type' => Types::listOf(Types::press_release()),
						'description' => 'A list of press_releases',
						'args' => [
							'sort' => Types::string(),
							'limit' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "press_release", 0) > 0) {
									return DB::select("SELECT * FROM press_release");
								} else {
									return;
								}
							}
							return DB::select("SELECT * FROM press_release WHERE active = 1 ORDER BY created_at "
								. ((!empty($args['sort']) ? $args['sort'] : "DESC"))
								. ((!empty($args['limit']) ? " LIMIT " . $args['limit'] : "")));
						}
					],

					'job_new' => [
						'type' => Types::job_new(),
						'description' => 'Returns the job_new by id_job_new',
						'args' => [
							'id_job_new' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "job_new", 0) > 0) {
									return DB::selectOne("SELECT * FROM job_new WHERE id_job_new = {$args['id_job_new']}");
								} else {
									return;
								}
							}
							return DB::selectOne("SELECT * FROM job_new WHERE id_job_new = {$args['id_job_new']} AND active = 1");
						}
					],
					'job_new_filter' => [
						'type' => Types::job_new(),
						'description' => 'Returns the job_new_filter by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							$data = DB::selectOne("SELECT *, 'Thai' as i18n  FROM job_new WHERE title_th LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							$data = DB::selectOne("SELECT *, 'English' as i18n FROM job_new WHERE title_en LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							return DB::selectOne("SELECT * FROM job_new WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'job_new_filters' => [
						'type' => Types::listOf(Types::job_new()),
						'description' => 'Returns the job_new_filters by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "job_new", 0) > 0) {
									$data = DB::select("SELECT *, 'Thai' as i18n  FROM job_new WHERE title_th LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;

									$data = DB::select("SELECT *, 'English' as i18n FROM job_new WHERE title_en LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;
								}
							}
							return DB::select("SELECT * FROM job_new WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'job_news' => [
						'type' => Types::listOf(Types::job_new()),
						'description' => 'A list of job_news',
						'args' => [
							'sort' => Types::string(),
							'limit' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "job_new", 0) > 0) {
									return DB::select("SELECT * FROM job_new");
								} else {
									return;
								}
							}
							return DB::select("SELECT * FROM job_new WHERE active = 1 ORDER BY created_at "
								. ((!empty($args['sort']) ? $args['sort'] : "DESC"))
								. ((!empty($args['limit']) ? " LIMIT " . $args['limit'] : "")));
						}
					],

					'study_tour' => [
						'type' => Types::study_tour(),
						'description' => 'Returns the study_tour by id_study_tour',
						'args' => [
							'id_study_tour' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "study_tour", 0) > 0) {
									return DB::selectOne("SELECT * FROM study_tour WHERE id_study_tour = {$args['id_study_tour']}");
								} else {
									return;
								}
							}
							return DB::selectOne("SELECT * FROM study_tour WHERE id_study_tour = {$args['id_study_tour']} AND active = 1");
						}
					],
					'study_tour_filter' => [
						'type' => Types::study_tour(),
						'description' => 'Returns the study_tour_filter by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							$data = DB::selectOne("SELECT *, 'Thai' as i18n  FROM study_tour WHERE title_th LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							$data = DB::selectOne("SELECT *, 'English' as i18n FROM study_tour WHERE title_en LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							return DB::selectOne("SELECT * FROM study_tour WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'study_tour_filters' => [
						'type' => Types::listOf(Types::study_tour()),
						'description' => 'Returns the study_tour_filters by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "study_tour", 0) > 0) {
									$data = DB::select("SELECT *, 'Thai' as i18n  FROM study_tour WHERE title_th LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;

									$data = DB::select("SELECT *, 'English' as i18n FROM study_tour WHERE title_en LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;
								}
							}
							return DB::select("SELECT * FROM study_tour WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'study_tours' => [
						'type' => Types::listOf(Types::study_tour()),
						'description' => 'A list of study_tours',
						'args' => [
							'sort' => Types::string(),
							'limit' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "study_tour", 0) > 0) {
									return DB::select("SELECT * FROM study_tour");
								} else {
									return;
								}
							}
							return DB::select("SELECT * FROM study_tour WHERE active = 1 ORDER BY created_at "
								. ((!empty($args['sort']) ? $args['sort'] : "DESC"))
								. ((!empty($args['limit']) ? " LIMIT " . $args['limit'] : "")));
						}
					],

					'student_activity' => [
						'type' => Types::student_activity(),
						'description' => 'Returns the student_activity by id_student_activity',
						'args' => [
							'id_student_activity' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "student_activity", 0) > 0) {
									return DB::selectOne("SELECT * FROM student_activity WHERE id_student_activity = {$args['id_student_activity']}");
								} else {
									return;
								}
							}
							return DB::selectOne("SELECT * FROM student_activity WHERE id_student_activity = {$args['id_student_activity']} AND active = 1");
						}
					],
					'student_activity_filter' => [
						'type' => Types::student_activity(),
						'description' => 'Returns the student_activity_filter by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							$data = DB::selectOne("SELECT *, 'Thai' as i18n  FROM student_activity WHERE title_th LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							$data = DB::selectOne("SELECT *, 'English' as i18n FROM student_activity WHERE title_en LIKE '%{$args['search']}%' AND active = 1");
							if (!empty($data))
								return $data;

							return DB::selectOne("SELECT * FROM student_activity WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'student_activity_filters' => [
						'type' => Types::listOf(Types::student_activity()),
						'description' => 'Returns the student_activity_filters by title',
						'args' => [
							'search' => Types::nonNull(Types::string())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "student_activity", 0) > 0) {
									$data = DB::select("SELECT *, 'Thai' as i18n  FROM student_activity WHERE title_th LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;

									$data = DB::select("SELECT *, 'English' as i18n FROM student_activity WHERE title_en LIKE '%{$args['search']}%'");
									if (!empty($data))
										return $data;
								}
							}
							return DB::select("SELECT * FROM student_activity WHERE (title_th LIKE '%{$args['search']}%' OR title_en LIKE '%{$args['search']}%') AND active = 1");
						}
					],
					'student_activities' => [
						'type' => Types::listOf(Types::student_activity()),
						'description' => 'A list of student_activities',
						'args' => [
							'sort' => Types::string(),
							'limit' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "student_activity", 0) > 0) {
									return DB::select("SELECT * FROM student_activity");
								} else {
									return;
								}
							}
							return DB::select("SELECT * FROM student_activity WHERE active = 1 ORDER BY created_at "
								. ((!empty($args['sort']) ? $args['sort'] : "DESC"))
								. ((!empty($args['limit']) ? " LIMIT " . $args['limit'] : "")));
						}
					],

					'category_activity' => [
						'type' => Types::category_activity(),
						'description' => 'Returns the category_activity by id',
						'args' => [
							'id_category_activity' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "category_activity", 0) > 0) {
									return DB::selectOne("SELECT * FROM category_activity WHERE id_category_activity = {$args['id_category_activity']}");
								}
							}
							return;
						}
					],
					'category_activities' => [
						'type' => Types::listOf(Types::category_activity()),
						'description' => 'A list of category_activities',
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "category_activity", 0) > 0) {
									return DB::select("SELECT * FROM category_activity");
								}
							}
							return;
						}
					],
					'category_activity_filter' => [
						'type' => Types::listOf(Types::category_activity()),
						'description' => 'A list filter of category_activities',
						'args' => [
							'search' => Types::string()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "category_activity", 0) > 0) {
									if (!empty($args['search'])) {
										return DB::select("SELECT * FROM category_activity WHERE id_category_activity = '{$args['search']}' OR name_th LIKE '%{$args['search']}%' OR name_en LIKE '%{$args['search']}%' ");
									} else {
										return DB::select("SELECT * FROM category_activity");
									}
								}
							}
							return;
						}
					],

					'subject_activity' => [
						'type' => Types::subject_activity(),
						'description' => 'Returns the subject_activity by id',
						'args' => [
							'id_subject_activity' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "subject_activity", 0) > 0) {
									return DB::selectOne("SELECT * FROM subject_activity WHERE id_subject_activity = {$args['id_subject_activity']}");
								}
							}
							return;
						}
					],
					'subject_activities' => [
						'type' => Types::listOf(Types::subject_activity()),
						'description' => 'A list of subject_activities',
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "subject_activity", 0) > 0) {
									return DB::select("SELECT * FROM subject_activity");
								}
							}
							return;
						}
					],
					'subject_activity_filter' => [
						'type' => Types::listOf(Types::subject_activity()),
						'description' => 'A list filter of subject_activities',
						'args' => [
							'search' => Types::string()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "subject_activity", 0) > 0) {
									if (!empty($args['search'])) {
										return DB::select("SELECT * FROM subject_activity WHERE id_subject_activity = '{$args['search']}' OR name_th LIKE '%{$args['search']}%' OR name_en LIKE '%{$args['search']}%' ");
									} else {
										return DB::select("SELECT * FROM subject_activity");
									}
								}
							}
							return;
						}
					],
					'online_tracking' => [
						'type' => Types::online_tracking(),
						'description' => 'Returns the online tracking by id',
						'args' => [
							'id_online_tracking' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "online_tracking", 0) > 0) {
									return DB::selectOne("SELECT `id_online_tracking`, `id_online_tracking_status`, `id_online_tracking_where`, `id_student`, `id_student_card`, `request_to`, `date_start`, `due_date`, `duration`, `note`, `ip`, `created_at`, `updated_at` FROM online_tracking WHERE id_online_tracking = {$args['id_online_tracking']}");
								}
							}
							return;
						}
					],
					'online_trackings' => [
						'type' => Types::listOf(Types::online_tracking()),
						'description' => 'A list of online_trackings',
						'args' => [
							//'student_year' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "online_tracking", 0) > 0) {
									return DB::select("SELECT `id_online_tracking`, `id_online_tracking_status`, `id_online_tracking_where`, `id_student`, `id_student_card`, `request_to`, `date_start`, `due_date`, `duration`, `note`, `ip`, `created_at`, `updated_at` FROM online_tracking");
								}
							}
							return;
						}
					],
					'online_tracking_filter' => [
						'type' => Types::listOf(Types::online_tracking()),
						'description' => 'A list filter of online_trackings',
						'args' => [
							'search' => Types::string()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "online_tracking", 0) > 0) {
									if (!empty($args['search'])) {
										return DB::select("SELECT * FROM online_tracking WHERE id_online_tracking = '{$args['search']}' OR id_student_card LIKE '%{$args['search']}%' OR request_to LIKE '%{$args['search']}%' ");
									} else {
										return DB::select("SELECT * FROM online_tracking");
									}
								}
							}
							return;
						}
					],
					'student' => [
						'type' => Types::student(),
						'description' => 'Returns the student by id',
						'args' => [
							'id_student' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "student", 0) > 0) {
									return DB::selectOne("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE id_student = {$args['id_student']}");
								}
							}
							return;
						}
					],
					'students' => [
						'type' => Types::listOf(Types::student()),
						'description' => 'A list of students',
						'args' => [
							'student_year' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "student", 0) > 0) {
									if (!empty($args['student_year'])) {
										return DB::select("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE student_year = {$args['student_year']}");
									} else {
										return DB::select("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student");
									}
								}
							}
							return;
						}
					],
					'student_filter' => [
						'type' => Types::listOf(Types::student()),
						'description' => 'A list filter of students',
						'args' => [
							'search' => Types::string()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "student", 0) > 0) {
									if (!empty($args['search'])) {
										//echo "SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE id_student_card = {$args['search']} OR firstname_th LIKE '%{$args['search']}%' OR lastname_th LIKE '%{$args['search']}%' OR firstname_en LIKE '%{$args['search']}%' OR lastname_en LIKE '%{$args['search']}%' ";
										/*return DB::select("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE id_student_card = CONVERT('{$args['search']}',UNSIGNED INTEGER) OR firstname_th LIKE '%{$args['search']}%' OR lastname_th LIKE '%{$args['search']}%' OR firstname_en LIKE '%{$args['search']}%' OR lastname_en LIKE '%{$args['search']}%' ");*/
										return DB::select("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student WHERE id_student_card LIKE '%{$args['search']}%' OR firstname_th LIKE '%{$args['search']}%' OR lastname_th LIKE '%{$args['search']}%' OR firstname_en LIKE '%{$args['search']}%' OR lastname_en LIKE '%{$args['search']}%' ");
									} else {
										return DB::select("SELECT `id_student`, `id_user`, `id_student_card`, `id_card`, `student_year`, `email`, `phone`, `title_th`, `firstname_th`, `lastname_th`, `title_en`, `firstname_en`, `lastname_en`, `nickname`, `major_code`, `major`, `student_status`, `confirmed`, `active`, `block`, `ip_address`, `avatar`, `birthday`, `created_at`, `update_at` FROM student");
									}
								}
							}
							return;
						}
					],
					'scanner_student' => [
						'type' => Types::scanner_student(),
						'description' => 'Returns the scanner_student by id',
						'args' => [
							'id_scanner_student' => Types::nonNull(Types::int())
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "scanner_student", 0) > 0) {
									return DB::selectOne("SELECT * FROM scanner_student WHERE id_scanner_student = {$args['id_scanner_student']}");
								}
							}
							return;
						}
					],
					'scanner_students' => [
						'type' => Types::listOf(Types::scanner_student()),
						'description' => 'A list of scanner_students',
						'args' => [
							'id_subject_activity' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "scanner_student", 0) > 0) {
									if (!empty($args['id_subject_activity'])) {
										return DB::select("SELECT * FROM scanner_student WHERE id_subject_activity = {$args['id_subject_activity']}");
									} else {
										return DB::select("SELECT * FROM scanner_student");
									}
								}
							}
							return;
						}
					],
					'scanner_student_filter' => [
						'type' => Types::listOf(Types::scanner_student()),
						'description' => 'A list filter of scanner_students',
						'args' => [
							'search' => Types::string(),
							'id_subject_activity' => Types::int()
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if ($this->checkAuth($root, "scanner_student", 0) > 0) {
									if (!empty($args['search'])) {
										//return DB::select("SELECT ss.* FROM scanner_student ss LEFT JOIN student s ON s.id_student = ss.id_student  WHERE s.id_student_card = CONVERT('{$args['search']}',UNSIGNED INTEGER) OR s.firstname_th LIKE '%{$args['search']}%' OR s.lastname_th LIKE '%{$args['search']}%' OR s.firstname_en LIKE '%{$args['search']}%' OR s.lastname_en LIKE '%{$args['search']}%' ");
										return DB::select("SELECT ss.* FROM scanner_student ss LEFT JOIN student s ON s.id_student = ss.id_student  WHERE s.id_student_card LIKE '%{$args['search']}%' OR s.firstname_th LIKE '%{$args['search']}%' OR s.lastname_th LIKE '%{$args['search']}%' OR s.firstname_en LIKE '%{$args['search']}%' OR s.lastname_en LIKE '%{$args['search']}%' ");
									} else {
										return DB::select("SELECT * FROM scanner_student");
									}
								}
							}
							return;
						}
					],
					'scanner_student_activity' => [
						'type' => Types::listOf(Types::scanner_student()),
						'description' => 'A list filter of scanner_students',
						'args' => [
							//'search' => Types::string(),
						],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								//if ($this->checkAuth($root, "scanner_student", 0) > 0) {
								if (!empty($root['id_student'])) {
									return DB::select("SELECT ss.* FROM scanner_student ss LEFT JOIN student s ON s.id_student = ss.id_student  WHERE s.id_student_card = '{$root['id_student_card']}' ");
								}
								//}
							}
							return;
						}
					],
					'scanner_student_events' => [
						'type' => Types::count(),
						'description' => 'Scanner Student join to event',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								//return DB::selectOne("SELECT SUM(all_tables.count) as count FROM ( SELECT COUNT(*) as count FROM `post` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `page` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `press_release` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `job_new` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `student_activity` WHERE active = 1 UNION ALL SELECT COUNT(*) as count FROM `study_tour` WHERE active = 1) as all_tables");
								if (!empty($root['id_student'])) {
									return DB::selectOne("SELECT COUNT(*) as count FROM scanner_student ss LEFT JOIN student s ON s.id_student = ss.id_student  WHERE s.id_student_card = '{$root['id_student_card']}' ");
								}
							}
							return;
						}
					],
					'scanner_student_credits' => [
						'type' => Types::count(),
						'description' => 'Scanner Student count credits',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if (!empty($root['id_student'])) {
									return DB::selectOne("SELECT SUM(sa.credit) as count FROM scanner_student ss LEFT JOIN student s ON s.id_student=ss.id_student LEFT JOIN subject_activity sa ON sa.id_subject_activity = ss.id_subject_activity WHERE s.id_student_card = '{$root['id_student_card']}' ");
								}
							}
							return;
						}
					],
					'scanner_student_hours' => [
						'type' => Types::count(),
						'description' => 'Scanner Student count Hours',
						'args' => [],
						'resolve' => function ($root, $args) {
							if (!empty($root)) {
								if (!empty($root['id_student'])) {
									return DB::selectOne("SELECT SUM(sa.hours) as count FROM scanner_student ss LEFT JOIN student s ON s.id_student=ss.id_student LEFT JOIN subject_activity sa ON sa.id_subject_activity = ss.id_subject_activity WHERE s.id_student_card = '{$root['id_student_card']}' ");
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

<?php

namespace App;

use App\Type\QueryType;
use App\Type\MutationType;
use App\Type\UserType;
use App\Type\RoleType;
use App\Type\PermissionType;

use App\Type\CategoryType;
use App\Type\ProductType;
use App\Type\CustomerType;
use App\Type\QuotationType;


use App\Type\AttributeType;
use App\Type\AddressType;

use App\Type\MenuTopType;
use App\Type\MenuMainType;
use App\Type\MenuBarType;
use App\Type\AnalyticType;
use App\Type\CountType;
use App\Type\SearchType;
use App\Type\ModelType;
use App\Type\PostType;
use App\Type\PageType;
use App\Type\PressReleaseType;
use App\Type\JobNewType;
use App\Type\StudyTourType;
use App\Type\StudentActivityType;
use App\Type\CalendarType;
use App\Type\InstructorType;
use App\Type\CategoryActivityType;
use App\Type\SubjectActivityType;
use App\Type\StudentType;
use App\Type\ScannerStudentType;
use App\Type\OnlineTrackingType;

use App\Type\Scalar\EmailType;
use App\Type\Scalar\JsonType;
use App\Type\Scalar\DateTimeType;
use GraphQL\Type\Definition\Type;

class Types
{
    private static $query;
    private static $mutation;
    private static $user;
    private static $category;
    private static $product;
    private static $attribute;
    private static $address;
    private static $emailType;
    private static $jsonType;
    private static $datetimeType;
    private static $role;
    private static $permission;
    private static $customer;
    private static $quotation;

    private static $menu_top;
    private static $menu_main;
    private static $menu_bar;
    private static $analytic;
    private static $count;
    private static $search;
    private static $model;
    private static $post;
    private static $page;
    private static $press_release;
    private static $job_new;
    private static $study_tour;
    private static $student_activity;
    private static $calendar;
    private static $instructor;
    private static $category_activity;
    private static $subject_activity;
    private static $student;
    private static $scanner_student;
    private static $online_tracking;

    public static function query()
    {
        return self::$query ?: (self::$query = new QueryType());
    }

    public static function mutation()
    {
        return self::$mutation ?: (self::$query = new MutationType());
    }

    public static function user()
    {
        return self::$user ?: (self::$user = new UserType());
    }

    public static function role()
    {
        return self::$role ?: (self::$role = new RoleType());
    }

    public static function permission()
    {
        return self::$permission ?: (self::$permission = new PermissionType());
    }

    public static function menu_top()
    {
        return self::$menu_top ?: (self::$menu_top = new MenuTopType());
    }

    public static function menu_main()
    {
        return self::$menu_main ?: (self::$menu_main = new MenuMainType());
    }

    public static function menu_bar()
    {
        return self::$menu_bar ?: (self::$menu_bar = new MenuBarType());
    }

    public static function analytic()
    {
        return self::$analytic ?: (self::$analytic = new AnalyticType());
    }

    public static function count()
    {
        return self::$count ?: (self::$count = new CountType());
    }

    public static function search()
    {
        return self::$search ?: (self::$search = new SearchType());
    }

    public static function model()
    {
        return self::$model ?: (self::$model = new ModelType());
    }

    public static function post()
    {
        return self::$post ?: (self::$post = new PostType());
    }

    public static function page()
    {
        return self::$page ?: (self::$page = new PageType());
    }

    public static function press_release()
    {
        return self::$press_release ?: (self::$press_release = new PressReleaseType());
    }

    public static function job_new()
    {
        return self::$job_new ?: (self::$job_new = new JobNewType());
    }

    public static function study_tour()
    {
        return self::$study_tour ?: (self::$study_tour = new StudyTourType());
    }

    public static function student_activity()
    {
        return self::$student_activity ?: (self::$student_activity = new StudentActivityType());
    }
    
    public static function calendar()
    {
        return self::$calendar ?: (self::$calendar = new CalendarType());
    }
    public static function instructor()
    {
        return self::$instructor ?: (self::$instructor = new InstructorType());
    }

    public static function category()
    {
        return self::$category ?: (self::$category = new CategoryType());
    }

    public static function product()
    {
        return self::$product ?: (self::$product = new ProductType());
    }

    public static function attribute()
    {
        return self::$attribute ?: (self::$attribute = new AttributeType());
    }

    public static function address()
    {
        return self::$address ?: (self::$address = new AddressType());
    }
    
    public static function category_activity()
    {
        return self::$category_activity ?: (self::$category_activity = new CategoryActivityType());
    }
    
    public static function subject_activity()
    {
        return self::$subject_activity ?: (self::$subject_activity = new SubjectActivityType());
    }
        
    public static function student()
    {
        return self::$student ?: (self::$student = new StudentType());
    }
    
    public static function scanner_student()
    {
        return self::$scanner_student ?: (self::$scanner_student = new ScannerStudentType());
    }
    
    public static function online_tracking()
    {
        return self::$online_tracking ?: (self::$online_tracking = new OnlineTrackingType());
    }
    
    public static function customer()
    {
        return self::$customer ?: (self::$customer = new CustomerType());
    }
    
    public static function quotation()
    {
        return self::$quotation ?: (self::$quotation = new QuotationType());
    }

    public static function id()
    {
        return Type::id();
    }

    public static function int()
    {
        return Type::int();
    }

    public static function string()
    {
        return Type::string();
    }

    public static function float()
    {
        return Type::float();
    }

    public static function listOf($type)
    {
        return Type::listOf($type);
    }

    public static function nonNull($type)
    {
        return Type::nonNull($type);
    }

    public static function email()
    {
        return self::$emailType ?: (self::$emailType = new EmailType());
    }

    public static function json()
    {
        return self::$jsonType ?: (self::$jsonType = new JsonType());
    }

    public static function datetime()
    {
        return self::$datetimeType ?: (self::$datetimeType = new DateTimeType());
    }
}

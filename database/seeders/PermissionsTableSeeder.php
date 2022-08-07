<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'tiket_create',
            ],
            [
                'id'    => 18,
                'title' => 'tiket_edit',
            ],
            [
                'id'    => 19,
                'title' => 'tiket_show',
            ],
            [
                'id'    => 20,
                'title' => 'tiket_delete',
            ],
            [
                'id'    => 21,
                'title' => 'tiket_access',
            ],
            [
                'id'    => 22,
                'title' => 'event_create',
            ],
            [
                'id'    => 23,
                'title' => 'event_edit',
            ],
            [
                'id'    => 24,
                'title' => 'event_show',
            ],
            [
                'id'    => 25,
                'title' => 'event_delete',
            ],
            [
                'id'    => 26,
                'title' => 'event_access',
            ],
            [
                'id'    => 27,
                'title' => 'banner_create',
            ],
            [
                'id'    => 28,
                'title' => 'banner_edit',
            ],
            [
                'id'    => 29,
                'title' => 'banner_show',
            ],
            [
                'id'    => 30,
                'title' => 'banner_delete',
            ],
            [
                'id'    => 31,
                'title' => 'banner_access',
            ],
            [
                'id'    => 32,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 33,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 34,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => 35,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => 36,
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => 37,
                'title' => 'user_alert_access',
            ],
            [
                'id'    => 38,
                'title' => 'faq_management_access',
            ],
            [
                'id'    => 39,
                'title' => 'faq_category_create',
            ],
            [
                'id'    => 40,
                'title' => 'faq_category_edit',
            ],
            [
                'id'    => 41,
                'title' => 'faq_category_show',
            ],
            [
                'id'    => 42,
                'title' => 'faq_category_delete',
            ],
            [
                'id'    => 43,
                'title' => 'faq_category_access',
            ],
            [
                'id'    => 44,
                'title' => 'faq_question_create',
            ],
            [
                'id'    => 45,
                'title' => 'faq_question_edit',
            ],
            [
                'id'    => 46,
                'title' => 'faq_question_show',
            ],
            [
                'id'    => 47,
                'title' => 'faq_question_delete',
            ],
            [
                'id'    => 48,
                'title' => 'faq_question_access',
            ],
            [
                'id'    => 49,
                'title' => 'transaksi_create',
            ],
            [
                'id'    => 50,
                'title' => 'transaksi_edit',
            ],
            [
                'id'    => 51,
                'title' => 'transaksi_show',
            ],
            [
                'id'    => 52,
                'title' => 'transaksi_delete',
            ],
            [
                'id'    => 53,
                'title' => 'transaksi_access',
            ],
            [
                'id'    => 54,
                'title' => 'sponsor_create',
            ],
            [
                'id'    => 55,
                'title' => 'sponsor_edit',
            ],
            [
                'id'    => 56,
                'title' => 'sponsor_show',
            ],
            [
                'id'    => 57,
                'title' => 'sponsor_delete',
            ],
            [
                'id'    => 58,
                'title' => 'sponsor_access',
            ],
            [
                'id'    => 59,
                'title' => 'setting_create',
            ],
            [
                'id'    => 60,
                'title' => 'setting_edit',
            ],
            [
                'id'    => 61,
                'title' => 'setting_show',
            ],
            [
                'id'    => 62,
                'title' => 'setting_delete',
            ],
            [
                'id'    => 63,
                'title' => 'setting_access',
            ],
            [
                'id'    => 64,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}

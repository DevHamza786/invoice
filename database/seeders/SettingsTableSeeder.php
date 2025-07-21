<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        DB::table('settings')->insert([
            ['id' => 1, 'key' => 'mail_notification', 'value' => '1', 'created_at' => '2023-07-10 13:29:13', 'updated_at' => '2023-07-10 13:29:13'],
            ['id' => 2, 'key' => 'currency_after_amount', 'value' => '1', 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2023-07-10 13:29:14'],
            ['id' => 3, 'key' => 'payment_auto_approved', 'value' => '1', 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2023-07-10 13:29:14'],
            ['id' => 4, 'key' => 'invoice_no_prefix', 'value' => null, 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2023-07-10 13:29:14'],
            ['id' => 5, 'key' => 'invoice_no_suffix', 'value' => null, 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2023-07-10 13:29:14'],
            ['id' => 6, 'key' => 'country_code', 'value' => '+61', 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 7, 'key' => 'show_product_description', 'value' => '1', 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2025-06-04 12:13:17'],
            ['id' => 8, 'key' => 'city', 'value' => null, 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 9, 'key' => 'state', 'value' => 'Melbourne', 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2025-06-04 17:34:13'],
            ['id' => 10, 'key' => 'country', 'value' => 'Australia', 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2025-06-04 17:34:13'],
            ['id' => 11, 'key' => 'zipcode', 'value' => null, 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 12, 'key' => 'fax_no', 'value' => null, 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 13, 'key' => 'show_additional_address_in_invoice', 'value' => '0', 'created_at' => '2023-07-10 13:29:14', 'updated_at' => '2025-07-10 09:29:14'],
            ['id' => 14, 'key' => 'default_invoice_template', 'value' => 'defaultTemplate', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 15, 'key' => 'default_invoice_color', 'value' => '#040404', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 16, 'key' => 'app_name', 'value' => 'Top Side', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2025-07-10 09:29:14'],
            ['id' => 17, 'key' => 'app_logo', 'value' => 'https://demo.preciousconsultant.com//uploads/13/jpeg-01.jpg', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 18, 'key' => 'company_name', 'value' => 'Top Side', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 20, 'key' => 'date_format', 'value' => 'd-m-Y', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2025-06-23 18:21:06'],
            ['id' => 21, 'key' => 'time_format', 'value' => '0', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 22, 'key' => 'time_zone', 'value' => 'Australia/Darwin', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 23, 'key' => 'current_currency', 'value' => '2', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2025-06-04 12:11:18'],
            ['id' => 24, 'key' => 'decimal_separator', 'value' => '.', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 25, 'key' => 'thousand_separator', 'value' => ',', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 26, 'key' => 'company_address', 'value' => 'Rockingham, Mandurah, Fremantle, Bunbury, Byford, Perth', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 27, 'key' => 'company_phone', 'value' => '+61400460331', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 28, 'key' => 'favicon_icon', 'value' => 'https://demo.preciousconsultant.com//uploads/14/png.png', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2025-07-09 11:56:34'],
            ['id' => 29, 'key' => 'stripe_key', 'value' => '', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 30, 'key' => 'stripe_secret', 'value' => '', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 31, 'key' => 'paypal_client_id', 'value' => '', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 32, 'key' => 'paypal_secret', 'value' => '', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 33, 'key' => 'razorpay_key', 'value' => '', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 34, 'key' => 'razorpay_secret', 'value' => '', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 35, 'key' => 'stripe_enabled', 'value' => '0', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 36, 'key' => 'paypal_enabled', 'value' => '0', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 37, 'key' => 'razorpay_enabled', 'value' => '0', 'created_at' => '2023-07-10 13:29:16', 'updated_at' => '2023-07-10 13:29:16'],
            ['id' => 38, 'key' => 'due_invoice_days', 'value' => null, 'created_at' => '2025-06-04 12:11:18', 'updated_at' => '2025-06-04 12:11:18'],
            ['id' => 39, 'key' => 'abn', 'value' => '000000', 'created_at' => '2025-07-07 14:54:45', 'updated_at' => '2025-07-10 09:29:14'],
        ]);
    }
}

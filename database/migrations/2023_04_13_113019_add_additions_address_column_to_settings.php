<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $settings = [
            ['key' => 'city', 'value' => 'Surat'],
            ['key' => 'state', 'value' => 'Gujarat'],
            ['key' => 'country', 'value' => 'India'],
            ['key' => 'zipcode', 'value' => '394101'],
            ['key' => 'fax_no', 'value' => '555-123-4567'],
            ['key' => 'show_additional_address_in_invoice', 'value' => 0],
            ['key' => 'default_invoice_template', 'value' => 'defaultTemplate'],
            ['key' => 'default_invoice_color', 'value' => '#040404'],
            ['key' => 'app_name', 'value' => 'Top Side'],
            ['key' => 'app_logo', 'value' => 'https://demo.preciousconsultant.com//uploads/13/jpeg-01.jpg'],
            ['key' => 'company_name', 'value' => 'Top Side'],
            ['key' => 'date_format', 'value' => 'd-m-Y'],
            ['key' => 'time_format', 'value' => '0'],
            ['key' => 'time_zone', 'value' => 'Australia/Darwin'],
            ['key' => 'current_currency', 'value' => '2'],
            ['key' => 'decimal_separator', 'value' => '.'],
            ['key' => 'thousand_separator', 'value' => ','],
            ['key' => 'company_address', 'value' => 'Rockingham, Mandurah, Fremantle, Bunbury, Byford, Perth'],
            ['key' => 'company_phone', 'value' => '+61400460331'],
            ['key' => 'favicon_icon', 'value' => 'https://demo.preciousconsultant.com//uploads/14/png.png'],
            ['key' => 'stripe_key', 'value' => ''],
            ['key' => 'stripe_secret', 'value' => ''],
            ['key' => 'paypal_client_id', 'value' => ''],
            ['key' => 'paypal_secret', 'value' => ''],
            ['key' => 'razorpay_key', 'value' => ''],
            ['key' => 'razorpay_secret', 'value' => ''],
            ['key' => 'stripe_enabled', 'value' => '0'],
            ['key' => 'paypal_enabled', 'value' => '0'],
            ['key' => 'razorpay_enabled', 'value' => '0'],
            ['key' => 'due_invoice_days', 'value' => null],
            ['key' => 'abn', 'value' => '000000'],
        ];
        foreach($settings as $setting){
             Setting::create($setting);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $settings = [
            ['key' => 'city', 'value' => 'Surat'],
            ['key' => 'state', 'value' => 'Gujarat'],
            ['key' => 'country', 'value' => 'India'],
            ['key' => 'zipcode', 'value' => '394101'],
            ['key' => 'fax_no', 'value' => '555-123-4567'],
            ['key' => 'show_additional_address_in_invoice', 'value' => 0],
            ['key' => 'default_invoice_template', 'value' => 'defaultTemplate'],
            ['key' => 'default_invoice_color', 'value' => '#040404'],
            ['key' => 'app_name', 'value' => 'Top Side'],
            ['key' => 'app_logo', 'value' => 'https://demo.preciousconsultant.com//uploads/13/jpeg-01.jpg'],
            ['key' => 'company_name', 'value' => 'Top Side'],
            ['key' => 'date_format', 'value' => 'd-m-Y'],
            ['key' => 'time_format', 'value' => '0'],
            ['key' => 'time_zone', 'value' => 'Australia/Darwin'],
            ['key' => 'current_currency', 'value' => '2'],
            ['key' => 'decimal_separator', 'value' => '.'],
            ['key' => 'thousand_separator', 'value' => ','],
            ['key' => 'company_address', 'value' => 'Rockingham, Mandurah, Fremantle, Bunbury, Byford, Perth'],
            ['key' => 'company_phone', 'value' => '+61400460331'],
            ['key' => 'favicon_icon', 'value' => 'https://demo.preciousconsultant.com//uploads/14/png.png'],
            ['key' => 'stripe_key', 'value' => ''],
            ['key' => 'stripe_secret', 'value' => ''],
            ['key' => 'paypal_client_id', 'value' => ''],
            ['key' => 'paypal_secret', 'value' => ''],
            ['key' => 'razorpay_key', 'value' => ''],
            ['key' => 'razorpay_secret', 'value' => ''],
            ['key' => 'stripe_enabled', 'value' => '0'],
            ['key' => 'paypal_enabled', 'value' => '0'],
            ['key' => 'razorpay_enabled', 'value' => '0'],
            ['key' => 'due_invoice_days', 'value' => null],
            ['key' => 'abn', 'value' => '000000'],
        ];
        foreach($settings as $setting){
            Setting::where('key', $setting['key'])->delete();
        }
    }
};

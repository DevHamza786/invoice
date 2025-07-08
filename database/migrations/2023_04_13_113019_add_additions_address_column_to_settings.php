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
            ['key' => 'city'],
            ['key' => 'state'],
            ['key' => 'country'],
            ['key' => 'zipcode'],
            ['key' => 'fax_no'],
            ['key' => 'show_additional_address_in_invoice'],
        ];
        foreach($settings as $setting){
            Setting::where('key', $setting['key'])->delete();
        }
    }
};

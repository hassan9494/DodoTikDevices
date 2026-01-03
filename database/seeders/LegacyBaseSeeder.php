<?php

namespace Database\Seeders;

use App\Models\General;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LegacyBaseSeeder extends Seeder
{
    public function run(): void
    {
        General::unguarded(function () {
            General::updateOrCreate(
                ['id' => 1],
                [
                    'title' => 'PT. Purdimen Vanjava',
                    'favicon' => 'images/general/chzeM0So0WcBQxsk4lTeDC9w3XVqOZXgsojLST0X.png',
                    'logo' => 'images/general/bqoJhyL8BVNsFCZWcW6md31EngtwGwaepTADuVQ8.png',
                    'address1' => 'Sambirejo, Bangorejo',
                    'address2' => 'Kab. Banyuwangi, Jawa Timur',
                    'phone' => '0821xxxxxx',
                    'email' => 'halo@example.com',
                    'twitter' => 'https://twitter.com/bisaboscom',
                    'facebook' => 'https://facebook.com/bisaboscom',
                    'instagram' => 'https://instagram.com/bisabos',
                    'linkedin' => 'https://linkedin.com',
                    'footer' => 'PT. Purdimen Vanjava',
                    'gmaps' => 'https://goo.gl/maps/PBqSN7chg75uz69HA',
                    'tawkto' => null,
                    'disqus' => null,
                    'gverification' => 'isi dengan script google verification',
                    'sharethis' => null,
                    'keyword' => 'purdimen vanjava',
                    'meta_desc' => 'description',
                    'created_at' => Carbon::parse('2022-03-27 08:43:57'),
                    'updated_at' => Carbon::parse('2022-03-27 08:43:57'),
                ]
            );
        });
    }
}

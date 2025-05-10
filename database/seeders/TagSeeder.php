<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'mobile',
            'desktop',
            'web',
            'admin',
            'user',
            'error',
            'success',
            'notification',
            'email',
            'sms',
            'marketing',
            'legal',
            'help',
            'navigation',
            'footer',
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag]);
        }
    }
}

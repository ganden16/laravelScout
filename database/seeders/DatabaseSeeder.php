<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(5)->create();

        $posts = Post::factory(5);
        User::factory(3)
            ->has($posts)
            ->create();

        Post::factory(3)
            ->for(User::factory()->state([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
            ]), 'author')
            ->create();

        Post::all()->each(function (Post $post) {
            $randomMax = rand(1, 4);
            $randomIdCategory = collect([1, 2, 3, 4, 5])->random($randomMax);
            $post->categories()->sync($randomIdCategory);
        });
    }
}

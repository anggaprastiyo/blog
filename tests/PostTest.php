<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;


class PostTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testPostIndexPositive()
    {
        $this->call('GET', '/post?sortby=created_at&sortbydesc=DESC&q&per_page=10', [], []);
        $this->response->assertStatus(200);
    }

    public function testPostIndexNegative()
    {
        $this->call('GET', '/post');
        $this->response->assertStatus(500);
    }

    public function testPostCountPositive()
    {
        $this->call('GET', '/post/count');
        $this->response->assertStatus(200);
        $this->response->assertJson(['success' => true]);
        // $this->assertEquals(true, $this->response['success']);
    }

    public function testPostCountNegative()
    {
        $this->call('GET', '/post/count/1');
        $this->response->assertStatus(404);
        $this->response->assertJson(['success' => false]);
    }

    public function testPostCreatePositive()
    {
        $faker = \Faker\Factory::create();
        $params = [
            'title' => $faker->text($maxNbChars = 200), //max length = 200
            'author' => $faker->text($maxNbChars = 50), //max length = 200
            'content' => $faker->text($maxNbChars = 1000), //max length = 2000
            'category' => $faker->numberBetween(1, 3),
            'status' => $faker->numberBetween(1, 2)
        ];

        $this->call('POST', '/post/create', $params);
        $this->response->assertStatus(201);
        $this->response->assertJson(['success' => true]);
    }

    public function testPostCreatePositive_TitleMaxFail()
    {
        $faker = \Faker\Factory::create();
        $params = [
            'title' => $faker->text($maxNbChars = 240), //max length = 200
            'author' => $faker->text($maxNbChars = 50), //max length = 200
            'content' => $faker->text($maxNbChars = 1000), //max length = 2000
            'category' => $faker->numberBetween(1, 3), 
            'status' => $faker->numberBetween(1, 2)
        ];

        $this->call('POST', '/post/create', $params);
        $this->response->assertStatus(400); // Bad Exception
        $this->response->assertJson(['success' => false]);
    }

    public function testPostCreatePositive_TitleMinFail()
    {
        $faker = \Faker\Factory::create();
        $params = [
            'title' => $faker->text($maxNbChars = 5), //max length = 200
            'author' => $faker->text($maxNbChars = 50), //max length = 200
            'content' => $faker->text($maxNbChars = 1000), //max length = 2000
            'category' => $faker->numberBetween(1, 3), 
            'status' => $faker->numberBetween(1, 2)
        ];

        $this->call('POST', '/post/create', $params);
        $this->response->assertStatus(400); // Bad Exception
        $this->response->assertJson(['success' => false]);
    }

    public function testPostCreatePositive_TitleNull()
    {
        $faker = \Faker\Factory::create();
        $params = [
            'author' => $faker->text($maxNbChars = 50), //max length = 200
            'content' => $faker->text($maxNbChars = 1000), //max length = 2000
            'category' => $faker->numberBetween(1, 3), 
            'status' => $faker->numberBetween(1, 2)
        ];

        $this->call('POST', '/post/create', $params);
        $this->response->assertStatus(400); // Bad Exception
        $this->response->assertJson(['success' => false]);
    }


}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test users index page displays correctly.
     */
    public function test_index_displays_users(): void
    {
        // Create some users
        $users = User::factory()
            ->count(5)
            ->has(Address::factory())
            ->create();

        // Visit users index page
        $response = $this->get(route('users.index'));

        // Verify the response is successful
        $response->assertStatus(200);
        
        // Verify the users are rendered in the Inertia page
        $response->assertInertia(fn ($page) => $page
            ->component('users/Index')
            ->has('users.data', 5)
        );
    }

    /**
     * Test search functionality on users index.
     */
    public function test_index_can_search_users(): void
    {
        // Create users with specific first names
        User::factory()
            ->has(Address::factory())
            ->create(['first_name' => 'John', 'last_name' => 'Doe']);
        
        User::factory()
            ->has(Address::factory())
            ->create(['first_name' => 'Jane', 'last_name' => 'Smith']);
        
        // Search for 'John'
        $response = $this->get(route('users.index', ['search' => 'John']));
        
        // Verify response and that only one user is returned
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('users/Index')
            ->has('users.data', 1)
            ->where('filters.search', 'John')
        );
    }

    /**
     * Test create user page loads correctly.
     */
    public function test_create_page_displays(): void
    {
        $response = $this->get(route('users.create'));
        
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('users/Create')
        );
    }

    /**
     * Test storing a new user with address.
     */
    public function test_store_creates_new_user(): void
    {
        $userData = [
            'first_name' => 'New',
            'last_name' => 'User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'address' => [
                'country' => 'United States',
                'city' => 'New York',
                'post_code' => '10001',
                'street' => '123 Broadway'
            ]
        ];

        $response = $this->post(route('users.store'), $userData);
        
        // Verify redirect to users index with success message
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User created successfully.');
        
        // Verify user and address were actually created in the database
        $this->assertDatabaseHas('users', [
            'first_name' => 'New',
            'last_name' => 'User',
            'email' => 'newuser@example.com',
        ]);
        
        $this->assertDatabaseHas('addresses', [
            'country' => 'United States',
            'city' => 'New York',
            'post_code' => '10001',
            'street' => '123 Broadway'
        ]);
    }

    /**
     * Test validation for storing a user.
     */
    public function test_store_validates_input(): void
    {
        // Sending empty data to trigger validation
        $response = $this->post(route('users.store'), []);
        
        // Assert that validation fails
        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'password', 'address.country', 'address.city', 'address.post_code', 'address.street']);
    }

    /**
     * Test showing a specific user.
     */
    public function test_show_displays_user(): void
    {
        $user = User::factory()
            ->has(Address::factory())
            ->create();
        
        $response = $this->get(route('users.show', $user));
        
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('users/Show')
            ->has('user')
        );
    }

    /**
     * Test edit page for a user.
     */
    public function test_edit_displays_user_form(): void
    {
        $user = User::factory()
            ->has(Address::factory())
            ->create();
        
        $response = $this->get(route('users.edit', $user));
        
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('users/Edit')
            ->has('user')
        );
    }

    /**
     * Test updating a user.
     */
    public function test_update_changes_user_data(): void
    {
        $user = User::factory()
            ->has(Address::factory())
            ->create();
        
        $updatedData = [
            'first_name' => 'Updated',
            'last_name' => 'User',
            'email' => 'updated@example.com',
            'address' => [
                'country' => 'Canada',
                'city' => 'Toronto',
                'post_code' => 'M5V 2A8',
                'street' => '456 Maple Avenue'
            ]
        ];
        
        $response = $this->put(route('users.update', $user), $updatedData);
        
        // Verify redirect with success message
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User updated successfully.');
        
        // Verify database was updated
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Updated',
            'last_name' => 'User',
            'email' => 'updated@example.com',
        ]);
        
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'country' => 'Canada',
            'city' => 'Toronto',
            'post_code' => 'M5V 2A8',
            'street' => '456 Maple Avenue'
        ]);
    }
    
    /**
     * Test validation for updating a user.
     */
    public function test_update_validates_input(): void
    {
        $user = User::factory()
            ->has(Address::factory())
            ->create();
        
        // Sending empty data to trigger validation
        $response = $this->put(route('users.update', $user), []);
        
        // Assert that validation fails
        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'address.country', 'address.city', 'address.post_code', 'address.street']);
    }
    
    /**
     * Test email uniqueness validation when updating.
     */
    public function test_update_validates_email_uniqueness(): void
    {
        // Create two users
        $user1 = User::factory()
            ->has(Address::factory())
            ->create();
        
        $user2 = User::factory()
            ->has(Address::factory())
            ->create();
        
        // Try to update user2 with user1's email
        $response = $this->put(route('users.update', $user2), [
            'first_name' => 'Updated',
            'last_name' => 'User',
            'email' => $user1->email,
            'address' => [
                'country' => 'Canada',
                'city' => 'Toronto',
                'post_code' => 'M5V 2A8',
                'street' => '456 Maple Avenue'
            ]
        ]);
        
        // Assert that email validation fails
        $response->assertSessionHasErrors(['email']);
    }
}
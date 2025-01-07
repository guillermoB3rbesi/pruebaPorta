<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    Storage::fake('public');
    $avatar = UploadedFile::fake()->image('avatar.jpg');
    $response = $this->post('/register', $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone' => fake()->phoneNumber(),
        'avatar' => $avatar,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $user = User::firstWhere('email', $data['email']);
    $this->assertEquals($data['name'], $user->name);
    $this->assertTrue(Hash::check($data['password'], $user->password));
    $this->assertEquals($data['phone'], $user->phone);

    Storage::disk('public')->assertExists($user->avatar);
});

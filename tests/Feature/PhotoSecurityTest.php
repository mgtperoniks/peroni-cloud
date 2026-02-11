<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_department_is_enforced_on_store()
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'QC User',
            'email' => 'qc@example.com',
            'password' => \Hash::make('password'),
            'role' => 'adminqcfitting',
        ]);

        $response = $this->actingAs($user)->post(route('photos.store'), [
            'photos' => [UploadedFile::fake()->image('test.jpg')],
            'photo_date' => date('Y-m-d'),
            'location' => 'Kantor',
            'department' => 'PPIC', // Try to hijack department
        ]);

        $response->assertRedirect(route('photos.index'));

        $photo = Photo::first();
        $this->assertEquals('QC Fitting', $photo->department);
        $this->assertNotEquals('PPIC', $photo->department);
    }

    public function test_global_admin_can_choose_any_department_on_store()
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => \Hash::make('password'),
            'role' => 'direktur',
        ]);

        $response = $this->actingAs($user)->post(route('photos.store'), [
            'photos' => [UploadedFile::fake()->image('test.jpg')],
            'photo_date' => date('Y-m-d'),
            'location' => 'Kantor',
            'department' => 'K3',
        ]);

        $response->assertRedirect(route('photos.index'));

        $photo = Photo::first();
        $this->assertEquals('K3', $photo->department);
    }

    public function test_non_admin_department_is_enforced_on_update()
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'QC User',
            'email' => 'qc@example.com',
            'password' => \Hash::make('password'),
            'role' => 'adminqcfitting',
        ]);

        $photo = Photo::create([
            'user_id' => $user->id,
            'filename' => 'test.jpg',
            'photo_date' => date('Y-m-d'),
            'location' => 'Kantor',
            'department' => 'QC Fitting',
        ]);

        $response = $this->actingAs($user)->patch(route('photos.update', $photo), [
            'photo_date' => date('Y-m-d'),
            'location' => 'Updated Location',
            'department' => 'PPIC', // Try to hijack
        ]);

        $response->assertRedirect();

        $photo->refresh();
        $this->assertEquals('QC Fitting', $photo->department);
        $this->assertEquals('Updated Location', $photo->location);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendantTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_all_attendants_ok(): void
    {
        \App\Models\Attendant::factory(10)->create();
        $response = $this->get(route('attendants.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'dni',
                    'enabled',
                    'attention_profile',
                ],
            ],
        ]);
        $response->assertJsonCount(10, 'data');
    }


    public function test_get_one_attendant_ok(): void
    {
        $attendant = \App\Models\Attendant::factory()->create();
        $response = $this->get(route('attendants.show', $attendant->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'dni',
                'enabled',
                'attention_profile',
            ],
        ]);
    }

    public function test_get_one_attendant_not_found(): void
    {
        $response = $this->get(route('attendants.show', 100));
        $response->assertStatus(404);
    }

    public function test_create_attendant_ok(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->make([
            'attention_profile_id' => $attentionProfile->id,
        ]);
        $response = $this->post(route('attendants.store'), $attendant->toArray());
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'dni',
                'enabled',
                'attention_profile',
            ],
        ]);
    }

    public function test_create_attendant_validation_error_name_required(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->make([
            'attention_profile_id' => $attentionProfile->id,
            'name' => '',
        ]);
        $response = $this->post(route('attendants.store'), $attendant->toArray());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name', null);
    }

    public function test_create_attendant_validation_error_email_required(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->make([
            'attention_profile_id' => $attentionProfile->id,
            'email' => '',
        ]);
        $response = $this->post(route('attendants.store'), $attendant->toArray());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email', null);
    }

    public function test_create_attendant_validation_error_email_invalid(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->make([
            'attention_profile_id' => $attentionProfile->id,
            'email' => 'invalid-email',
        ]);
        $response = $this->post(route('attendants.store'), $attendant->toArray());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email', null);
    }

    public function test_create_attendant_validation_error_dni_required(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->make([
            'attention_profile_id' => $attentionProfile->id,
            'dni' => '',
        ]);
        $response = $this->post(route('attendants.store'), $attendant->toArray());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dni', null);
    }

    public function test_create_attendant_validation_error_dni_invalid(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->make([
            'attention_profile_id' => $attentionProfile->id,
            'dni' => 'invalid-dni',
        ]);
        $response = $this->post(route('attendants.store'), $attendant->toArray());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dni', null);
    }

    public function test_create_attendant_validation_error_attention_profile_id_required(): void
    {
        $attendant = \App\Models\Attendant::factory()->make([
            'attention_profile_id' => '',
        ]);
        $response = $this->post(route('attendants.store'), $attendant->toArray());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('attention_profile_id', null);
    }

    public function test_create_attendant_validation_error_attention_profile_id_not_exists(): void
    {
        $attendant = \App\Models\Attendant::factory()->make([
            'attention_profile_id' => 100,
        ]);
        $response = $this->post(route('attendants.store'), $attendant->toArray());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('attention_profile_id', null);
    }


    public function test_update_attendant_ok(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->create([
            'attention_profile_id' => $attentionProfile->id,
        ]);
        $data = \App\Models\Attendant::factory()->make([
            'attention_profile_id' => $attentionProfile->id,
        ]);
        $response = $this->put(route('attendants.update', $attendant->id), $data->toArray());
        $response->assertStatus(200);
    }

    public function test_update_attendant_validation_error_name_required(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->create([
            'attention_profile_id' => $attentionProfile->id,
        ]);
        $response = $this->put(route('attendants.update', $attendant->id), ['name' => '']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name', null);
    }

    public function test_update_attendant_validation_error_email_required(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->create([
            'attention_profile_id' => $attentionProfile->id,
        ]);
        $response = $this->put(route('attendants.update', $attendant->id), ['email' => '']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email', null);
    }

    public function test_update_attendant_validation_error_email_invalid(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->create([
            'attention_profile_id' => $attentionProfile->id,
        ]);
        $response = $this->put(route('attendants.update', $attendant->id), ['email' => 'invalid-email']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email', null);
    }

    public function test_update_attendant_validation_error_dni_required(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->create([
            'attention_profile_id' => $attentionProfile->id,
        ]);
        $response = $this->put(route('attendants.update', $attendant->id), ['dni' => '']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dni', null);
    }

    public function test_update_attendant_validation_error_dni_invalid(): void
    {
        $attentionProfile = \App\Models\AttentionProfile::factory()->create();
        $attendant = \App\Models\Attendant::factory()->create([
            'attention_profile_id' => $attentionProfile->id,
        ]);
        $response = $this->put(route('attendants.update', $attendant->id), ['dni' => 'invalid-dni']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('dni', null);
    }

    public function test_update_attendant_validation_error_attention_profile_id_required(): void
    {
        $attendant = \App\Models\Attendant::factory()->create();
        $response = $this->put(route('attendants.update', $attendant->id), ['attention_profile_id' => '']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('attention_profile_id', null);
    }

    public function test_update_attendant_validation_error_attention_profile_id_not_exists(): void
    {
        $attendant = \App\Models\Attendant::factory()->create();
        $response = $this->put(route('attendants.update', $attendant->id), ['attention_profile_id' => 100]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('attention_profile_id', null);
    }

    public function test_update_attendant_not_found(): void
    {
        $response = $this->put(route('attendants.update', 100), []);
        $response->assertStatus(404);
    }

    public function test_delete_attendant_ok(): void
    {
        $attendant = \App\Models\Attendant::factory()->create();
        $response = $this->delete(route('attendants.destroy', $attendant->id));
        $response->assertStatus(204);
    }

    public function test_delete_attendant_not_found(): void
    {
        $response = $this->delete(route('attendants.destroy', 100));
        $response->assertStatus(404);
    }
}
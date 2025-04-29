<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Hospital;

use App\Models\HospitalModel;
use App\Models\StaffModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class HospitalControllerTest extends TestCase
{
    use RefreshDatabase;

    private $guard = 'staffs';

    public function setUp():void
    {
        parent::setUp();

        $this->hospital = HospitalModel::factory()->create([
            'uuid'         => 'b90612f5-3446-47d7-b66a-12ff54963050',
            'name'         => '裕美子病院',
            'zipcode'      => '1234567',
            'address'      => '岩手県佐藤市東区吉本町佐々木1-2-3',
            'phone'        => '0123456789',
            'is_published' => true,
        ]);

        $this->staff = StaffModel::factory()->create([
            'hospital_id' => $this->hospital->id,
            'name'        => '山岸太一',
            'email'       => 'staff+1@example.com',
            'password'    => Hash::make('password'),
        ]);
    }

    /**
     * 病院情報を取得 staffが所属する病院情報が取得できること
     */
    public function testShowSuccess()
    {
        // 準備（Arrange）
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.info.show'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonFragment([
                'uuid'         => 'b90612f5-3446-47d7-b66a-12ff54963050',
                'name'         => '裕美子病院',
                'zipcode'      => '1234567',
                'address'      => '岩手県佐藤市東区吉本町佐々木1-2-3',
                'phone'        => '0123456789',
                'is_published' => true,
            ]);
    }

    /**
     * 病院情報を更新 病院情報を更新できること
     */
    public function testUpdateSuccess()
    {
        // 準備（Arrange）
        Sanctum::actingAs($this->staff, ['*'], $this->guard);
        $postData = [
            'name'         => '裕美子病院x',
            'zipcode'      => '1234560',
            'address'      => '岩手県佐藤市東区吉本町佐々木1-2-0',
            'phone'        => '0123456780',
            'is_published' => false,
        ];

        // 実行（Act）
        $response = $this->put(route('hospital.info.update'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $hospital = HospitalModel::firstWhere('id', $this->staff->hospital->id);
        $this->assertEquals('b90612f5-3446-47d7-b66a-12ff54963050', $hospital->uuid);
        $this->assertEquals('裕美子病院x', $hospital->name);
        $this->assertEquals('1234560', $hospital->zipcode);
        $this->assertEquals('岩手県佐藤市東区吉本町佐々木1-2-0', $hospital->address);
        $this->assertEquals('0123456780', $hospital->phone);
        $this->assertFalse($hospital->is_published);
    }
}

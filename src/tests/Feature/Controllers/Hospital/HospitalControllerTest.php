<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Hospital;

use App\Domains\Location\Enum\Prefecture;
use App\Models\Hospital;
use App\Models\StaffMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;

class HospitalControllerTest extends TestCase
{
    use RefreshDatabase;

    private $guard = 'staff-members';

    public function setUp():void
    {
        parent::setUp();

        $this->hospital = Hospital::factory()->create([
            'name'         => '裕美子病院',
            'post_code'    => '1234567',
            'prefecture'   => Prefecture::Okinawa->value,
            'address1'     => '佐藤市東区吉本町',
            'address2'     => '佐々木1-2-3',
            'phone'        => '0123456789',
            'is_published' => true,
        ]);

        $this->staff = StaffMember::factory()->create([
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
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.info.show'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(8, 'data')
            ->assertJsonFragment([
                'name'         => '裕美子病院',
                'post_code'    => '1234567',
                'prefecture'   => Prefecture::Okinawa->value,
                'address1'     => '佐藤市東区吉本町',
                'address2'     => '佐々木1-2-3',
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
        $hospital = Hospital::factory()->create();
        $staff    = StaffMember::factory()->create(['hospital_id' => $hospital->id]);
        $this->actingAs($staff, $this->guard);
        $postData = [
            'name'         => '裕美子病院',
            'post_code'    => '1234567',
            'prefecture'   => Prefecture::Okinawa->value,
            'address1'     => '佐藤市東区吉本町',
            'address2'     => '佐々木1-2-3',
            'phone'        => '0123456789',
            'is_published' => true,
        ];

        // 実行（Act）
        $response = $this->put(route('hospital.info.update'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $record = Hospital::firstWhere('id', $staff->hospital_id);
        $this->assertEquals($hospital->uuid, $record->uuid);  // uuidは変更されないこと
        $this->assertEquals('裕美子病院', $record->name);
        $this->assertEquals('1234567', $record->post_code);
        $this->assertEquals(Prefecture::Okinawa->value, $record->prefecture);
        $this->assertEquals('佐藤市東区吉本町', $record->address1);
        $this->assertEquals('佐々木1-2-3', $record->address2);
        $this->assertEquals('0123456789', $record->phone);
        $this->assertTrue($record->is_published);
    }
}

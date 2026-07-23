<?php

namespace Tests\Feature;

use App\Models\Presensi;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PresensiNisFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('user_siswa', function ($table) {
            $table->integer('nis')->primary();
            $table->string('nama_siswa')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::dropIfExists('presensi');
        Schema::create('presensi', function ($table) {
            $table->integer('id_presensi')->autoIncrement();
            $table->integer('nis');
            $table->string('tanggal', 30);
            $table->string('jam', 10)->nullable();
            $table->string('status', 15);
            $table->string('keterangan', 25)->nullable();
            $table->string('file', 255)->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('presensi');
        Schema::dropIfExists('user_siswa');

        parent::tearDown();
    }

    public function test_can_get_presensi_by_nis(): void
    {
        $this->withoutMiddleware();

        \DB::table('user_siswa')->insert([
            'nis' => 13862,
            'nama_siswa' => 'Test Siswa',
            'password' => bcrypt('password'),
        ]);

        Presensi::create([
            'nis' => 13862,
            'tanggal' => '2026-07-01',
            'jam' => '07:00',
            'status' => 'hadir',
            'keterangan' => null,
        ]);

        Presensi::create([
            'nis' => 13862,
            'tanggal' => '2026-07-02',
            'jam' => '07:05',
            'status' => 'hadir',
            'keterangan' => null,
        ]);

        Presensi::create([
            'nis' => 99999,
            'tanggal' => '2026-07-01',
            'jam' => '07:00',
            'status' => 'hadir',
            'keterangan' => null,
        ]);

        $response = $this->getJson('/api/ismuba/presensi/by-nis/13862');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $data = $response->json('data.data');
        $this->assertCount(2, $data);
        $this->assertEquals(13862, $data[0]['nis']);
        $this->assertEquals('hadir', $data[0]['status']);
        $this->assertContains($data[0]['tanggal'], ['2026-07-01', '2026-07-02']);
    }
}

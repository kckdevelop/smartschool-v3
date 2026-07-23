<?php

namespace Tests\Feature;

use App\Models\Btaq;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BtaqNisFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('kelas', function ($table) {
            $table->integer('id_kelas')->primary();
            $table->string('nama_kelas')->nullable();
        });

        Schema::create('guru', function ($table) {
            $table->integer('id_guru')->primary();
            $table->string('nama_guru')->nullable();
        });

        Schema::create('user_siswa', function ($table) {
            $table->integer('nis')->primary();
            $table->string('nama_siswa')->nullable();
            $table->integer('id_kelas')->default(1);
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::dropIfExists('btaq');
        Schema::create('btaq', function ($table) {
            $table->integer('id_btaq')->autoIncrement();
            $table->date('tanggal');
            $table->integer('nis');
            $table->integer('id_kelas')->default(13);
            $table->string('level', 15);
            $table->string('awal', 100);
            $table->string('akhir', 100);
            $table->integer('id_guru');
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('btaq');
        Schema::dropIfExists('user_siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('guru');

        parent::tearDown();
    }

    public function test_can_get_btaq_by_nis(): void
    {
        $this->withoutMiddleware();

        \DB::table('kelas')->insert([
            'id_kelas' => 52,
            'nama_kelas' => 'Test Kelas',
        ]);

        \DB::table('guru')->insert([
            'id_guru' => 101,
            'nama_guru' => 'Bapak Guru Test',
        ]);

        \DB::table('user_siswa')->insert([
            'nis' => 13862,
            'nama_siswa' => 'Test Siswa',
            'id_kelas' => 52,
            'password' => bcrypt('password'),
        ]);

        Btaq::create([
            'tanggal' => '2026-06-03',
            'nis' => 13862,
            'id_kelas' => 52,
            'level' => 'iqro1',
            'awal' => 1,
            'akhir' => 2,
            'id_guru' => 101,
        ]);

        Btaq::create([
            'tanggal' => '2026-06-10',
            'nis' => 99999,
            'id_kelas' => 1,
            'level' => 'iqro1',
            'awal' => 2,
            'akhir' => 3,
            'id_guru' => 101,
        ]);

        $response = $this->getJson('/api/ismuba/btaq/by-nis/13862');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id_btaq', 1)
            ->assertJsonPath('data.data.0.level', 'iqro1')
            ->assertJsonPath('data.data.0.awal', '1')
            ->assertJsonPath('data.data.0.akhir', '2')
            ->assertJsonPath('data.data.0.id_guru', 101);
    }
}

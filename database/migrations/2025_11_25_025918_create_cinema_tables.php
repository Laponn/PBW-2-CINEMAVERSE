<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. USERS (Pengguna & Admin)
        // Tabel bawaan Laravel biasanya sudah ada, tapi pastikan kolom 'role' ada.
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'customer'])->default('customer'); // PENTING
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. BRANCHES (Cabang Bioskop)
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');     // Misal: CinemaVerse Grand Indonesia
            $table->string('city');     // Misal: Jakarta
            $table->text('address');    // Alamat lengkap
            $table->timestamps();
        });

        // 3. MOVIES (Data Film)
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('duration_minutes'); // Misal: 120
            $table->date('release_date');
            $table->string('poster_url')->nullable(); 
            $table->string('trailer_url')->nullable(); // <--- REQUEST ANDA (Link Youtube)
            $table->enum('status', ['now_showing', 'coming_soon', 'ended']);
            $table->timestamps();
        });

        // 4. STUDIOS (Ruang Teater)
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('name');     // Misal: Studio 1, The Premiere
            $table->enum('type', ['regular', 'vip', 'imax']); // Kelas Studio
            $table->decimal('base_price', 10, 2); // Harga dasar (sebelum kenaikan weekend)
            $table->integer('capacity'); // Total kursi
            $table->timestamps();
        });

        // 5. SEATS (Denah Kursi Fisik)
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade');
            $table->string('row_label');   // A, B, C
            $table->integer('seat_number'); // 1, 2, 3
            $table->boolean('is_usable')->default(true); // FALSE = Rusak/Maintenance
            $table->timestamps();
        });

        // 6. SHOWTIMES (Jadwal Tayang)
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade');
            $table->dateTime('start_time'); // 2023-10-25 14:00:00
            $table->dateTime('end_time');   // start_time + durasi + cleaning
            $table->decimal('price', 10, 2); // Harga Final (Bisa override base_price studio)
            $table->timestamps();
        });


        
        // 7. BOOKINGS (Transaksi / Invoice)
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('showtime_id')->constrained('showtimes'); // Link ke jadwal
            $table->string('booking_code')->unique(); // Kode unik (misal: CNV-8821)
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'cancelled', 'expired']);
            $table->timestamps();
        });

        // 8. TICKETS (Detail Item Kursi per Transaksi)
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('seat_id')->constrained('seats'); // KUNCI: Kursi ini diambil siapa
            $table->decimal('price', 10, 2); // Harga per kursi saat itu (History)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Hapus tabel harus urutan terbalik dari pembuatan (Child dulu baru Parent)
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('showtimes');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('studios');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('users');
    }
    
};

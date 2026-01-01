<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. USERS
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user'); 
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. BRANCHES (Menambahkan Latitude & Longitude untuk Peta)
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable(); // Diperlukan untuk marker peta
            $table->decimal('longitude', 11, 8)->nullable(); // Diperlukan untuk marker peta
            $table->timestamps();
        });

        // 3. MOVIES (Termasuk kolom Genre & Trailer)
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('duration_minutes');
            $table->date('release_date');
            $table->string('poster_url')->nullable(); 
            $table->string('trailer_url')->nullable(); 
            $table->string('genre')->nullable(); // Fix error 1054
            $table->enum('status', ['now_showing', 'coming_soon', 'ended'])->default('now_showing');
            $table->timestamps();
        });

        // 4. STUDIOS (Termasuk Base Price)
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['regular', 'vip', 'imax'])->default('regular');
            $table->decimal('base_price', 15, 2)->default(0); // Fix error 1364
            $table->integer('capacity');
            $table->timestamps();
        });

        // 5. SEATS
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade');
            $table->string('row_label');
            $table->integer('seat_number');
            $table->boolean('is_usable')->default(true);
            $table->timestamps();
        });

        // 6. SHOWTIMES
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable(); // Bisa dikosongkan jika seeder belum menghitung durasi
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });

        // 7. BOOKINGS
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('showtime_id')->constrained('showtimes')->onDelete('cascade');
            $table->string('booking_code')->unique();
            $table->decimal('total_price', 15, 2);
            $table->enum('payment_status', ['pending', 'paid', 'cancelled', 'expired'])->default('pending');
            $table->timestamps();
        });

        // 8. TICKETS
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
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
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Teachers Table
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nip')->unique();
            $table->string('name');
            $table->timestamps();
        });

        // 2. Create Parents Table
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 3. Create Classes Table
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., X IPA 1
            $table->string('level')->nullable(); // e.g., 10, 11, 12
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null'); // Wali Kelas
            $table->timestamps();
        });

        // 4. Update Students Table
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('name')->constrained('classes')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->after('class_id')->constrained('parents')->onDelete('set null');
            $table->enum('gender', ['L', 'P'])->nullable()->after('name');
            $table->date('birth_date')->nullable()->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['class_id', 'parent_id', 'gender', 'birth_date']);
        });

        Schema::dropIfExists('classes');
        Schema::dropIfExists('parents');
        Schema::dropIfExists('teachers');
    }
};

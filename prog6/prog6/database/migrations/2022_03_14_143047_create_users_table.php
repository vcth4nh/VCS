<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * uid      INT AUTO_INCREMENT PRIMARY KEY,
         * username VARCHAR(15) UNIQUE                                              NOT NULL,
         * password VARCHAR(60)                                                     NULL,
         * name VARCHAR(100) CHARACTER SET utf8mb4 COLLATE 'utf8mb4_general_ci' NOT NULL,
         * email    VARCHAR(320)                                                    NULL,
         * phone    VARCHAR(15)                                                     NULL,
         * teacher  VARCHAR(7)                                                      NOT NULL,
         * avatar   VARCHAR(50)                                                     NULL
         */
        Schema::create('users', function (Blueprint $table) {
            $table->id('uid');
            $table->string('username', 15)->unique();
            $table->string('password');
            $table->string('name')->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->string('email', 320)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('teacher', 7);
            $table->string('avatar', 50)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};

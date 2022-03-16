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
         * chall_id  INT AUTO_INCREMENT PRIMARY KEY,
         * post_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP()                           NOT NULL,
         * hint      VARCHAR(256) CHARACTER SET utf8mb4 COLLATE 'utf8mb4_general_ci' NOT NULL
         */
        Schema::create('challs', function (Blueprint $table) {
            $table->id('chall_id');
            $table->timestamp('post_time')->useCurrent();
            $table->string('hint')->charset('utf8mb4')->collation('utf8mb4_general_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challs');
    }
};

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
         * sub_id        INT AUTO_INCREMENT PRIMARY KEY,
         * exer_id       INT                                                             NOT NULL,
         * uid           INT                                                             NOT NULL,
         * post_time     TIMESTAMP DEFAULT CURRENT_TIMESTAMP()                           NOT NULL,
         * location      VARCHAR(256) UNIQUE                                             NOT NULL,
         * original_name VARCHAR(256) CHARACTER SET utf8mb4 COLLATE 'utf8mb4_general_ci' NOT NULL
         */
        Schema::create('submitted', function (Blueprint $table) {
            $table->id('sub_id');
            $table->bigInteger('exer_id');
            $table->bigInteger('uid');
            $table->timestamp('post_time')->useCurrent();
            $table->string('location')->unique();
            $table->string('original_name')->charset('utf8mb4')->collation('utf8mb4_general_ci');
            $table->index('uid');
//            $table->foreign('uid')->references('uid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submitted');
    }
};

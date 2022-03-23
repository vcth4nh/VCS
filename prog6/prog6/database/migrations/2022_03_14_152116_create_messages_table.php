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
         * msg_id    INT AUTO_INCREMENT PRIMARY KEY,
         * recv_id   INT                                                              NOT NULL,
         * send_id   INT                                                              NOT NULL,
         * recv_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP()                            NOT NULL,
         * text      VARCHAR(1000) CHARACTER SET utf8mb4 COLLATE 'utf8mb4_general_ci' NOT NULL
         */
        Schema::create('messages', function (Blueprint $table) {
            $table->id('msg_id');
            $table->bigInteger('send_uid');
            $table->bigInteger('recv_uid');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->string('text')->charset('utf8mb4')->collation('utf8mb4_general_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};

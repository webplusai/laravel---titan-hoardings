<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->integer('agent_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('installation_id')->unsigned();
            $table->enum('request_type', ['Installation', 'Modification', 'Removal']);
	        $table->enum('type', ['Impact Rated','Kiosk','Std Wind Rated', 'Eco Wind Rated', 'Temporary Fence']);
	        $table->double('tenancy_width');
	        $table->double('distance_from_lease_line');
	        $table->double('ceiling_height');
	        $table->double('dust_suppression');
	        $table->double('specified_wind_speed');
	        $table->enum('panel_type', ['12mm MDF', '16mm WB', '18mm ply', '50mm EPS']);
	        $table->enum('double_door_type', ['Hinged', 'Sliding']);
	        $table->integer('double_door_qty');
	        $table->string('tenancy_name', 255);
	        $table->integer('tenancy_number');
	        $table->text('site_name');
	        $table->text('photos');
	        $table->text('notes');
	        $table->enum('modification_required', ['Move In', 'Move Out', 'Remove Section', 'Extend Hoarding', 'Remove & Install', 'Install Extra Doors']);
	        $table->double('modification_length', 15,1);
	        $table->enum('status', ['Pending', 'Actioned']);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quote_requests');
    }
}

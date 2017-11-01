<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CompaniesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( "companies", function ( $t ) {
			$engine = "InnoDB";
			$t->increments( "id" );
			$t->string( "nimi" );
			$t->string( "nimi_lower" );
			$t->string( "ariregistri_kood" )->unique();
			$t->string( "kmkr_nr" );
			$t->string( "ettevotja_staatus" );
			$t->string( "ettevotja_staatus_tekstina" );
			$t->string( "ettevotja_aadress" );
			$t->string( "asukoht_ettevotja_aadressis" );
			$t->string( "asukoha_ehak_kood" );
			$t->string( "asukoha_ehak_tekstina" );
			$t->string( "indeks_ettevotja_aadressis" );
			$t->string( "ads_adr_id" );
			$t->string( "ads_ads_oid" );
			$t->string( "ads_normaliseeritud_taisaadress" );
			$t->string( "teabesysteemi_link" );
			$t->index( "ariregistri_kood" );
			$t->index( "nimi_lower" );
			$t->timestamps();
		} );

		DB::statement( 'CREATE FULLTEXT INDEX nimi_lower on companies (nimi_lower)' );

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop( "companies" );
	}
}

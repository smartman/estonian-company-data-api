<?php

namespace App\Jobs;

use App\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DbUpdateJob implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $records;

	public function __construct( $records ) {
		$this->records = $records;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		$batchId = str_random();
		info( "Starting to handle batch of DB updates $batchId" );

		//remove BOM
		$bom = chr( 239 ) . chr( 187 ) . chr( 191 );
		foreach ( $this->records as $key => $r ) {
			if ( array_key_exists( $bom . "nimi", $r ) ) {
				$value                         = $r[ $bom . "nimi" ];
				$this->records[ $key ]["nimi"] = $value;
				unset( $this->records[ $key ][ $bom . "nimi" ] );
			}
		}

		foreach ( $this->records as $r ) {
			$company = Company::where( "ariregistri_kood", $r["ariregistri_kood"] )->first();
			if ( $company == null ) {
				info( "Creating new company " . $r["nimi"] . ", " . $r["ariregistri_kood"] );
				$company = new Company();
			}
			if ( ! $company->isSame( $r ) ) {
				info( "Saving company data " . $r["nimi"] . ", " . $r["ariregistri_kood"] );
				$company->setValues( $r );
				$company->save();
			}
		}
		info( "Batch finished $batchId" );
	}

	function string_to_ascii( $string ) {
		$ascii = null;

		for ( $i = 0; $i < strlen( $string ); $i ++ ) {
			echo ord( $string[ $i ] ) . ",";
		}

		return ( $ascii );
	}
}

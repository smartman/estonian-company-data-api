<?php

namespace Importer;


use App\Jobs\ReadDbupdatesFromFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class DataImporter {

	protected $guzzle;
	protected $zip;

	public function __construct( \GuzzleHttp\Client $guzzle, \ZipArchive $zip ) {
		$this->guzzle = $guzzle;
		$this->zip    = $zip;
	}

	public function updateData() {
		$fileName = $this->downloadData();
		if ( $fileName ) {
			$this->scheduleUpdateDb( $fileName );
		} else {
			Log::error( "Cannot update company database" );
		}
	}

	private function downloadData() {
		info( "Starting to download latest version of companies data" );
		$csvZipFile      = $this->guzzle->get( "http://avaandmed.rik.ee/andmed/ARIREGISTER/ariregister_csv.zip" )->getBody();
		$ariRegisterPath = storage_path() . DIRECTORY_SEPARATOR . "ariregister";
		if ( File::exists( $ariRegisterPath ) ) {
			File::deleteDirectory( $ariRegisterPath );
		}
		File::makeDirectory( $ariRegisterPath );
		$zipFileName = $ariRegisterPath . DIRECTORY_SEPARATOR . "ariregister_csv.zip";
		File::put( $zipFileName, $csvZipFile );

		$res = $this->zip->open( $zipFileName );

		if ( $res === true ) {
			$stat        = $this->zip->statIndex( 0 );
			$zipFileName = basename( $stat['name'] );
			$this->zip->extractTo( $ariRegisterPath );
			$this->zip->close();
		} else {
			Log::error( "Zip extracting failed: $res" );

			return false;
		}

		return $ariRegisterPath . DIRECTORY_SEPARATOR . $zipFileName;
	}

	private function scheduleUpdateDb( $csvFileName ) {
		$header = null;
		if ( ( $handle = fopen( $csvFileName, 'r' ) ) !== false ) {
			$header       = fgetcsv( $handle, 0, ";" );
			$fileLocation = ftell( $handle );
			fclose( $handle );
			info( "Starting changing of database update schedulings" );
			Queue::push( new ReadDbupdatesFromFile( $fileLocation, $csvFileName, $header ) );
		}
	}

}
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Queue;

class ReadDbupdatesFromFile implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $fileLocation;
	protected $csvFileName;
	protected $header;

	public function __construct( $fileLocation, $csvFileName, $header ) {
		$this->fileLocation = $fileLocation;
		$this->csvFileName  = $csvFileName;
		$this->header       = $header;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		info( "ReadDbupdatesFromFile job starting" );
		$data    = [];
		$counter = 0;
		if ( ( $handle = fopen( $this->csvFileName, 'r' ) ) !== false ) {
			fseek( $handle, $this->fileLocation );
			while ( ( $row = fgetcsv( $handle, 0, ";" ) ) !== false && $counter < 100 ) {
				$counter ++;
				$data[] = array_combine( $this->header, $row );
			}
			$fileLocation = ftell( $handle );
			fclose( $handle );
			Queue::push( new DbUpdateJob( $data ) );
			if ( $this->fileLocation < $fileLocation ) {
				info( "Adding new batch of company data upgrade to queue. Processed from $this->fileLocation to $fileLocation" );
				Queue::push( new ReadDbupdatesFromFile( $fileLocation, $this->csvFileName, $this->header ) );
			} else {
				info( "All updates read from CSV file" );
			}

		}
	}
}

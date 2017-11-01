<?php

namespace App\Http\Controllers;

use App\Company;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller {

	public function getCompanyByName( Request $request ) {

		$companyName = $request->name;
		if ( strlen( $companyName ) < 3 ) {
			return response()->json( [
				"status"  => "error",
				"message" => "Minimum 3 characters needed"
			], 400 );
		}
		$companyName = str_replace( " ", " ", $companyName );
		info( "Looking for company: $companyName" );

		//If better performance is needed without partial name matches then this condition can be used
		//whereRaw( 'MATCH(nimi_lower) AGAINST(?)', $companyName )
		$companies = Company::where( "nimi_lower", 'like', "%$companyName%" )
		                    ->limit( 5 )
		                    ->get();

		$response = response()->json( $companies );

		//Support also jsonp responses
		if ( $request->callback ) {
			$response->withCallback( $request->callback );
		}

		return $response;
	}
}

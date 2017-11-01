<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Company extends Model {

	public function setValues( $r ) {
		$this->nimi                            = $r["nimi"];
		$this->nimi_lower                      = strtolower( $r["nimi"] );
		$this->ariregistri_kood                = $r["ariregistri_kood"];
		$this->kmkr_nr                         = $r["kmkr_nr"];
		$this->ettevotja_staatus               = $r["ettevotja_staatus"];
		$this->ettevotja_staatus_tekstina      = $r["ettevotja_staatus_tekstina"];
		$this->ettevotja_aadress               = $r["ettevotja_aadress"];
		$this->asukoht_ettevotja_aadressis     = $r["asukoht_ettevotja_aadressis"];
		$this->asukoha_ehak_kood               = $r["asukoha_ehak_kood"];
		$this->asukoha_ehak_tekstina           = $r["asukoha_ehak_tekstina"];
		$this->indeks_ettevotja_aadressis      = $r["indeks_ettevotja_aadressis"];
		$this->ads_adr_id                      = $r["ads_adr_id"];
		$this->ads_ads_oid                     = $r["ads_ads_oid"];
		$this->ads_normaliseeritud_taisaadress = $r["ads_normaliseeritud_taisaadress"];
		$this->teabesysteemi_link              = $r["teabesysteemi_link"];
	}

	public function isSame( $r ) {
		if (
			$this->nimi === $r["nimi"] &&
			$this->nimi_lower === strtolower( $r["nimi"] ) &&
			$this->ariregistri_kood === $r["ariregistri_kood"] &&
			$this->kmkr_nr === $r["kmkr_nr"] &&
			$this->ettevotja_staatus === $r["ettevotja_staatus"] &&
			$this->ettevotja_staatus_tekstina === $r["ettevotja_staatus_tekstina"] &&
			$this->ettevotja_aadress === $r["ettevotja_aadress"] &&
			$this->asukoht_ettevotja_aadressis === $r["asukoht_ettevotja_aadressis"] &&
			$this->asukoha_ehak_kood === $r["asukoha_ehak_kood"] &&
			$this->asukoha_ehak_tekstina === $r["asukoha_ehak_tekstina"] &&
			$this->indeks_ettevotja_aadressis === $r["indeks_ettevotja_aadressis"] &&
			$this->ads_adr_id === $r["ads_adr_id"] &&
			$this->ads_ads_oid === $r["ads_ads_oid"] &&
			$this->ads_normaliseeritud_taisaadress === $r["ads_normaliseeritud_taisaadress"] &&
			$this->teabesysteemi_link === $r["teabesysteemi_link"]
		) {
			return true;
		} else {
			return false;
		}

	}

}
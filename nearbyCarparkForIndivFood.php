<?php 
	class carparkNearBy {
		var $carparkId;
		var $carparkName;
		var $latitude;
		var $longitude;
		var $distance;
		
		function set_carparkId($carparkId) {
			$this->carparkId = $carparkId;
		}
		function get_carparkId() {
			return $this->carparkId;
		}

		function set_carparkName($carparkName) {
			$this->carparkName = $carparkName;
		}
		function get_carparkName() {
			return $this->carparkName;
		}

		function set_latitude($latitude) {
			$this->latitude = $latitude;
		}
		function get_latitude() {
			return $this->latitude;
		}

		function set_longitude($longitude) {
			$this->longitude = $longitude;
		}
		function get_longitude() {
			return $this->longitude;
		}

		function set_distance($distance) {
			$this->distance = $distance;
		}
		function get_distance() {
			return $this->distance;
		}
	}
?>
<?php 
	class carparkNearBy {
		var $carparkId;
		var $carparkName;
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

		function set_distance($distance) {
			$this->distance = $distance;
		}
		function get_distance() {
			return $this->distance;
		}
	}
?>
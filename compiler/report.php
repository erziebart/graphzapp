<?php
	class Report {
		protected $message;
		protected $offset;

		function __construct($message, $offset) {
			$this->message = $message;
			$this->offset = $offset;
		}

		public function get_reason() {
			return $this->message." at character ".$this->offset;
		}
	}
?>
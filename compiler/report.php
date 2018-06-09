<?php
	class Report {
		protected $message;
		protected $offset;

		function __construct($message, $offset) {
			$this->message = $message;
			$this->offset = $offset;
		}

		public function get_reason() {
			if ($this->offset < 0) {
				return $this->message;
			} else {
				return $this->message." at character ".$this->offset;
			}
			
		}
	}
?>
<?php
	class DataBaseConnector
	{
		private $host = 'localhost';
		private $user = 'planner';
		private $pass = 'f47QwKYuw3e4txZx';
		private $type = 'mysqli';
		private $dbName = 'planner';
	
		private $link;
	
		// Connection function
		public function Connect()
		{
			$this->link = mysqli_init();
			mysqli_real_connect($this->link, $this->host, $this->user, $this->pass, $this->dbName);
			$this->link->select_db($this->dbName);
			$this->link->set_charset("utf8");
	
			if (mysqli_connect_errno())
			{
				return mysqli_connect_error();
			}
			else
			{
				return $this->link;
			}
		}
	
		public function Disconnect()
		{
			$this->link->close();
		}
	}
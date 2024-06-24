<?php

class DefaultLogin {
	/** Print login form
	* @return null
	*/
	function loginForm() {
		global $drivers;
		echo "<table cellspacing='0' class='layout'>";
		echo $this->loginFormField('driver', '', 'pgsql');
		echo $this->loginFormField('server', 'POSTGRES_HOST');
		echo $this->loginFormField('username', 'POSTGRES_USER');
		echo $this->loginFormField('password', 'POSTGRES_PASSWORD');
		echo $this->loginFormField('db', 'POSTGRES_DB');
		echo "</table>\n";
		echo "<p><input type='submit' value='Login'>\n";
		return 'wahoo';
	}

	function loginFormField(string $name, string $envValue = '', string $value = ''): string {
		$inputValue = $value;
		if (!empty($envValue) && isset($_ENV[$envValue])) {
			$inputValue = $_ENV[$envValue];
		}
		return "<tr><td><input name=\"auth[{$name}]\" value=\"{$inputValue}\" /></td></tr>";
	}
}

return new DefaultLogin();
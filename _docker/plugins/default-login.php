<?php

class DefaultLogin
{
    /** Print login form.
     */
    public function loginForm()
    {
        echo '<p hidden data-plugin="default-login">Plugin on</p>'.PHP_EOL;
        echo "<table cellspacing='0' class='layout'>".PHP_EOL;
        echo $this->loginFormField('driver', '', 'pgsql').PHP_EOL;
        echo $this->loginFormField('server', 'POSTGRES_HOST').PHP_EOL;
        echo $this->loginFormField('username', 'POSTGRES_USER').PHP_EOL;
        echo $this->loginFormField('password', 'POSTGRES_PASSWORD').PHP_EOL;
        echo $this->loginFormField('db', 'POSTGRES_DB').PHP_EOL;
        echo '</table>'.PHP_EOL;
        echo "<p><input type='submit' value='Login'>".PHP_EOL;

        return 'wahoo';
    }

    public function loginFormField(string $name, string $envValue = '', string $value = ''): string
    {
        $inputValue = $value;
        if (!empty($envValue) && isset($_ENV[$envValue])) {
            $inputValue = $_ENV[$envValue];
        }

        return "<tr><td><input name=\"auth[{$name}]\" value=\"{$inputValue}\" /></td></tr>";
    }
}

return new DefaultLogin();

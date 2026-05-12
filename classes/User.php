<?php

class User {

    protected $username;
    protected $password;

    // Constructor
    public function __construct($username, $password) {

        $this->username = $username;
        $this->password = $password;
    }

    // Method login
   public function login() {

    return true;
}
}

?>
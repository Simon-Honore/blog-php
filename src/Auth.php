<?php

namespace App;

use App\Security\ForbiddenException;

class Auth
{

  /**
   * Checks if the user is authorized to access the administrator area
   */
  public static function check()
  {
    if (session_status() !== 2) {
      session_start();
    }
    if (!isset($_SESSION['auth'])) {
      throw new ForbiddenException();
    }
  }
}

<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class Single extends Controller
{
  public function postType() {    
    return get_post_type( get_post()->ID );
  }
}

<?php

use Ramsey\Uuid\Uuid;

function generateUuid(){

  return  Uuid::uuid4()->toString();
  
}


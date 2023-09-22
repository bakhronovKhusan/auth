<?php

return [
  'redis_each_expire' =>  env('REDIS_EACH_KEY',86400), //3 месяц
  'redis_each_expire_app' =>  env('REDIS_EACH_KEY_APP',7776000), //3 месяц
];

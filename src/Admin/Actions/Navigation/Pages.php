<?php

namespace SmartCms\Reviews\Admin\Actions\Navigation;

use SmartCms\Reviews\Admin\Pages\ReviewSettings;

class Pages
{
   public function __invoke(array &$items)
   {
      $items = array_merge([
         ReviewSettings::class,
      ], $items);
   }
}

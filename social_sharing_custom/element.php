<?php
/**
 * @package   Essentials YOOtheme Pro 1.5.8
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

return [

    'yooessentialsUpdates' => [

        '1.1.0' => function ($node) {
            if (!empty($node->props['icon_ratio'])) {
                $node->props['icon_width'] = round(20 * $node->props['icon_ratio']);
                unset($node->props['icon_ratio']);
            }
        }

    ]

];

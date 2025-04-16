<?php

/**
 * @package   Essentials YOOtheme Pro 1.5.8
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace YOOtheme;

use YOOtheme\Builder\ElementTransform;

return [

    'transforms' => [

        'render' => function ($node, array $params) {

            /** @var Config $config */
            $config = app(Config::class);

            /** @var View $view */
            $view = app(View::class);

            /** @var Metadata $metadata */
            $metadata = app(Metadata::class);

            /** @var ElementTransform $transform */
            $transform = new ElementTransform($view);

            // Load custom JS file
            $metadata->set('script:social-sharing-network', ['src' => Path::get('./asset.js'), 'defer' => true]);

            //load custom css file 
           $metadata->set('style:social-sharing-network', ['href' => Path::get('./assets.css')]);

            // Get permalink
            $permalink = $config->get('req')['href'];

            // Social share templates
            $networks = [
                'twitter'    => 'https://twitter.com/intent/tweet?text=%s',
                'facebook'   => 'https://www.facebook.com/sharer/sharer.php?u=%s',
                'whatsapp'   => 'https://api.whatsapp.com/send?text=%s',
                'linkedin'   => 'https://www.linkedin.com/shareArticle?mini=true&url=%s',
                'telegram'   => 'https://telegram.me/share/url?url=%s',
                'mail'   => 'mailto:?subject=Check this out!&body=%s',
                'pinterest'  => 'http://pinterest.com/pin/create/button/?url=%s',
                'copylink'   => '%s', // raw link (used for clipboard)
                'custom'     => $node->props['custom_link'] ?? ''
            ];

            // Get selected network and build the link
            $network = $node->network = $node->props['link'];
            $node->props['link'] = sprintf($networks[$network] ?? null, $permalink);
            $node->title = $node->props['title'] ?? null;

            // If popup target
            if ($node->props['link_target'] === 'popup') {
                $node->popup = json_encode([
                    'width'  => $node->props['link_target_width'] ?: 600,
                    'height' => $node->props['link_target_height'] ?: 600
                ]);
            }

            // Set base attributes
            $node->attrs += [
                'id'    => $node->props['id'] ?? null,
                'class' => !empty($node->props['class']) ? [$node->props['class']] : [],
            ];

           
            if ($node->network === 'copylink') {

                $message = $node->props['message'] ?? 'Link copied!';
            
                // Add class and data-message attribute
                $node->attrs['class'][] = 'copy-share-link';
                $node->attrs['data-message'] = $message;
            
                // Build the attributes into a string
                $attrs = [];
                foreach ($node->attrs as $key => $value) {
                    if (is_array($value)) {
                        $value = implode(' ', $value);
                    }
                    $attrs[] = sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
                }
                $attrString = implode(' ', $attrs);
            
                // Render the button
                return sprintf(
                    '<button type="button" %s>%s</button>',
                    $attrString,
                    $node->title ?: 'Link copied!'
                );
            }
            
            

            // Apply additional attributes
            $transform->customAttributes($node);

            // Don't render element if content fields are empty
            return $node->props['link'];
        }

    ],

    'yooessentialsUpdates' => [

        '1.2.0-beta' => function ($node) {
            if (is_bool($node->props['link_target'] ?? '')) {
                $node->props['link_target'] = $node->props['link_target'] ? '_blank' : '_self';
            }
        }

    ]

];

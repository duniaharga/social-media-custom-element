<?php
/**
 * @package   Essentials YOOtheme Pro 1.5.8
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

$icon = $this->el('a', [

    'rel' => 'noreferrer',

    'href' => $node->props['link'],

    'title' => $node->title,

    'class' => array_merge([
        'el-link',
        'uk-icon-link {@!link_style}',
        'uk-icon-button {@link_style: button}',
        'uk-link-{link_style: muted|text|reset}',
    ], $attrs['class']),

    'uk-icon' => [
        $node->network === 'custom' && $props['icon']
            ? "icon: {$this->e($props['icon'])};"
            : "icon: {$this->e($props['link'], 'social')};",
        'width: {icon_width}; height: {icon_width}; {@!link_style: button}',
    ]

]);

if ($props['link_target'] === 'popup') {
    $icon->attrs['data-yooessentials-social-popup'] = $node->popup;
} else {
    $icon->attrs['target'] = $props['link_target'] ?? '';
}

?>

<?= $icon($element, $attrs, '') ?>

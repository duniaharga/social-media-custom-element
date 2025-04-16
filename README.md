🔗 YOOtheme Pro – Custom Social Share Element
This custom element adds social media sharing functionality to your YOOtheme Pro site, including:

* Social share icons includes:
 - Facebook
 -  Twitter/X
 -  LnkedIn
 -  Whatsapp
 -  Telegram
 -  E-mail
 -  
* A custom Soical media  field to copy the current page URL (show image bleow):

  ![image](https://github.com/user-attachments/assets/4c32e4c1-02b3-40af-8391-0d6274f72c79)


* A custom toast notification when the link is copied:
  




1- Method Overview:

- element.json
  {
    "@import": "./element.php",
    "name": "yooessentials_social_sharing_item_custom",
    "title": "Network",
    "width": 500,
    "templates": {
        "render": "./templates/template.php",
        "content": "./templates/content.php"
    },
    "defaults": {
        "link_target": "_blank",
        "link_target_width": "",
        "link_target_height": ""
    },
    "fields": {
        "link": {
            "label": "Social Network",
            "type": "select",
            "default": "twitter",
            "options": {
                "Twitter": "twitter",
                "Facebook": "facebook",
                "WhatsApp": "whatsapp",
                "LinkedIn": "linkedin",
                "Telegram": "telegram",
                "E-mail": "mail",
                "Pinterest": "pinterest",
                "Copylink": "copylink",
                "Custom": "custom"
            }
        },
        "message": {
            "label": "Copy Message",
            "description": "This message will be shown when the link is copied.",
            "type": "text",
            "default": "link copied! ",
            "show": "link == 'copylink'"
          },
          
        "custom_link": {
            "label": "Link",
            "attrs": {
                "placeholder": "http://mysharer.com/?u=%s"
            },
            "description": "Set a custom share link where <code>%s</code> is a reference to the current site url.",
            "show": "link == 'custom'"
        },
        "link_target": {
            "label": "Target",
            "description": "Set the target window for the sharing links to open.",
            "type": "select",
            "options": {
                "New Window": "_blank",
                "Same Window": "_self",
                "PopUp Window": "popup"
            }
        },
        "link_target_width": {
            "label": "Width",
            "attrs": {
                "placeholder": 600
            }
        },
        "link_target_height": {
            "label": "Height",
            "attrs": {
                "placeholder": 600
            }
        },
        "title": {
            "label": "Title",
            "description": "An optional title for the link."
        },
        "icon": {
            "label": "Icon",
            "description": "Instead of using a custom image, you can click on the pencil to pick an icon from the icon library.",
            "type": "icon",
            "source": true,
            "show": "link == 'custom'"
        },
        "status": "${builder.status}",
        "id": "${builder.id}",
        "class": "${builder.cls}",
        "attributes": "${builder.attrs}"
    },
    "fieldset": {
        "default": {
            "type": "tabs",
            "fields": [
                {
                    "title": "Content",
                   
                    "fields": [
                        "link",
                        "custom_link",
                        "link_target",
                        {
                            "description": "The target window specifications.",
                            "name": "_target",
                            "type": "grid",
                            "width": "1-2",
                            "fields": [
                                "link_target_width",
                                "link_target_height"
                            ],
                            "show": "link_target === 'popup'"
                        },
                        "title",
                        "icon",
                        "message"
                    ]
                },
                "${builder.advanced}"
            ]
        }
    }
}

2- template.php

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



3-script.js

//copy sharing link 


document.addEventListener("DOMContentLoaded", function () {
    const toast = document.createElement("div");
    toast.className = "copy-toast";
    document.body.appendChild(toast);

    document.body.addEventListener("click", function (e) {
        const shareButton = e.target.closest(".copy-share-link");

        if (!shareButton) return;
        e.preventDefault(); // 🛑 يمنع التحديث
        const currentUrl = window.location.href;
        const message = shareButton.getAttribute("data-message") || "Link copied!";

        navigator.clipboard.writeText(currentUrl).then(() => {
            toast.textContent = message;
            toast.classList.add("show");

            setTimeout(() => {
                toast.classList.remove("show");
            }, 2000);
        });
    });
});



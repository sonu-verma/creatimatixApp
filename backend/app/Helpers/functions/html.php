<?php 


if (!function_exists('quick_buttons')) {
    function quick_buttons($items) {
        $buttons = button_list();
        $html = '';
        if ($items && !empty($items)) {
            foreach ($items as $key => $btn) {
                $attributes = [];
                if (is_string($key)) {
                    $_button = $key;
                    $attributes = $btn;
                } else {
                    $_button = $btn;
                }
                if (array_key_exists($_button, $buttons)) {

                    $style = $buttons[$_button];

                    if (array_key_exists('attributes', $style)) {
                        $attributes = array_merge($style['attributes'], $attributes);
                    }
                    $attribute = urldecode(str_replace('=', '="', http_build_query($attributes, 'null', '" ')) . '"');
                    $html .= '<a ' . $attribute . '><i class="' . $style['icon'] . '"></i></a>';
                }
            }

        }
        if ($html !== '') {
            return '<div class="f-right btn-group ">' . $html . '</div>';
        }
        return null;
    }
}


if (!function_exists('button_list')) {
    function button_list() {
        return [
            'messages' => [
                'icon' => 'icofont icofont-envelope-open',
                'label' => 'Messages',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-primary btn-mini waves-effect waves-light',
                    'title' => 'Messages',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'edit' => [
                'icon' => 'icofont icofont-pencil-alt-5',
                'label' => 'Edit',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-warning btn-mini waves-effect waves-light',
                    'title' => 'Edit',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'add' => [
                'icon' => 'icofont icofont-plus',
                'label' => 'Add',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-success btn-mini waves-effect waves-light',
                    'title' => 'Add',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'refresh' => [
                'icon' => 'icofont icofont-refresh',
                'label' => 'Refresh',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-info btn-mini waves-effect waves-light',
                    'title' => 'Refresh',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'configure' => [
                'icon' => 'icofont icofont-gear',
                'label' => 'Configure',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-primary btn-mini waves-effect waves-light',
                    'title' => 'Configure',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'external-link' => [
                'icon' => 'icofont icofont-external-link',
                'label' => 'Open',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-primary btn-mini waves-effect waves-light',
                    'title' => 'Open in new window',
                    'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'link' => [
                'icon' => 'icofont icofont-link-alt',
                'label' => 'Open',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-primary btn-mini waves-effect waves-light',
                    'title' => 'Open link',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'login' => [
                'icon' => 'icofont icofont-login',
                'label' => 'Login',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-primary btn-mini waves-effect waves-light',
                    'title' => 'Login',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'delete' => [
                'icon' => 'icofont icofont-ui-delete',
                'label' => 'Delete',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-danger btn-mini waves-effect waves-light',
                    'title' => 'Delete',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'trash' => [
                'icon' => 'icofont icofont-trash',
                'label' => 'Trash',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-danger btn-mini waves-effect waves-light',
                    'title' => 'Trash',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'filter' => [
                'icon' => 'icofont icofont-filter',
                'label' => 'Filter',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-primary btn-mini waves-effect waves-light',
                    'title' => 'Filter',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'view' => [
                'icon' => 'icofont icofont-ui-zoom-in',
                'label' => 'View',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-info btn-mini waves-effect waves-light',
                    'title' => 'View',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'picked' => [
                'icon' => 'icofont icofont-ui-check',
                'label' => 'Mark as Picked',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-success btn-mini waves-effect waves-light',
                    'title' => 'Mark as Picked',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'mail' => [
                'icon' => 'icofont icofont-ui-email',
                'label' => 'Send Reminder',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-info btn-mini waves-effect waves-light',
                    'title' => 'Send Reminder',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'back' => [
                'icon' => 'icofont icofont-arrow-left',
                'label' => 'Back',
                'attributes' => [
                    'href' => 'javascript:history.go(-1)',
                    'class' => 'btn btn-info btn-mini waves-effect waves-light',
                    'title' => 'Back',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'accept' => [
                'icon' => 'icofont icofont-verification-check',
                'label' => 'Accept',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-success btn-mini waves-effect waves-light',
                    'title' => 'Accept',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'reject' => [
                'icon' => 'icofont icofont-close',
                'label' => 'Reject',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-danger btn-mini waves-effect waves-light',
                    'title' => 'Reject',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'return' => [
                'icon' => 'icofont icofont-reply',
                'label' => 'Reject',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-danger btn-mini waves-effect waves-light',
                    'title' => 'Return',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'print' => [
                'icon' => 'icofont icofont-print',
                'label' => 'Print',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-success btn-mini waves-effect waves-light',
                    'title' => 'Print',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'export' => [
                'icon' => 'icofont icofont-download',
                'label' => 'Export',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-primary btn-mini waves-effect waves-light',
                    'title' => 'Download',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ],
            'import' => [
                'icon' => 'icofont icofont-upload',
                'label' => 'Import',
                'attributes' => [
                    'href' => '#!',
                    'class' => 'btn btn-danger btn-mini waves-effect waves-light',
                    'title' => 'Upload',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                ]
            ]
        ];
    }
}

<?php

if ($data['fields_values']['chat_type'] == 0) {
    (new CWidgetView($data))
        ->addItem(
            (new CDiv([
                (new CDiv([
                    // Chat-specific content here
                ]))
                    ->setId('chat-log')
                    ->addClass('chat-log'),
                (new CDiv([
                    (new CInput('text'))
                        ->addClass('chat-form-message')
                        ->setAttribute('placeholder', _('Send a message to ZabbixAI')),
                    (new CButton('send-button', '➜'))
                        ->addClass('chat-form-button')
                        ->setAttribute('type', 'submit'),
                ]))
                    ->addClass('chat-form'),
            ]))
            ->setId('chat-container')
            ->addClass('chat-container')
            ->setAttribute('api-token', $data['fields_values']['token'])
        )
        ->show();
} elseif ($data['fields_values']['chat_type'] != 0) {
    (new CWidgetView($data))
        ->addItem(
            (new CDiv([
                (new CDiv([
                    // Report-specific content here
                ]))
                    ->setId('chat-log')
                    ->addClass('chat-log'),
                (new CDiv([
                    (new CInput('text'))
                        ->addClass('chat-form-message')
                        ->setAttribute('placeholder', _('Send a message')),
                    (new CButton('send-button', 'Make report'))
                        ->addClass('chat-form-button')
                        ->setAttribute('type', 'submit'),
                ]))
                    ->addClass('chat-form'),
            ]))
            ->setId('chat-container')
            ->addClass('chat-container')
            ->setAttribute('api-token', $data['fields_values']['token'])
        )
        ->show();
}
?>



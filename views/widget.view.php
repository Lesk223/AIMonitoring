<?php

// Assume $evalType is your variable that will either be 'chat' or 'report'
// This could come from the $data array or be directly set based on your application's logic

if ($data['fields_values']['evaltype']==0) {
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
                        ->setAttribute('placeholder', _('Send a message to YandexGPT')),
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
  } elseif ($data['fields_values']['evaltype']!=0 ) {
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
                        ->setAttribute('placeholder', _('Send a message ')),
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
